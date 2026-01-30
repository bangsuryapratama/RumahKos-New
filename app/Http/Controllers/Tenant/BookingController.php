<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();

        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);

        $address = Property::select('address')->first();
        view()->share('address', $address);

        $residents = $user->residents()
            ->with(['room.property', 'payments' => function($q) {
                $q->orderBy('billing_month', 'desc');
            }])
            ->latest()
            ->get();

        return view('tenant.bookings.index', compact('user', 'residents', 'address', 'contact'));
    }

    public function create(Room $room)
    {
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);

        $address = Property::select('address')->first();
        view()->share('address', $address);

        if ($room->status !== 'available') {
            return redirect()->back()
                ->with('error', 'Kamar tidak tersedia.');
        }

        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        $activeResident = $user->resident;

        if ($activeResident) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'Anda sudah memiliki kamar aktif. Silakan selesaikan kontrak terlebih dahulu.');
        }

        // Check if profile is complete
        $profile = $user->profile;
        $isProfileComplete = $profile
            && $profile->phone
            && $profile->identity_number
            && $profile->ktp_photo;

        if (!$isProfileComplete) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'Mohon lengkapi data profil Anda terlebih dahulu (No. Telepon, No. KTP, dan Upload KTP).');
        }

        return view('tenant.bookings.create', compact('room', 'address', 'contact'));
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:12',
            'agree_terms' => 'accepted',
        ]);

        /** @var User $user */
        $user = Auth::guard('tenant')->user();

        $durationMonths = (int) $validated['duration_months'];

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($durationMonths);

        DB::beginTransaction();
        try {
            // Create resident
            $resident = Resident::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'inactive', // Will be 'active' after first payment
            ]);

            // Create payments untuk setiap bulan
            for ($i = 0; $i < $durationMonths; $i++) {
                $billingMonth = $startDate->copy()->addMonths($i);
                $dueDate = $billingMonth->copy()->addDays(3); // Jatuh tempo 3 hari setelah tanggal

                $payment = $resident->payments()->create([
                    'amount' => $room->price,
                    'billing_month' => $billingMonth->startOfMonth(),
                    'due_date' => $dueDate,
                    'method' => 'midtrans',
                    'status' => 'pending',
                    'description' => "Sewa {$room->name} - Bulan ke-" . ($i + 1) . " ({$billingMonth->format('F Y')})",
                ]);

                // Hanya pembayaran bulan pertama yang akan langsung di-redirect ke Midtrans
                if ($i === 0) {
                    $firstPayment = $payment;
                }
            }

            DB::commit();

            // Redirect ke pembayaran bulan pertama
            return redirect()->route('tenant.payment.midtrans', $firstPayment->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Resident $resident)
    {
        // Pastikan resident milik user yang sedang login
        /** @var User $user */
        $user = Auth::guard('tenant')->user();

        if ($resident->user_id !== $user->id) {
            return redirect()->route('tenant.bookings.index')
                ->with('error', 'Anda tidak memiliki akses untuk membatalkan booking ini.');
        }

        // Hanya bisa cancel jika status masih inactive (belum ada pembayaran)
        if ($resident->status !== 'inactive') {
            return redirect()->route('tenant.bookings.index')
                ->with('error', 'Booking tidak dapat dibatalkan. Silakan hubungi admin.');
        }

        // Cek apakah ada pembayaran yang sudah lunas
        $hasPaidPayment = $resident->payments()->where('status', 'paid')->exists();

        if ($hasPaidPayment) {
            return redirect()->route('tenant.bookings.index')
                ->with('error', 'Booking tidak dapat dibatalkan karena sudah ada pembayaran yang lunas.');
        }

        DB::beginTransaction();
        try {
            // Update status resident menjadi cancelled
            $resident->update([
                'status' => 'cancelled'
            ]);

            // Cancel semua pending payments
            $resident->payments()
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled'
                ]);

            // Update room status kembali ke available jika tidak ada resident aktif lainnya
            $room = $resident->room;
            $hasActiveResident = $room->residents()
                ->where('status', 'active')
                ->exists();

            if (!$hasActiveResident) {
                $room->update([
                    'status' => 'available'
                ]);
            }

            DB::commit();

            return redirect()->route('tenant.bookings.index')
                ->with('success', 'Booking berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tenant.bookings.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
