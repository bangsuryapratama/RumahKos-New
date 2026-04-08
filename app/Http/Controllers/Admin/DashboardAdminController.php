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
        $totalRooms     = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms  = Room::where('status', 'occupied')->count();

        // Pendapatan: hanya dari payment yang sudah PAID
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        // Pembayaran tertunda (pending)
        $pendingRevenue = Payment::where('status', 'pending')->sum('amount');
        $pendingCount   = Payment::where('status', 'pending')->count();

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