<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentApiController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * FULL HISTORY
     */
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::whereHas('resident', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->orderBy('billing_month', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * CREATE / GET SNAP TOKEN
     */
    public function midtrans($id)
    {
        $user = Auth::user();

        $payment = Payment::whereHas('resident', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah lunas'
            ], 400);
        }

        try {
            $snapToken = $this->createSnapToken($payment, $user);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $payment->order_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Snap Token'
            ], 500);
        }
    }

    /**
     * MIDTRANS CALLBACK
     */
    public function callback(Request $request)
    {
        Log::info('MIDTRANS CALLBACK', $request->all());

        if (empty($request->order_id)) {
            return response()->json(['message' => 'Invalid']);
        }

        $parts = explode('-', $request->order_id);
        $paymentId = $parts[1] ?? null;

        $payment = Payment::find($paymentId);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found']);
        }

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

    // ===============================
    // PRIVATE METHODS (SAMA KAYA WEB)
    // ===============================

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
            'item_details' => [[
                'id' => 'payment-' . $payment->id,
                'price' => (int) $payment->amount,
                'quantity' => 1,
                'name' => $payment->description ?: "Sewa {$room->name}",
            ]],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile->phone ?? $user->phone ?? '08123456789',
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
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $transactionId,
        ]);
    }

    private function handleFailed(Payment $payment, $transactionId)
    {
        $payment->update([
            'status' => 'failed',
            'transaction_id' => $transactionId,
        ]);
    }

    public function checkStatus($id)
{
    $user = Auth::user();

    $payment = Payment::whereHas('resident', function ($q) use ($user) {
        $q->where('user_id', $user->id);
    })->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => $payment
    ]);
}
}
