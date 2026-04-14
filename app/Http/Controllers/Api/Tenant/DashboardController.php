<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('sanctum')->user();

        $resident = $user->resident()
            ->with(['room.property', 'payments'])
            ->first();

        if (!$resident) {
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => ['name' => $user->name],
                    'room' => null,
                    'billing' => [
                        'pending_count' => 0,
                        'overdue_count' => 0,
                        'total_overdue' => 0,
                    ],
                    'payments' => [],
                ]
            ]);
        }

        $payments = $resident->payments;


        $pending = $payments->where('status', 'pending');

        $overdue = $pending->filter(fn($p) =>
            $p->due_date && $p->due_date < now()
        );

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'room' => [
                    'name' => $resident->room?->name,
                    'property' => $resident->room?->property?->name,
                ],

                'billing' => [
                    'pending_count' => $pending->count(),
                    'overdue_count' => $overdue->count(),
                    'total_overdue' => $overdue->sum('amount'),
                ],

                'payments' => $pending->values()->map(fn($p) => [
                    'id' => $p->id,
                    'month' => $p->billing_month,
                    'amount' => $p->amount,
                    'due_date' => $p->due_date,
                    'status' => $p->status,
                ]),
            ]
        ]);
    }
}