<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Exports\InvoicesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $year = $request->get('year', now()->year);

        // ── إحصائيات عامة ──────────────────────────────
        $totalRevenue = $user->invoices()->where('status', 'paid')->sum('total');
        $totalInvoices = $user->invoices()->count();
        $totalPaid = $user->invoices()->where('status', 'paid')->count();
        $totalDraft = $user->invoices()->where('status', 'draft')->count();
        $totalCustomers = $user->customers()->count();
        $avgInvoiceValue = $totalInvoices > 0
            ? round($totalRevenue / max($totalPaid, 1), 2)
            : 0;

        // ── إيرادات شهرية (للسنة المختارة) ────────────
        $monthlyRevenue = $user->invoices()
            ->selectRaw('MONTH(issue_date) as month, SUM(total) as revenue, COUNT(*) as count')
            ->where('status', 'paid')
            ->whereYear('issue_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // حوّل لمصفوفة 12 شهر (0 للشهور الفارغة)
        $monthlyData = collect(range(1, 12))->map(fn($m) => [
            'month' => now()->month($m)->format('M'),
            'revenue' => $monthlyRevenue->get($m)?->revenue ?? 0,
            'count' => $monthlyRevenue->get($m)?->count ?? 0,
        ]);

        // ── أفضل العملاء ───────────────────────────────
        $topCustomers = $user->customers()
            ->withCount('invoices')
            ->withSum([
                'invoices as total_billed' => fn($q) =>
                    $q->where('status', 'paid')
            ], 'total')
            ->orderByDesc('total_billed')
            ->take(5)
            ->get();

        // ── توزيع الحالات (Pie Chart) ──────────────────
        $statusData = [
            'paid' => $totalPaid,
            'draft' => $totalDraft,
        ];

        // ── آخر 30 يوم ─────────────────────────────────
        $last30Days = $user->invoices()
            ->selectRaw('DATE(issue_date) as date, SUM(total) as revenue')
            ->where('status', 'paid')
            ->where('issue_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ── السنوات المتاحة للفلتر ──────────────────────
        $availableYears = $user->invoices()
            ->selectRaw('YEAR(issue_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('reports.index', compact(
            'totalRevenue',
            'totalInvoices',
            'totalPaid',
            'totalDraft',
            'totalCustomers',
            'avgInvoiceValue',
            'monthlyData',
            'topCustomers',
            'statusData',
            'last30Days',
            'availableYears',
            'year'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->get('year', now()->year);
        $status = $request->get('status', 'all');

        return Excel::download(
            new InvoicesExport(auth()->id(), $year, $status),
            "invoices-report-{$year}.xlsx"
        );
    }
}