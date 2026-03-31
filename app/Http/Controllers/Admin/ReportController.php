<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // ─── Shared filter builder ───────────────────────────────────────────────

    private function tenantQuery(Request $request)
    {
        $query = Resident::with(['user.profile', 'room.property', 'payments']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('property_id')) {
            $query->whereHas('room', fn($q) =>
                $q->where('property_id', $request->property_id)
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        return $query->latest('start_date');
    }

    private function financeQuery(Request $request)
    {
        $query = Payment::with(['resident.user', 'resident.room.property']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('resident.user', fn($q) =>
                $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('property_id')) {
            $query->whereHas('resident.room', fn($q) =>
                $q->where('property_id', $request->property_id)
            );
        }

        if ($request->filled('month')) {
            $m = Carbon::parse($request->month);
            $query->whereMonth('billing_month', $m->month)->whereYear('billing_month', $m->year);
        }

        if ($request->filled('paid_from')) {
            $query->whereDate('paid_at', '>=', $request->paid_from);
        }

        if ($request->filled('paid_to')) {
            $query->whereDate('paid_at', '<=', $request->paid_to);
        }

        return $query->latest('billing_month');
    }

    // ─── Laporan Penghuni ────────────────────────────────────────────────────

    public function tenants(Request $request)
    {
        $residents = $this->tenantQuery($request)->paginate(15)->withQueryString();

        $stats = [
            'total'     => Resident::count(),
            'active'    => Resident::where('status', 'active')->count(),
            'inactive'  => Resident::where('status', 'inactive')->count(),
            'expired'   => Resident::where('status', 'expired')->count(),
            'cancelled' => Resident::where('status', 'cancelled')->count(),
        ];

        $properties = \App\Models\Property::orderBy('name')->get();

        return view('admin.reports.tenants', compact('residents', 'stats', 'properties'));
    }

    public function tenantsPdf(Request $request)
    {
        $residents  = $this->tenantQuery($request)->get();
        $properties = \App\Models\Property::orderBy('name')->get();
        $generatedAt = now()->format('d M Y H:i');

        $pdf = Pdf::loadView('admin.reports.pdf.tenants', compact('residents', 'generatedAt'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penghuni-' . now()->format('Ymd') . '.pdf');
    }

    public function tenantsExcel(Request $request)
    {
        $residents = $this->tenantQuery($request)->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-penghuni-' . now()->format('Ymd') . '.csv"',
        ];

        $callback = function () use ($residents) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($f, ['No', 'Nama', 'Email', 'No. HP', 'Kamar', 'Properti',
                         'Tgl Masuk', 'Tgl Keluar', 'Durasi (bln)', 'Pembayaran Lunas', 'Status']);

            foreach ($residents as $i => $r) {
                $paid     = $r->payments->where('status', 'paid')->count();
                $total    = $r->payments->count();
                $duration = Carbon::parse($r->start_date)->diffInMonths(Carbon::parse($r->end_date));

                fputcsv($f, [
                    $i + 1,
                    $r->user->name,
                    $r->user->email,
                    $r->user->profile->phone ?? '-',
                    $r->room->name ?? '-',
                    $r->room->property->name ?? '-',
                    Carbon::parse($r->start_date)->format('d/m/Y'),
                    Carbon::parse($r->end_date)->format('d/m/Y'),
                    $duration,
                    "$paid/$total",
                    ucfirst($r->status),
                ]);
            }

            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Laporan Keuangan ────────────────────────────────────────────────────

    public function finance(Request $request)
    {
        $payments   = $this->financeQuery($request)->paginate(15)->withQueryString();
        $properties = \App\Models\Property::orderBy('name')->get();

        // Stats dengan filter yang sama
        $baseQuery = clone $this->financeQuery($request);
        $allData   = $this->financeQuery($request)->get();

        $stats = [
            'total_tagihan' => $allData->sum('amount'),
            'total_lunas'   => $allData->where('status', 'paid')->sum('amount'),
            'total_pending' => $allData->where('status', 'pending')->count(),
            'total_failed'  => $allData->whereIn('status', ['failed', 'cancelled'])->count(),
        ];

        // Chart revenue 12 bulan terakhir
        $revenueChart = Payment::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('admin.reports.finance', compact('payments', 'stats', 'revenueChart', 'properties'));
    }

    public function financePdf(Request $request)
    {
        $payments    = $this->financeQuery($request)->get();
        $generatedAt = now()->format('d M Y H:i');

        $stats = [
            'total_tagihan' => $payments->sum('amount'),
            'total_lunas'   => $payments->where('status', 'paid')->sum('amount'),
            'total_pending' => $payments->where('status', 'pending')->count(),
            'total_failed'  => $payments->whereIn('status', ['failed', 'cancelled'])->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.finance', compact('payments', 'stats', 'generatedAt'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-keuangan-' . now()->format('Ymd') . '.pdf');
    }

    public function financeExcel(Request $request)
    {
        $payments = $this->financeQuery($request)->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-keuangan-' . now()->format('Ymd') . '.csv"',
        ];

        $callback = function () use ($payments) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($f, ['No', 'Penghuni', 'Email', 'Kamar', 'Properti',
                         'Deskripsi', 'Bulan Tagih', 'Jatuh Tempo', 'Nominal', 'Tgl Bayar', 'Status']);

            foreach ($payments as $i => $p) {
                fputcsv($f, [
                    $i + 1,
                    $p->resident->user->name,
                    $p->resident->user->email,
                    $p->resident->room->name ?? '-',
                    $p->resident->room->property->name ?? '-',
                    $p->description ?? '-',
                    Carbon::parse($p->billing_month)->format('m/Y'),
                    $p->due_date ? Carbon::parse($p->due_date)->format('d/m/Y') : '-',
                    $p->amount,
                    $p->paid_at ? Carbon::parse($p->paid_at)->format('d/m/Y') : '-',
                    ucfirst($p->status),
                ]);
            }

            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}