<?php
// app/Http/Controllers/PublicInvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicInvoiceController extends Controller
{
    // عرض الصفحة العامة
    public function show(string $token)
    {
        $invoice = Invoice::where('public_token', $token)
            ->with(['customer', 'items', 'user.setting'])
            ->firstOrFail();

        $setting = $invoice->user->setting
            ?? new \App\Models\Setting([
                'invoice_color' => '#212529',
                'invoice_prefix' => 'INV',
            ]);

        return view('invoices.public', compact('invoice', 'setting'));
    }

    // تحميل PDF من الصفحة العامة
    public function downloadPdf(string $token)
    {
        $invoice = Invoice::where('public_token', $token)
            ->with(['customer', 'items', 'user.setting'])
            ->firstOrFail();

        $setting = $invoice->user->setting
            ?? new \App\Models\Setting([
                'invoice_color' => '#212529',
                'invoice_prefix' => 'INV',
            ]);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'setting'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}