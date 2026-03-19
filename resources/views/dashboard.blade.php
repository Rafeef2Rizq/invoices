@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Dashboard</h4>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Invoice
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small">Total Invoices</span>
                        <span class="bg-primary bg-opacity-10 text-primary rounded p-2 lh-1">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                    </div>
                    <div class="fs-3 fw-semibold">{{ $totalInvoices }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small">Total Revenue</span>
                        <span class="bg-success bg-opacity-10 text-success rounded p-2 lh-1">
                            <i class="bi bi-cash-stack"></i>
                        </span>
                    </div>
                    <div class="fs-3 fw-semibold text-success">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small">Draft Invoices</span>
                        <span class="bg-warning bg-opacity-10 text-warning rounded p-2 lh-1">
                            <i class="bi bi-clock"></i>
                        </span>
                    </div>
                    <div class="fs-3 fw-semibold text-warning">{{ $totalDraft }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small">Total Customers</span>
                        <span class="bg-purple bg-opacity-10 text-purple rounded p-2 lh-1">
                            <i class="bi bi-people" style="color:#7c3aed"></i>
                        </span>
                    </div>
                    <div class="fs-3 fw-semibold" style="color:#7c3aed">{{ $totalCustomers }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Invoices + Top Customers --}}
    <div class="row g-3">

        {{-- Recent Invoices --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Recent Invoices</span>
                    <a href="{{ route('invoices.index') }}" class="text-decoration-none small">View all</a>
                </div>
                <div class="card-body p-0">
                    @if($recentInvoices->isEmpty())
                        <div class="text-center text-muted py-4">No invoices yet.</div>
                    @else
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Number</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                    <tr onclick="window.location='{{ route('invoices.show', $invoice) }}'" style="cursor:pointer">
                                        <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->customer->name }}</td>
                                        <td class="text-muted">{{ $invoice->issue_date->format('M d, Y') }}</td>
                                        <td class="fw-semibold">${{ number_format($invoice->total, 2) }}</td>
                                        <td><span class="{{ $invoice->statusBadgeClass() }}">{{ ucfirst($invoice->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Customers --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Top Customers</span>
                    <a href="{{ route('customers.index') }}" class="text-decoration-none small">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($topCustomers as $customer)
                        <div class="list-group-item px-3 py-3 border-0 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                                    style="width:36px;height:36px;background:#eff6ff;color:#1d4ed8;font-size:12px;flex-shrink:0">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-truncate small">{{ $customer->name }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ $customer->email ?? '—' }}</div>
                                </div>
                                <div class="fw-semibold small">${{ number_format($customer->total_billed, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small">No customers yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection