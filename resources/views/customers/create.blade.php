@extends('layouts.app')
@section('title', 'Add Customer')

@section('content')
    <div class="mb-4">
        <a href="{{ route('customers.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 560px;">
        <div class="card-header bg-white fw-semibold">Add Customer</div>
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                @include('customers._form')
                <button type="submit" class="btn btn-primary w-100 mt-2">
                    <i class="bi bi-check-lg me-1"></i> Save Customer
                </button>
            </form>
        </div>
    </div>
@endsection