<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('sanctum')->user();

        $resident = $user->resident()->with([
            'room.property',
            'payments' => fn ($q) => $q->orderBy('billing_month', 'desc')
        ])->first();

        if (!$resident) {
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'resident' => null,
                    'room' => null,
                    'billing' => null,
                    'payments' => [],
                ]
            ]);
        }

        $payments = $resident->payments;

        $pending = $payments->where('status', 'pending');

        $overdue = $pending->filter(fn ($p) =>
            $p->due_date && $p->due_date < now()
        );

        $latest = $payments->first();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'resident' => [
                    'status' => $resident->status,
                    'start_date' => $resident->start_date,
                ],

                'room' => $resident->room ? [
                    'name' => $resident->room->name,
                    'property' => $resident->room->property->name ?? '-',
                    'price' => $resident->room->price,
                ] : null,

                'billing' => [
                    'latest_amount' => $latest?->amount ?? 0,
                    'pending_count' => $pending->count(),
                    'overdue_count' => $overdue->count(),
                    'total_overdue' => $overdue->sum('amount'),
                ],

                'payments' => $pending->values()->map(fn ($p) => [
                    'id' => $p->id,
                    'month' => $p->billing_month,
                    'amount' => $p->amount,
                    'due_date' => $p->due_date,
                    'is_overdue' => $p->due_date && $p->due_date < now(),
                ]),
            ]
        ]);
    }
}