@extends('layouts.app')
@section('title', 'Invoices')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Invoices</h4>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Invoice
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($invoices->isEmpty())
                <div class="text-center text-muted py-4">No invoices yet.</div>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Number</th>
                            <th>Customer</th>
                            <th>Issue Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->customer->name }}</td>
                                <td>{{ $invoice->issue_date->format('M d, Y') }}</td>
                                <td>${{ number_format($invoice->total, 2) }}</td>
                                <td>
                                    <span class="{{ $invoice->statusBadgeClass() }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{-- View --}}
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-eye"></i> View
                                    </a>

                                    {{-- Duplicate --}}
                                    <form action="{{ route('invoices.duplicate', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary me-1" title="Duplicate invoice">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this invoice?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="mt-3">{{ $invoices->links() }}</div>
@endsection