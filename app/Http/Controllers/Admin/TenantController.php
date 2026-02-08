<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants
     */
    public function index(Request $request)
    {
        $query = User::with(['resident.room.property', 'residents', 'profile'])
            ->where('role_id', 2); // 2 = tenant

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('phone', 'like', "%{$search}%")
                         ->orWhere('identity_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('resident', function($q) {
                    $q->where('status', 'active');
                });
            } elseif ($request->status === 'inactive') {
                $query->whereHas('residents', function($q) {
                    $q->where('status', 'inactive');
                })->orWhereDoesntHave('residents');
            }
        }

        $tenants = $query->latest()->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant
     */
    public function create()
    {
        $rooms = Room::where('status', 'available')
            ->with('property')
            ->get();

        return view('admin.tenants.create', compact('rooms'));
    }

    /**
     * Store a newly created tenant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'identity_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'occupation' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:100',

            // Document uploads
            'ktp_photo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'sim_photo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'passport_photo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',

            // Booking info (optional)
            'room_id' => 'nullable|exists:rooms,id',
            'start_date' => 'nullable|required_with:room_id|date',
            'duration_months' => 'nullable|required_with:room_id|integer|min:1|max:12',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 2, // 2 = tenant
            ]);

            // Prepare profile data
            $profileData = [
                'phone' => $validated['phone'] ?? null,
                'identity_number' => $validated['identity_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            ];

            // Handle document uploads
            $documents = ['ktp_photo', 'sim_photo', 'passport_photo'];
            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);
                    if ($file->isValid()) {
                        $path = $file->store('documents/profiles', 'public');
                        $profileData[$doc] = $path;
                    }
                }
            }

            // Create profile
            $user->profile()->create($profileData);

            // Create booking if room is selected
            if ($request->filled('room_id')) {
                $room = Room::findOrFail($validated['room_id']);
                $startDate = \Carbon\Carbon::parse($validated['start_date']);
                $endDate = $startDate->copy()->addMonths($validated['duration_months']);

                $resident = Resident::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'inactive', // Will be active after payment
                ]);

                // Create payments
                for ($i = 0; $i < $validated['duration_months']; $i++) {
                    $billingMonth = $startDate->copy()->addMonths($i);
                    $dueDate = $billingMonth->copy()->addDays(3);

                    $resident->payments()->create([
                        'amount' => $room->price,
                        'billing_month' => $billingMonth->startOfMonth(),
                        'due_date' => $dueDate,
                        'method' => 'midtrans',
                        'status' => 'pending',
                        'description' => "Sewa {$room->name} - Bulan ke-" . ($i + 1) . " ({$billingMonth->format('F Y')})",
                    ]);
                }

                // Update room status
                $room->update(['status' => 'occupied']);
            }

            DB::commit();

            return redirect()->route('admin.tenants.show', $user)
                ->with('success', 'Penghuni berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified tenant
     */
    public function show(User $tenant)
    {
        if ($tenant->role_id !== 2) { // 2 = tenant
            abort(404);
        }

        $tenant->load([
            'profile',
            'residents.room.property',
            'residents.payments' => function($q) {
                $q->orderBy('billing_month', 'desc');
            }
        ]);

        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant
     */
    public function edit(User $tenant)
    {
        if ($tenant->role_id !== 2) { // 2 = tenant
            abort(404);
        }

        $tenant->load('profile', 'resident.room');

        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant
     */
    public function update(Request $request, User $tenant)
    {
        if ($tenant->role_id !== 2) { // 2 = tenant
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($tenant->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'identity_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'occupation' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:100',
            
            // Document uploads
            'ktp_photo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'sim_photo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'passport_photo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            
            // Delete flags
            'delete_ktp' => 'nullable|boolean',
            'delete_sim' => 'nullable|boolean',
            'delete_passport' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $tenant->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $tenant->update([
                    'password' => Hash::make($validated['password'])
                ]);
            }

            // Prepare profile data
            $profileData = [
                'phone' => $validated['phone'] ?? null,
                'identity_number' => $validated['identity_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            ];

            // Get or create profile
            $profile = $tenant->profile;
            if (!$profile) {
                $profileData['user_id'] = $tenant->id;
                $profile = $tenant->profile()->create($profileData);
            }

            // Handle file deletions
            $deleteFlags = [
                'delete_ktp' => 'ktp_photo',
                'delete_sim' => 'sim_photo',
                'delete_passport' => 'passport_photo',
            ];

            foreach ($deleteFlags as $flag => $field) {
                if ($request->has($flag) && $request->$flag == '1') {
                    if ($profile->$field) {
                        Storage::disk('public')->delete($profile->$field);
                        $profileData[$field] = null;
                    }
                }
            }

            // Handle file uploads
            $documents = ['ktp_photo', 'sim_photo', 'passport_photo'];

            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);

                    if ($file->isValid()) {
                        // Delete old file if exists
                        $deleteFlag = 'delete_' . str_replace('_photo', '', $doc);
                        if ($profile->$doc && !($request->has($deleteFlag) && $request->$deleteFlag == '1')) {
                            Storage::disk('public')->delete($profile->$doc);
                        }

                        // Store new file
                        $path = $file->store('documents/profiles', 'public');
                        $profileData[$doc] = $path;
                    }
                }
            }

            // Update profile
            $profile->update($profileData);

            DB::commit();

            return redirect()->route('admin.tenants.show', $tenant)
                ->with('success', 'Data penghuni berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified tenant
     */
    public function destroy(User $tenant)
    {
        if ($tenant->role_id !== 2) { // 2 = tenant
            abort(404);
        }

        // Check if tenant has active booking
        $activeResident = $tenant->residents()
            ->where('status', 'active')
            ->exists();

        if ($activeResident) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus penghuni dengan status aktif. Silakan nonaktifkan terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            // Delete documents
            if ($tenant->profile) {
                $documents = ['ktp_photo', 'sim_photo', 'passport_photo'];
                foreach ($documents as $doc) {
                    if ($tenant->profile->$doc) {
                        Storage::disk('public')->delete($tenant->profile->$doc);
                    }
                }
                
                // Delete profile
                $tenant->profile()->delete();
            }

            // Delete residents and related payments
            $tenant->residents()->each(function($resident) {
                $resident->payments()->delete();
                $resident->delete();
            });

            // Delete user
            $tenant->delete();

            DB::commit();

            return redirect()->route('admin.tenants.index')
                ->with('success', 'Penghuni berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Activate tenant's booking
     */
    public function activate(Resident $resident)
    {
        if ($resident->status === 'active') {
            return redirect()->back()
                ->with('error', 'Status penghuni sudah aktif.');
        }

        DB::beginTransaction();
        try {
            $resident->update(['status' => 'active']);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status penghuni berhasil diaktifkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate tenant's booking
     */
    public function deactivate(Resident $resident)
    {
        if ($resident->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Status penghuni tidak aktif.');
        }

        DB::beginTransaction();
        try {
            $resident->update(['status' => 'expired']);

            // Update room to available if no other active residents
            $room = $resident->room;
            $hasActiveResident = $room->residents()
                ->where('status', 'active')
                ->exists();

            if (!$hasActiveResident) {
                $room->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status penghuni berhasil dinonaktifkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}