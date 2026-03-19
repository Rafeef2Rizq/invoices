<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .invoice-card {
            max-width: 780px;
            margin: 40px auto;
        }

        .invoice-header {
            background-color:
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            color: #fff;
            border-radius: 12px 12px 0 0;
            padding: 2rem;
        }

        .brand-name {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .brand-detail {
            font-size: .8rem;
            opacity: .75;
            line-height: 1.6;
        }

        .invoice-title {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .invoice-num {
            font-size: .85rem;
            opacity: .8;
            margin-top: 4px;
        }

        .invoice-body {
            background: #fff;
            padding: 2rem;
            border-radius: 0 0 12px 12px;
        }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #888;
            margin-bottom: 4px;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-draft {
            background: #e5e7eb;
            color: #374151;
        }

        .items-table thead tr {
            background-color:
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            color: #fff;
        }

        .items-table thead th {
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 600;
        }

        .items-table tbody td {
            padding: 10px 14px;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .total-row {
            background-color:
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            color: #fff;
        }

        .divider-colored {
            border: none;
            border-top: 3px solid
                {{ $setting->invoice_color ?? '#212529' }}
            ;
            opacity: .15;
            margin: 1.5rem 0;
        }

        .footer-note {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 1.5rem;
        }

        .logo-img {
            max-height: 60px;
            max-width: 160px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        @media (max-width: 576px) {
            .invoice-card {
                margin: 0;
            }

            .invoice-header {
                border-radius: 0;
            }

            .invoice-body {
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-card shadow-sm">

        {{-- Header --}}
        <div class="invoice-header">
            <div class="row align-items-start">
                <div class="col">
                    @if($setting->company_logo)
                        <img src="{{ asset('storage/' . $setting->company_logo) }}" alt="logo" class="logo-img">
                        <br>
                    @endif
                    <div class="brand-name">
                        {{ $setting->company_name ?? config('app.name') }}
                    </div>
                    @if($setting->company_email)
                        <div class="brand-detail">{{ $setting->company_email }}</div>
                    @endif
                    @if($setting->company_phone)
                        <div class="brand-detail">{{ $setting->company_phone }}</div>
                    @endif
                    @if($setting->company_address)
                        <div class="brand-detail">{{ $setting->company_address }}</div>
                    @endif
                </div>
                <div class="col text-end">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-num">{{ $invoice->invoice_number }}</div>
                    <div class="mt-2">
                        @if($invoice->isPaid())
                            <span class="badge badge-paid px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Paid
                            </span>
                        @else
                            <span class="badge badge-draft px-3 py-2">
                                <i class="bi bi-clock me-1"></i> Draft
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="invoice-body">

            {{-- Dates --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="section-label">Issue Date</div>
                    <div class="fw-semibold">
                        {{ $invoice->issue_date->format('F j, Y') }}
                    </div>
                </div>
                @if($invoice->due_date)
                    <div class="col-md-4">
                        <div class="section-label">Due Date</div>
                        <div class="fw-semibold">
                            {{ $invoice->due_date->format('F j, Y') }}
                        </div>
                    </div>
                @endif
            </div>

            <hr class="divider-colored">

            {{-- Bill To --}}
            <div class="mb-4">
                <div class="section-label">Bill To</div>
                <div class="fw-bold fs-6">{{ $invoice->customer->name }}</div>
                @if($invoice->customer->email)
                    <div class="text-muted small">{{ $invoice->customer->email }}</div>
                @endif
                @if($invoice->customer->phone)
                    <div class="text-muted small">{{ $invoice->customer->phone }}</div>
                @endif
            </div>

            {{-- Items --}}
            <div class="table-responsive mb-0">
                <table class="table items-table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center" style="width:80px">Qty</th>
                            <th class="text-end" style="width:120px">Unit Price</th>
                            <th class="text-end" style="width:120px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" class="text-end fw-bold py-3">Total Due</td>
                            <td class="text-end fw-bold py-3 fs-5">
                                ${{ number_format($invoice->total, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Notes --}}
            @if($invoice->notes)
                <div class="mt-4 p-3 rounded"
                    style="background:#f9fafb;border-left:3px solid {{ $setting->invoice_color ?? '#212529' }}">
                    <div class="section-label">Notes</div>
                    <div class="small text-muted">{{ $invoice->notes }}</div>
                </div>
            @endif

            {{-- Download Button --}}
            <div class="text-center mt-4">
                <a href="{{ route('invoices.public.pdf', $invoice->public_token) }}" class="btn btn-lg px-5 text-white"
                    style="background-color: {{ $setting->invoice_color ?? '#212529' }}">
                    <i class="bi bi-download me-2"></i> Download PDF
                </a>
            </div>

            {{-- Footer --}}
            <div class="footer-note">
                {{ $setting->invoice_footer
    ?? 'Thank you for your business — ' . config('app.name') }}
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>