@extends('layouts.app')
@section('title', $invoice->invoice_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('invoices.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Back to Invoices
        </a>
        <div class="d-flex gap-2">

            {{-- Toggle Status --}}
            <form action="{{ route('invoices.updateStatus', $invoice) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-outline-{{ $invoice->isPaid() ? 'secondary' : 'success' }} btn-sm">
                    <i class="bi bi-{{ $invoice->isPaid() ? 'arrow-counterclockwise' : 'check-circle' }} me-1"></i>
                    Mark as {{ $invoice->isPaid() ? 'Draft' : 'Paid' }}
                </button>
            </form>

            {{-- Toggle Public Link --}}
            <form action="{{ route('invoices.togglePublic', $invoice) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-outline-{{ $invoice->isPublic() ? 'warning' : 'info' }}">
                    <i class="bi bi-{{ $invoice->isPublic() ? 'link-45deg' : 'share' }} me-1"></i>
                    {{ $invoice->isPublic() ? 'Disable Link' : 'Share Link' }}
                </button>
            </form>
            {{-- Duplicate Invoice --}}
            <form action="{{ route('invoices.duplicate', $invoice) }}" method="POST">
                @csrf
                <button class="btn btn-outline-secondary btn-sm" onclick="return confirm('Duplicate this invoice?')">
                    <i class="bi bi-copy me-1"></i> Duplicate
                </button>
            </form>

            {{-- Download PDF --}}
            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-download me-1"></i> Download PDF
            </a>

            {{-- Delete --}}
            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                onsubmit="return confirm('Delete this invoice?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    {{-- ✅ Public Link Box — يظهر فقط إذا الرابط مفعّل --}}
    @if($invoice->isPublic())
        <div class="card border-0 shadow-sm mb-3 border-start border-success border-3">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold small">
                        <i class="bi bi-globe2 me-1 text-success"></i>
                        Public link is active
                    </span>
                    <span class="badge bg-success">Live</span>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="public-url" value="{{ $invoice->publicUrl() }}"
                        readonly>
                    <button class="btn btn-outline-secondary btn-sm" onclick="copyLink()" type="button" title="Copy link">
                        <i class="bi bi-clipboard" id="copy-icon"></i>
                    </button>
                    <a href="{{ $invoice->publicUrl() }}" target="_blank" class="btn btn-outline-secondary btn-sm"
                        title="Preview">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
                <div class="form-text mt-1">
                    <i class="bi bi-info-circle me-1"></i>
                    Anyone with this link can view and download the invoice — no login required.
                </div>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="row mb-4">
                <div class="col">
                    <h5 class="fw-bold mb-0">{{ $invoice->invoice_number }}</h5>
                    <span class="{{ $invoice->statusBadgeClass() }} mt-1">{{ ucfirst($invoice->status) }}</span>
                </div>
                <div class="col text-end text-muted small">
                    <div>Issued: {{ $invoice->issue_date->format('M d, Y') }}</div>
                    @if($invoice->due_date)
                        <div>Due: {{ $invoice->due_date->format('M d, Y') }}</div>
                    @endif
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="mb-4">
                <div class="text-muted small fw-semibold mb-1">BILL TO</div>
                <div class="fw-semibold">{{ $invoice->customer->name }}</div>
                @if($invoice->customer->email)
                    <div class="text-muted small">{{ $invoice->customer->email }}</div>
                @endif
                @if($invoice->customer->phone)
                    <div class="text-muted small">{{ $invoice->customer->phone }}</div>
                @endif
            </div>

            {{-- Items Table --}}
            <table class="table table-bordered mb-3">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th class="text-center" style="width:80px">Qty</th>
                        <th class="text-end" style="width:120px">Price</th>
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
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold fs-5">${{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            {{-- Notes --}}
            @if($invoice->notes)
                <div class="text-muted small">
                    <span class="fw-semibold">Notes:</span> {{ $invoice->notes }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function copyLink() {
                const input = document.getElementById('public-url');
                navigator.clipboard.writeText(input.value).then(() => {
                    const icon = document.getElementById('copy-icon');
                    icon.className = 'bi bi-check2 text-success';
                    setTimeout(() => icon.className = 'bi bi-clipboard', 2000);
                });
            }
        </script>
    @endpush

@endsection