<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function midtrans(Payment $payment)
    {

       
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);
        $address = Property::select('address')->first();
        view()->share('address', $address);

        $user = Auth::guard('tenant')->user();
        
        // Cek authorization
        if ($payment->resident->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Kalau udah bayar
        if ($payment->status === 'paid') {
            return redirect()->route('tenant.bookings.index')
                ->with('success', 'Pembayaran sudah lunas.');
        }

        try {
            $snapToken = $this->createSnapToken($payment, $user);
            
            return view('tenant.payment.midtrans', [
                'payment' => $payment,
                'snapToken' => $snapToken,
                'resident' => $payment->resident,
                'room' => $payment->resident->room,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            return redirect()->route('tenant.bookings.index')
                ->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function finish(Payment $payment)
    {
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);
        $address = Property::select('address')->first();
        view()->share('address', $address);

        $user = Auth::guard('tenant')->user();
        
        if ($payment->resident->user_id !== $user->id) {
            abort(403);
        }

        return view('tenant.payment.finish', compact('payment','address','contact'));
    }

    public function callback(Request $request)
    {
        Log::info('MIDTRANS CALLBACK', $request->all());

        // Skip test notification
        if (empty($request->order_id) || str_contains($request->order_id, 'payment_notif_test')) {
            return response()->json(['message' => 'Test OK']);
        }

        // Parse order_id
        $parts = explode('-', $request->order_id);
        if (count($parts) < 3) {
            Log::warning('Invalid order_id format');
            return response()->json(['message' => 'Invalid order']);
        }

        $paymentId = $parts[1];
        $payment = Payment::find($paymentId);

        if (!$payment) {
            Log::warning('Payment not found');
            return response()->json(['message' => 'Payment not found']);
        }

        // Update payment status
        $status = $request->transaction_status;
        $transactionId = $request->transaction_id;

        if (in_array($status, ['settlement', 'capture'])) {
            $this->handleSuccess($payment, $transactionId);
        } elseif ($status === 'pending') {
            $payment->update([
                'status' => 'pending',
                'transaction_id' => $transactionId
            ]);
        } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
            $this->handleFailed($payment, $transactionId);
        }

        return response()->json(['message' => 'OK']);
    }

    // PRIVATE METHODS

    private function createSnapToken(Payment $payment, $user)
    {
        $resident = $payment->resident;
        $room = $resident->room;
        $orderId = 'PAYMENT-' . $payment->id . '-' . time();
        
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $payment->amount,
            ],
            'item_details' => [
                [
                    'id' => 'payment-' . $payment->id,
                    'price' => (int) $payment->amount,
                    'quantity' => 1,
                    'name' => $payment->description ?: "Sewa {$room->name}",
                ]
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile->phone ?? $user->phone ?? '08123456789',
            ],
            'callbacks' => [
                'finish' => route('tenant.payment.finish', $payment->id),
            ]
        ];

        $snapToken = Snap::getSnapToken($params);
        
        $payment->update([
            'order_id' => $orderId,
            'snap_token' => $snapToken,
        ]);

        return $snapToken;
    }

    private function handleSuccess(Payment $payment, $transactionId)
    {
        Log::info('Payment SUCCESS', ['payment_id' => $payment->id]);

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $transactionId,
        ]);

        // Aktivasi resident kalau payment pertama
        $resident = $payment->resident;
        $firstPayment = $resident->payments()->orderBy('billing_month')->first();
        
        if ($payment->id === $firstPayment->id && $resident->status === 'inactive') {
            $resident->update(['status' => 'active']);
            $resident->room->update(['status' => 'occupied']);
            Log::info('Resident ACTIVATED', ['resident_id' => $resident->id]);
        }
    }

    private function handleFailed(Payment $payment, $transactionId)
    {
        Log::info('Payment FAILED', ['payment_id' => $payment->id]);

        $payment->update([
            'status' => 'failed',
            'transaction_id' => $transactionId,
        ]);

        // Cancel booking kalau payment pertama gagal
        $resident = $payment->resident;
        $firstPayment = $resident->payments()->orderBy('billing_month')->first();
        
        if ($payment->id === $firstPayment->id && $resident->status === 'inactive') {
            $resident->update(['status' => 'cancelled']);
            $resident->room?->update(['status' => 'available']);
            Log::info('Booking CANCELLED', ['resident_id' => $resident->id]);
        }
    }
}