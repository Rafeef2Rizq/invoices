<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Http\Requests\InvoiceRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = auth()->user()
            ->invoices()
            ->with('customer')
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = auth()->user()->customers()->orderBy('name')->get();

        return view('invoices.create', compact('customers'));
    }

    public function store(InvoiceRequest $request)
    {
        $data = $request->validated();

        // Calculate total from items
        $total = collect($data['items'])->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        // Create invoice
        $invoice = auth()->user()->invoices()->create([
            'customer_id' => $data['customer_id'],
            'status' => $data['status'],
            'issue_date' => $data['issue_date'],
            'due_date' => $data['due_date'] ?? null,
            'notes' => $data['notes'] ?? null,
            'total' => $total,
        ]);

        // Create items
        foreach ($data['items'] as $item) {
            $invoice->items()->create([
                'user_id' => auth()->id(),
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeOwnership($invoice);

        $invoice->load(['customer', 'items']);

        return view('invoices.show', compact('invoice'));
    }

    public function updateStatus(Invoice $invoice)
    {
        $this->authorizeOwnership($invoice);

        $invoice->update([
            'status' => $invoice->isPaid() ? 'draft' : 'paid',
        ]);

        return back()->with('success', 'Invoice status updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorizeOwnership($invoice);

        $invoice->delete(); // items cascade automatically

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $this->authorizeOwnership($invoice);

        $invoice->load(['customer', 'items', 'user']);

        // جلب إعدادات المستخدم
        $setting = $invoice->user->setting
            ?? new \App\Models\Setting([
                'invoice_color' => '#212529',
                'invoice_prefix' => 'INV',
            ]);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'setting'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    private function authorizeOwnership(Invoice $invoice): void
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
    }
    // تفعيل/تعطيل الرابط العام
    public function togglePublic(Invoice $invoice)
    {
        $this->authorizeOwnership($invoice);

        if ($invoice->isPublic()) {
            // تعطيل — حذف الـ token
            $invoice->update(['public_token' => null]);
            return back()->with('success', 'Public link disabled.');
        }

        // تفعيل — توليد token جديد
        $invoice->generatePublicToken();
        return back()->with('success', 'Public link enabled.');
    }
    // app/Http/Controllers/InvoiceController.php

    public function duplicate(Invoice $invoice)
    {
        $this->authorizeOwnership($invoice);

        // نسخ الفاتورة
        $newInvoice = $invoice->replicate();
        $newInvoice->status = 'draft';           // دايماً draft
        $newInvoice->issue_date = now()->toDateString();
        $newInvoice->due_date = null;
        $newInvoice->public_token = null;              // رابط جديد منفصل
        $newInvoice->invoice_number = Invoice::generateNumber(auth()->id());
        $newInvoice->save();

        // نسخ البنود
        foreach ($invoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        return redirect()->route('invoices.show', $newInvoice)
            ->with('success', "Invoice duplicated as {$newInvoice->invoice_number}.");
    }
}