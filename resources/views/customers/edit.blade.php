@extends('layouts.app')
@section('title', 'Edit Customer')

@section('content')
    <div class="mb-4">
        <a href="{{ route('customers.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 560px;">
        <div class="card-header bg-white fw-semibold">Edit Customer</div>
        <div class="card-body">
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $customer->name) }}" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $customer->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $customer->phone) }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Update Customer
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection