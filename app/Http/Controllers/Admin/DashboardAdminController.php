<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // ── Stats Utama ──────────────────────────────────────────────
        $roomStats = Room::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
            SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied
        ")->first();
        $totalRooms     = $roomStats->total;
        $availableRooms = $roomStats->available;
        $occupiedRooms  = $roomStats->occupied;

        // Pendapatan: hanya dari payment yang sudah PAID
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        // Pembayaran tertunda (pending)
        $pendingStats = Payment::where('status', 'pending')
            ->selectRaw('COALESCE(SUM(amount), 0) as total, COUNT(*) as count')
            ->first();
        $pendingRevenue = $pendingStats->total;
        $pendingCount   = $pendingStats->count;

        // ── Penghuni Aktif ────────────────────────────────────────────
        $activeTenants = Resident::where('status', 'active')->count();

        // ── Pendapatan Bulanan (6 bulan terakhir) ─────────────────────
        $monthlyRevenue = Payment::where('status', 'paid')
            ->where('paid_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'month' => Carbon::createFromFormat('Y-m', $row->month)->translatedFormat('M Y'),
                'total' => (float) $row->total,
            ]);

        // ── Pembayaran Jatuh Tempo (7 hari ke depan) ──────────────────
        $upcomingDue = Payment::with(['resident.user', 'resident.room'])
            ->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // ── Aktivitas Terbaru (booking baru + pembayaran lunas) ────────
        $recentPayments = Payment::with(['resident.user', 'resident.room'])
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'icon'  => 'wallet',
                'color' => 'green',
                'title' => 'Pembayaran lunas – ' . ($p->resident->room->name ?? '-'),
                'sub'   => $p->resident->user->name ?? '-',
                'time'  => $p->paid_at?->diffForHumans() ?? '-',
            ]);

        $recentBookings = Resident::with(['user', 'room'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn($r) => [
                'icon'  => 'calendar',
                'color' => 'blue',
                'title' => 'Booking baru – ' . ($r->room->name ?? '-'),
                'sub'   => $r->user->name ?? '-',
                'time'  => $r->created_at?->diffForHumans() ?? '-',
            ]);

        $recentActivities = $recentPayments
            ->concat($recentBookings)
            ->sortByDesc('time')
            ->values()
            ->take(8);

        // ── Occupancy Rate ─────────────────────────────────────────────
        $occupancyRate = $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100, 1)
            : 0;

        return view('admin.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'totalRevenue',
            'pendingRevenue',
            'pendingCount',
            'activeTenants',
            'monthlyRevenue',
            'upcomingDue',
            'recentActivities',
            'occupancyRate',
        ));
    }
}