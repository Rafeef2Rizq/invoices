@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Customers</h4>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Customer
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($customers->isEmpty())
                <div class="text-center text-muted py-4">No customers yet.</div>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="fw-semibold">{{ $customer->name }}</td>
                                <td>{{ $customer->email ?? '—' }}</td>
                                <td>{{ $customer->phone ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('customers.edit', $customer) }}"
                                        class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this customer?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
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

    <div class="mt-3">{{ $customers->links() }}</div>
@endsection