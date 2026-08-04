<?php

namespace App\Services;

use App\Models\User;
use App\Models\Room;
use App\Models\Resident;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TenantService
{
    /**
     * Create a new tenant with profile, optional documents, resident contract, and payments.
     */
    public function createTenant(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // 1. Create User
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id'  => User::ROLE_PENGHUNI,
            ]);

            // 2. Handle Document Uploads
            $profileData = [
                'phone'                  => $data['phone'] ?? null,
                'identity_number'        => $data['identity_number'] ?? null,
                'address'                => $data['address'] ?? null,
                'date_of_birth'          => $data['date_of_birth'] ?? null,
                'gender'                 => $data['gender'] ?? null,
                'occupation'             => $data['occupation'] ?? null,
                'emergency_contact'      => $data['emergency_contact'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            ];

            if (isset($data['ktp_photo'])) {
                $profileData['ktp_photo'] = $data['ktp_photo']->store('documents/ktp', 'public');
            }

            if (isset($data['sim_photo'])) {
                $profileData['sim_photo'] = $data['sim_photo']->store('documents/sim', 'public');
            }

            $user->profile()->create($profileData);

            // 3. Handle Room Booking / Resident contract if room provided
            if (!empty($data['room_id']) && !empty($data['start_date'])) {
                $startDate = Carbon::parse($data['start_date']);
                $duration = (int) ($data['duration_months'] ?? 1);
                $endDate = (clone $startDate)->addMonths($duration)->subDay();

                $room = Room::findOrFail($data['room_id']);

                $resident = $user->residents()->create([
                    'room_id'    => $room->id,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'status'     => 'active',
                ]);

                // Update room status
                $room->update(['status' => 'occupied']);

                // Generate Monthly Payments
                for ($i = 0; $i < $duration; $i++) {
                    $billingDate = (clone $startDate)->addMonths($i);
                    $dueDate = (clone $billingDate)->addDays(7);

                    $resident->payments()->create([
                        'amount'        => $room->price,
                        'billing_month' => $billingDate->format('Y-m-01'),
                        'due_date'      => $dueDate,
                        'status'        => 'pending',
                        'method'        => 'midtrans',
                        'description'   => "Sewa Bulan ke-" . ($i + 1) . " ({$billingDate->translatedFormat('F Y')})",
                    ]);
                }
            }

            return $user;
        });
    }
}
