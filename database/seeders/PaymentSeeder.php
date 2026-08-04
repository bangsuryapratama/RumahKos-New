<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $residents = Resident::with(['room', 'user'])->where('status', 'active')->get();

        foreach ($residents as $resident) {
            $roomPrice = $resident->room ? $resident->room->price : 2000000;
            $startDate = Carbon::parse($resident->start_date);
            $now = Carbon::now();

            // 1. Generate paid records for past months up to last month
            $currentMonth = $startDate->copy();
            while ($currentMonth->lessThan($now->copy()->startOfMonth())) {
                $billingDate = $currentMonth->copy()->startOfMonth();
                $dueDate = $currentMonth->copy()->day(10);
                $paidDate = $currentMonth->copy()->day(rand(2, 8))->setHour(rand(8, 20))->setMinute(rand(10, 50));
                $orderId = 'ORDER-' . $resident->id . '-' . $billingDate->format('Ym');

                Payment::updateOrCreate(
                    ['order_id' => $orderId],
                    [
                        'resident_id'    => $resident->id,
                        'amount'         => $roomPrice,
                        'billing_month'  => $billingDate->toDateString(),
                        'due_date'       => $dueDate->toDateString(),
                        'method'         => 'midtrans',
                        'status'         => 'paid',
                        'snap_token'     => 'sample-snap-' . Str::random(24),
                        'transaction_id' => 'MIDTRANS-TRX-' . strtoupper(Str::random(12)),
                        'paid_at'        => $paidDate,
                        'description'    => 'Pembayaran Sewa Kamar ' . ($resident->room?->name ?? '') . ' - Periode ' . $billingDate->translatedFormat('F Y'),
                    ]
                );

                $currentMonth->addMonth();
            }

            // 2. Current month payment (some paid, some pending)
            $currentBillingDate = $now->copy()->startOfMonth();
            $currentDueDate = $now->copy()->day(10);
            $currentOrderId = 'ORDER-' . $resident->id . '-' . $currentBillingDate->format('Ym');

            $isCurrentPaid = in_array($resident->user?->name, ['Budi Santoso', 'Siti Rahmawati', 'Dimas Pratama']);

            Payment::updateOrCreate(
                ['order_id' => $currentOrderId],
                [
                    'resident_id'    => $resident->id,
                    'amount'         => $roomPrice,
                    'billing_month'  => $currentBillingDate->toDateString(),
                    'due_date'       => $currentDueDate->toDateString(),
                    'method'         => 'midtrans',
                    'status'         => $isCurrentPaid ? 'paid' : 'pending',
                    'snap_token'     => 'sample-snap-' . Str::random(24),
                    'transaction_id' => $isCurrentPaid ? ('MIDTRANS-TRX-' . strtoupper(Str::random(12))) : null,
                    'paid_at'        => $isCurrentPaid ? $now->copy()->subDays(2) : null,
                    'description'    => 'Tagihan Sewa Kamar ' . ($resident->room?->name ?? '') . ' - Periode ' . $currentBillingDate->translatedFormat('F Y'),
                ]
            );
        }
    }
}
