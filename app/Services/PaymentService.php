<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Create Snap Token for a Payment
     */
    public function createSnapToken(Payment $payment, User $user): string
    {
        $orderId = 'PAY-' . $payment->id . '-' . Str::random(5);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile?->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'ROOM-' . ($payment->resident->room_id ?? '1'),
                    'price' => (int) $payment->amount,
                    'quantity' => 1,
                    'name' => 'Sewa ' . ($payment->resident->room->name ?? 'Kamar Kost'),
                ]
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $payment->update([
            'order_id' => $orderId,
            'snap_token' => $snapToken,
        ]);

        return $snapToken;
    }

    /**
     * Handle payment success webhook / callback
     */
    public function handleSuccess(Payment $payment, ?string $transactionId = null): void
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $transactionId ?? $payment->transaction_id,
        ]);

        // Activate resident contract if inactive
        $resident = $payment->resident;
        if ($resident && $resident->status === 'inactive') {
            $resident->update(['status' => 'active']);
            $resident->room?->update(['status' => 'occupied']);
        }
    }

    /**
     * Handle payment failure/cancel webhook / callback
     */
    public function handleFailed(Payment $payment, ?string $transactionId = null): void
    {
        $payment->update([
            'status' => 'failed',
            'transaction_id' => $transactionId ?? $payment->transaction_id,
        ]);
    }
}
