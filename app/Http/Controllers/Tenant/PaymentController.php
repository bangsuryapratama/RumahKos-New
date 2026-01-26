<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Midtrans
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function midtrans(Payment $payment)
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        
        // Share contact & address
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);
        $address = Property::select('address')->first();
        view()->share('address', $address);
        
        // Validasi payment milik user
        if ($payment->resident->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($payment->status === 'paid') {
            return redirect()->route('tenant.bookings.index')
                ->with('success', 'Pembayaran sudah lunas.');
        }

        $resident = $payment->resident;
        $room = $resident->room;
        
        // Generate order ID yang unique
        $orderId = 'PAYMENT-' . $payment->id . '-' . time();
        
        // Transaction details
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $payment->amount,
        ];

        // Item details
        $itemDetails = [
            [
                'id' => 'payment-' . $payment->id,
                'price' => (int) $payment->amount,
                'quantity' => 1,
                'name' => $payment->description ?: "Sewa {$room->name}",
            ]
        ];

        // Customer details
        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->profile->phone ?? $user->phone ?? '08123456789',
        ];

        // Midtrans parameters
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => route('tenant.payment.finish', $payment->id),
            ]
        ];

        try {
            // Get Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Save order_id and snap_token to database
            $payment->update([
                'order_id' => $orderId,
                'snap_token' => $snapToken,
            ]);

            return view('tenant.payment.midtrans', compact('payment', 'snapToken', 'resident', 'room', 'contact', 'address'));
            
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            return redirect()->route('tenant.bookings.index')
                ->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function finish(Payment $payment)
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        
        // Share contact & address
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);
        $address = Property::select('address')->first();
        view()->share('address', $address);
        
        if ($payment->resident->user_id !== $user->id) {
            abort(403);
        }

        return view('tenant.payment.finish', compact('payment', 'contact', 'address'));
    }

    public function callback(Request $request)
    {
        // Log semua request yang masuk
        Log::info('=== MIDTRANS CALLBACK RECEIVED ===');
        Log::info('Request Body: ', $request->all());
        Log::info('Headers: ', $request->headers->all());

        try {
            // Get notification dari Midtrans
            $notification = new Notification();
            
            Log::info('Midtrans Notification Object', [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status' => $notification->fraud_status ?? 'accept',
                'transaction_id' => $notification->transaction_id,
                'payment_type' => $notification->payment_type ?? 'unknown',
            ]);

            // Extract payment ID dari order_id (format: PAYMENT-{id}-{timestamp})
            $orderId = $notification->order_id;
            $parts = explode('-', $orderId);
            
            Log::info('Parsing Order ID', [
                'order_id' => $orderId,
                'parts' => $parts,
            ]);
            
            $paymentId = $parts[1] ?? null;

            if (!$paymentId) {
                Log::error('Invalid order ID format: ' . $orderId);
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            $payment = Payment::find($paymentId);

            if (!$payment) {
                Log::error('Payment not found: ' . $paymentId);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            Log::info('Payment Found', [
                'payment_id' => $payment->id,
                'current_status' => $payment->status,
            ]);

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? 'accept';
            $transactionId = $notification->transaction_id;

            // Process payment based on transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Success - Credit Card
                    Log::info('Processing CAPTURE with fraud_status: accept');
                    $this->setPaymentSuccess($payment, $transactionId);
                } else {
                    Log::warning('CAPTURE but fraud_status is: ' . $fraudStatus);
                }
            } elseif ($transactionStatus == 'settlement') {
                // Success - Transfer/E-Wallet/Other
                Log::info('Processing SETTLEMENT');
                $this->setPaymentSuccess($payment, $transactionId);
            } elseif ($transactionStatus == 'pending') {
                // Pending - Waiting payment
                Log::info('Processing PENDING');
                $payment->update([
                    'status' => 'pending',
                    'transaction_id' => $transactionId,
                ]);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                // Failed
                Log::info('Processing FAILED: ' . $transactionStatus);
                $this->setPaymentFailed($payment, $transactionId);
            } else {
                Log::warning('Unknown transaction status: ' . $transactionStatus);
            }

            Log::info('=== CALLBACK PROCESSED SUCCESSFULLY ===');
            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error('=== MIDTRANS CALLBACK ERROR ===');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    private function setPaymentSuccess(Payment $payment, $transactionId)
    {
        Log::info('Setting Payment Success', [
            'payment_id' => $payment->id,
            'transaction_id' => $transactionId,
        ]);

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $transactionId,
        ]);

        Log::info('Payment Updated to PAID', [
            'payment_id' => $payment->id,
            'new_status' => $payment->fresh()->status,
        ]);

        // Jika ini pembayaran pertama, aktivasi resident
        $resident = $payment->resident;
        
        // Cek apakah ini pembayaran bulan pertama
        $firstPayment = $resident->payments()
            ->orderBy('billing_month', 'asc')
            ->first();
            
        if ($payment->id === $firstPayment->id && $resident->status === 'inactive') {
            Log::info('Activating Resident', ['resident_id' => $resident->id]);
            
            $resident->update(['status' => 'active']);
            $resident->room->update(['status' => 'occupied']);
            
            Log::info('Resident Activated', [
                'resident_id' => $resident->id,
                'new_status' => $resident->fresh()->status,
            ]);
        }

        Log::info('=== PAYMENT SUCCESS COMPLETED ===');
    }

    private function setPaymentFailed(Payment $payment, $transactionId)
    {
        Log::info('Setting Payment Failed', [
            'payment_id' => $payment->id,
            'transaction_id' => $transactionId,
        ]);

        $payment->update([
            'status' => 'failed',
            'transaction_id' => $transactionId,
        ]);

        // Jika ini pembayaran pertama yang gagal, cancel booking
        $resident = $payment->resident;
        
        $firstPayment = $resident->payments()
            ->orderBy('billing_month', 'asc')
            ->first();
            
        if ($payment->id === $firstPayment->id && $resident->status === 'inactive') {
            Log::info('Cancelling Booking', ['resident_id' => $resident->id]);
            
            $resident->update(['status' => 'cancelled']);
            
            if ($resident->room) {
                $resident->room->update(['status' => 'available']);
            }
            
            Log::info('Booking Cancelled', ['resident_id' => $resident->id]);
        }

        Log::info('=== PAYMENT FAILED COMPLETED ===');
    }

    // Manual check status (untuk debugging)
    public function checkStatus(Payment $payment)
    {
        if (!$payment->order_id) {
            return response()->json(['error' => 'No order_id found'], 400);
        }

        try {
            $status = \Midtrans\Transaction::status($payment->order_id);
            
            Log::info('Manual Status Check', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'status' => $status,
            ]);

            return response()->json([
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'midtrans_status' => $status,
                'db_status' => $payment->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}