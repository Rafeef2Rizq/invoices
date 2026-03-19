{{-- Shared by create + edit --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $customer->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $customer->email ?? '') }}">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Phone</label>
    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
        value="{{ old('phone', $customer->phone ?? '') }}">
    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>