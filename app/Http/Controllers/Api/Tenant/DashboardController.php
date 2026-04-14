<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('sanctum')->user();

        $resident = $user->resident;

        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data penghuni'
            ], 404);
        }

        $room = $resident->room;

        // Ambil payment terakhir
        $payment = $resident->payments()
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                
                // 👤 USER
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                // 🏠 ROOM
                'room' => [
                    'name' => $room->name ?? '-',
                    'status' => $resident->status,
                ],

                // 💰 BILLING
                'billing' => [
                    'amount' => $payment?->amount ?? 0,
                    'status' => $payment?->status ?? 'none',
                    'due_date' => $payment?->due_date,
                ],

            ]
        ]);
    }
}