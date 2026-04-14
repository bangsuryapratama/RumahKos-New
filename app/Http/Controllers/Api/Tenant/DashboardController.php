<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function index()
{
    $user = Auth::guard('sanctum')->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $resident = $user->resident()
        ->with(['room.property', 'payments'])
        ->first();

    $payments = $resident?->payments ?? collect();

    $pending = $payments->where('status', 'pending');

    $overdue = $pending->filter(fn ($p) =>
        $p->due_date && $p->due_date < now()
    );

    return response()->json([
        'success' => true,
        'data' => [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],

            'room' => $resident?->room ? [
                'name' => $resident->room->name,
                'property' => $resident->room->property->name,
            ] : null,

            'billing' => [
                'pending_count' => $pending->count(),
                'overdue_count' => $overdue->count(),
                'total_overdue' => $overdue->sum('amount'),
            ],

            'payments' => $payments->take(10)->map(fn ($p) => [
                'id' => $p->id,
                'month' => $p->billing_month,
                'amount' => $p->amount,
                'due_date' => $p->due_date,
                'status' => $p->status,
                'is_overdue' => $p->status === 'pending' &&
                    $p->due_date && $p->due_date < now(),
            ])->values(),
        ]
    ]);
}
}