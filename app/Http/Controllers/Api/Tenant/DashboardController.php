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
                'success' => false,
                'message' => 'Tidak ada data penghuni'
            ], 404);
        }

        $payments = $resident->payments;

        $overdue = $payments->filter(fn ($p) =>
            $p->status === 'pending' &&
            $p->due_date &&
            $p->due_date < now()
        );

        $unpaid = $payments->where('status', 'pending');
        $latest = $payments->first();

        return response()->json([
            'success' => true,
            'data' => [

                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'room' => [
                    'name' => $resident->room?->name ?? '-',
                    'status' => $resident->status,
                    'property' => $resident->room?->property?->name ?? '-',
                    'price' => $resident->room?->price ?? 0,
                ],

                'billing' => [
                    'amount' => $latest?->amount ?? 0,
                    'status' => $latest?->status ?? 'none',
                    'due_date' => $latest?->due_date,
                    'unpaid_count' => $unpaid->count(),
                    'overdue_count' => $overdue->count(),
                    'total_overdue' => $overdue->sum('amount'),
                ],

                'stats' => [
                    'duration' => $resident->getDurationInMonths(),
                    'start_date' => $resident->start_date,
                ],

                'payments' => $payments->take(10)->map(fn ($p) => [
                    'id' => $p->id,
                    'month' => $p->billing_month,
                    'amount' => $p->amount,
                    'status' => $p->status,
                    'due_date' => $p->due_date,
                    'is_overdue' =>
                        $p->status === 'pending' &&
                        $p->due_date &&
                        $p->due_date < now(),
                ])->values(),
            ]
        ]);
    }
}