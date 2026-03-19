<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalInvoices = $user->invoices()->count();
        $totalRevenue = $user->invoices()->where('status', 'paid')->sum('total');
        $totalDraft = $user->invoices()->where('status', 'draft')->count();
        $totalCustomers = $user->customers()->count();

        $recentInvoices = $user->invoices()
            ->with('customer')
            ->latest()
            ->take(5)
            ->get();

        $topCustomers = $user->customers()
            ->withSum('invoices as total_billed', 'total')
            ->orderByDesc('total_billed')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'totalInvoices',
            'totalRevenue',
            'totalDraft',
            'totalCustomers',
            'recentInvoices',
            'topCustomers'
        ));
    }
}