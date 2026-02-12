<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentApiController extends Controller
{
    /**
     * Get current tenant's residence information
     */
    public function getCurrentResidence()
    {
        try {
            $user = Auth::user();

            // Get active resident with room, property, facilities, and payments
            $resident = $user->residents()
                ->with([
                    'room' => function($query) {
                        $query->with(['property', 'facilities']);
                    },
                    'payments' => function($query) {
                        $query->orderBy('billing_month', 'desc')->limit(5);
                    }
                ])
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            if (!$resident) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum terdaftar sebagai penghuni',
                    'data' => null
                ], 200);
            }

            // Calculate statistics
            $unpaidCount = $resident->payments()->where('status', 'pending')->count();
            $durationMonths = $resident->getDurationInMonths();

            return response()->json([
                'success' => true,
                'message' => 'Data residence berhasil diambil',
                'data' => [
                    'resident' => [
                        'id' => $resident->id,
                        'start_date' => $resident->start_date->format('Y-m-d'),
                        'end_date' => $resident->end_date?->format('Y-m-d'),
                        'status' => $resident->status,
                        'duration_months' => $durationMonths,
                        'is_contract_active' => $resident->isContractActive(),
                    ],
                    'room' => [
                        'id' => $resident->room->id,
                        'name' => $resident->room->name,
                        'floor' => $resident->room->floor,
                        'size' => $resident->room->size,
                        'price' => $resident->room->price,
                        'billing_cycle' => $resident->room->billing_cycle,
                        'image' => $resident->room->image
                            ? asset('storage/' . $resident->room->image)
                            : null,
                        'status' => $resident->room->status,
                    ],
                    'property' => [
                        'id' => $resident->room->property->id,
                        'name' => $resident->room->property->name,
                        'address' => $resident->room->property->address,
                        'description' => $resident->room->property->description,
                    ],
                    'facilities' => $resident->room->facilities->map(function($facility) {
                        return [
                            'id' => $facility->id,
                            'name' => $facility->name,
                            'icon' => $facility->icon,
                        ];
                    }),
                    'statistics' => [
                        'unpaid_payments' => $unpaidCount,
                        'duration_months' => $durationMonths,
                        'total_payments' => $resident->payments->count(),
                    ],
                    'recent_payments' => $resident->payments->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'billing_month' => $payment->billing_month->format('Y-m-d'),
                            'due_date' => $payment->due_date->format('Y-m-d'),
                            'amount' => $payment->amount,
                            'status' => $payment->status,
                            'paid_at' => $payment->paid_at?->format('Y-m-d H:i:s'),
                        ];
                    }),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data residence',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get residence history
     */
    public function getResidenceHistory()
    {
        try {
            $user = Auth::user();

            $residents = $user->residents()
                ->with(['room.property'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat residence berhasil diambil',
                'data' => $residents->map(function($resident) {
                    return [
                        'id' => $resident->id,
                        'room_name' => $resident->room->name,
                        'property_name' => $resident->room->property->name,
                        'start_date' => $resident->start_date->format('Y-m-d'),
                        'end_date' => $resident->end_date?->format('Y-m-d'),
                        'status' => $resident->status,
                        'duration_months' => $resident->getDurationInMonths(),
                    ];
                }),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat residence',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
