<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #fff;
            padding: 40px;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 36px;
        }

        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: top;
        }

        .header-right {
            text-align: right;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .brand-sub {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
            line-height: 1.6;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color:
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            letter-spacing: -0.5px;
        }

        .invoice-number {
            font-size: 13px;
            color: #555;
            margin-top: 4px;
        }

        /* ── Logo ── */
        .company-logo {
            max-height: 65px;
            max-width: 180px;
            object-fit: contain;
            margin-bottom: 8px;
            display: block;
        }

        /* ── Status Badge ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-draft {
            background: #e5e7eb;
            color: #374151;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 2px solid
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            margin: 24px 0;
            opacity: 0.15;
        }

        /* ── Meta Row ── */
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 28px;
        }

        .meta-cell {
            display: table-cell;
            width: 33%;
        }

        .meta-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #888;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 13px;
            color: #1a1a1a;
        }

        /* ── Bill To ── */
        .bill-section {
            display: table;
            width: 100%;
            margin-bottom: 32px;
        }

        .bill-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #888;
            margin-bottom: 6px;
        }

        .customer-name {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .customer-detail {
            font-size: 12px;
            color: #555;
            line-height: 1.6;
        }

        /* ── Items Table ── */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        table.items thead tr {
            background-color:
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            color: #fff;
        }

        table.items thead th {
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.items tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        table.items tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        table.items tbody td {
            padding: 10px 12px;
            font-size: 13px;
            color: #1a1a1a;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ── Totals ── */
        .totals-wrapper {
            display: table;
            width: 100%;
            margin-top: 0;
        }

        .totals-spacer,
        .totals-box {
            display: table-cell;
            vertical-align: top;
        }

        .totals-spacer {
            width: 55%;
        }

        .totals-box {
            width: 45%;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .totals-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #f0f0f0;
        }

        .totals-row:last-child {
            border-bottom: none;
        }

        .totals-label,
        .totals-value {
            display: table-cell;
            padding: 9px 12px;
            font-size: 13px;
        }

        .totals-value {
            text-align: right;
        }

        .totals-grand {
            background-color:
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            color: #fff;
        }

        .totals-grand .totals-label,
        .totals-grand .totals-value {
            font-size: 15px;
            font-weight: 700;
        }

        /* ── Notes ── */
        .notes-section {
            margin-top: 36px;
            padding: 14px 16px;
            background: #f9fafb;
            border-left: 3px solid
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            border-radius: 2px;
        }

        .notes-section .section-label {
            margin-bottom: 4px;
        }

        .notes-text {
            font-size: 12px;
            color: #444;
            line-height: 1.6;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 48px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }
    </style>
</head>

<body>

    {{-- ── Header ── --}}
    <div class="header">
        <div class="header-left">

            {{-- Logo --}}
            @if($setting->company_logo)
                <img src="{{ storage_path('app/public/' . $setting->company_logo) }}" alt="logo" class="company-logo">
            @endif

            {{-- Company Name --}}
            <div class="brand">
                {{ $setting->company_name ?? config('app.name') }}
            </div>

            {{-- Company Details --}}
            @if($setting->company_email)
                <div class="brand-sub">{{ $setting->company_email }}</div>
            @endif
            @if($setting->company_phone)
                <div class="brand-sub">{{ $setting->company_phone }}</div>
            @endif
            @if($setting->company_address)
                <div class="brand-sub">{{ $setting->company_address }}</div>
            @endif

        </div>

        <div class="header-right">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            <div>
                @if($invoice->isPaid())
                    <span class="badge badge-paid">Paid</span>
                @else
                    <span class="badge badge-draft">Draft</span>
                @endif
            </div>
        </div>
    </div>

    <hr class="divider">

    {{-- ── Dates ── --}}
    <div class="meta-row">
        <div class="meta-cell">
            <div class="meta-label">Issue Date</div>
            <div class="meta-value">{{ $invoice->issue_date->format('F j, Y') }}</div>
        </div>
        @if($invoice->due_date)
            <div class="meta-cell">
                <div class="meta-label">Due Date</div>
                <div class="meta-value">{{ $invoice->due_date->format('F j, Y') }}</div>
            </div>
        @endif
        <div class="meta-cell">
            <div class="meta-label">Status</div>
            <div class="meta-value">{{ ucfirst($invoice->status) }}</div>
        </div>
    </div>

    {{-- ── Bill To ── --}}
    <div class="bill-section">
        <div class="bill-left">
            <div class="section-label">Bill To</div>
            <div class="customer-name">{{ $invoice->customer->name }}</div>
            <div class="customer-detail">
                @if($invoice->customer->email)
                    {{ $invoice->customer->email }}<br>
                @endif
                @if($invoice->customer->phone)
                    {{ $invoice->customer->phone }}
                @endif
            </div>
        </div>
    </div>

    {{-- ── Line Items ── --}}
    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center" style="width:70px">Qty</th>
                <th class="text-right" style="width:110px">Unit Price</th>
                <th class="text-right" style="width:110px">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Totals ── --}}
    <div class="totals-wrapper">
        <div class="totals-spacer"></div>
        <div class="totals-box">
            <div class="totals-row totals-grand">
                <div class="totals-label">Total Due</div>
                <div class="totals-value">${{ number_format($invoice->total, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- ── Invoice Notes ── --}}
    @if($invoice->notes)
        <div class="notes-section">
            <div class="section-label">Notes</div>
            <div class="notes-text">{{ $invoice->notes }}</div>
        </div>
    @endif

    {{-- ── Footer ── --}}
    <div class="footer">
        {{ $setting->invoice_footer
    ?? 'Thank you for your business — ' . config('app.name') }}
    </div>

</body>

</html>