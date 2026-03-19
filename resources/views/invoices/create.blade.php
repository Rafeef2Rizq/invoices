@extends('layouts.app')
@section('title', 'New Invoice')

@section('content')
    <div class="mb-4">
        <a href="{{ route('invoices.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Back to Invoices
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Create Invoice</div>
        <div class="card-body">
            <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
                @csrf

                {{-- Invoice Meta --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                        <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                        <input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror"
                            value="{{ old('issue_date', date('Y-m-d')) }}" required>
                        @error('issue_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                            value="{{ old('due_date') }}">
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="fw-semibold mb-2">Invoice Items</div>
                <table class="table table-bordered" id="items-table">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th style="width:110px">Qty</th>
                            <th style="width:130px">Price ($)</th>
                            <th style="width:130px">Subtotal</th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        @if(old('items'))
                            @foreach(old('items') as $i => $item)
                                <tr>
                                    <td><input type="text" name="items[{{ $i }}][name]" class="form-control item-name"
                                            value="{{ $item['name'] }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control item-qty" min="1"
                                            value="{{ $item['quantity'] }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][price]" class="form-control item-price" min="0"
                                            step="0.01" value="{{ $item['price'] }}" required></td>
                                    <td><input type="text" class="form-control item-subtotal" readonly
                                            value="{{ number_format($item['quantity'] * $item['price'], 2) }}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td><input type="text" name="items[0][name]" class="form-control item-name" required></td>
                                <td><input type="number" name="items[0][quantity]" class="form-control item-qty" min="1"
                                        value="1" required></td>
                                <td><input type="number" name="items[0][price]" class="form-control item-price" min="0"
                                        step="0.01" value="0" required></td>
                                <td><input type="text" class="form-control item-subtotal" readonly value="0.00"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td><input type="text" id="grand-total" class="form-control fw-bold" readonly value="0.00"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                @error('items')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <button type="button" id="add-row" class="btn btn-outline-secondary btn-sm mb-4">
                    <i class="bi bi-plus-lg me-1"></i> Add Item
                </button>

                {{-- Notes --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="Optional notes...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Save Invoice
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let rowIndex = {{ old('items') ? count(old('items')) : 1 }};

        function recalculate() {
            let grand = 0;
            document.querySelectorAll('#items-body tr').forEach(row => {
                const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
                const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
                const sub = qty * price;
                const subEl = row.querySelector('.item-subtotal');
                if (subEl) subEl.value = sub.toFixed(2);
                grand += sub;
            });
            document.getElementById('grand-total').value = grand.toFixed(2);
        }

        function attachRowEvents(row) {
            row.querySelector('.item-qty').addEventListener('input', recalculate);
            row.querySelector('.item-price').addEventListener('input', recalculate);
            row.querySelector('.remove-row').addEventListener('click', function () {
                if (document.querySelectorAll('#items-body tr').length > 1) {
                    row.remove();
                    recalculate();
                }
            });
        }

        // Attach to existing rows
        document.querySelectorAll('#items-body tr').forEach(attachRowEvents);
        recalculate();

        // Add new row
        document.getElementById('add-row').addEventListener('click', function () {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text"   name="items[${rowIndex}][name]"     class="form-control item-name"     required></td>
                <td><input type="number" name="items[${rowIndex}][quantity]"  class="form-control item-qty"  min="1" value="1" required></td>
                <td><input type="number" name="items[${rowIndex}][price]"     class="form-control item-price" min="0" step="0.01" value="0" required></td>
                <td><input type="text"                                         class="form-control item-subtotal" readonly value="0.00"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                        <i class="bi bi-x"></i>
                    </button>
                </td>`;
            document.getElementById('items-body').appendChild(row);
            attachRowEvents(row);
            rowIndex++;
        });
    </script>
@endpush