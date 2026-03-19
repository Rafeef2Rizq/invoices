{{-- resources/views/settings/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Company Settings</h4>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Left Column --}}
            <div class="col-md-8">

                {{-- Company Info --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-building me-2"></i>Company Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Company Name</label>
                                <input type="text" name="company_name"
                                    class="form-control @error('company_name') is-invalid @enderror"
                                    value="{{ old('company_name', $setting->company_name) }}" placeholder="Acme Inc.">
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="company_email"
                                    class="form-control @error('company_email') is-invalid @enderror"
                                    value="{{ old('company_email', $setting->company_email) }}"
                                    placeholder="hello@company.com">
                                @error('company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="company_phone"
                                    class="form-control @error('company_phone') is-invalid @enderror"
                                    value="{{ old('company_phone', $setting->company_phone) }}"
                                    placeholder="+1 555 000 0000">
                                @error('company_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Invoice Prefix</label>
                                <input type="text" name="invoice_prefix"
                                    class="form-control @error('invoice_prefix') is-invalid @enderror"
                                    value="{{ old('invoice_prefix', $setting->invoice_prefix ?? 'INV') }}"
                                    placeholder="INV">
                                <div class="form-text">e.g. INV → INV-0001</div>
                                @error('invoice_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea name="company_address" rows="2"
                                    class="form-control @error('company_address') is-invalid @enderror"
                                    placeholder="123 Main St, City, Country">{{ old('company_address', $setting->company_address) }}</textarea>
                                @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Invoice Footer Note</label>
                                <textarea name="invoice_footer" rows="2"
                                    class="form-control @error('invoice_footer') is-invalid @enderror"
                                    placeholder="Thank you for your business. Payment due within 30 days.">{{ old('invoice_footer', $setting->invoice_footer) }}</textarea>
                                @error('invoice_footer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Invoice Style --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-palette me-2"></i>Invoice Style
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold">Primary Color</label>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            {{-- Preset colors --}}
                            @foreach(['#212529', '#1d4ed8', '#0f6e56', '#993556', '#854F0B', '#7c3aed'] as $color)
                                <div class="color-preset rounded"
                                    style="width:32px;height:32px;background:{{ $color }};cursor:pointer;border:2px solid transparent"
                                    data-color="{{ $color }}" onclick="setColor('{{ $color }}')">
                                </div>
                            @endforeach
                            <input type="color" id="color-picker" name="invoice_color"
                                value="{{ old('invoice_color', $setting->invoice_color ?? '#212529') }}"
                                class="form-control form-control-color" style="width:48px;height:32px;padding:2px">
                            <span id="color-label" class="text-muted small">
                                {{ old('invoice_color', $setting->invoice_color ?? '#212529') }}
                            </span>
                        </div>
                        <div class="form-text mt-2">This color is used for the invoice header and totals row.</div>
                    </div>
                </div>

            </div>

            {{-- Right Column — Logo --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-image me-2"></i>Company Logo
                    </div>
                    <div class="card-body text-center">

                        {{-- Current Logo --}}
                        @if($setting->company_logo)
                            <img src="{{ $setting->logoUrl() }}" alt="Logo" class="img-fluid mb-3 rounded"
                                style="max-height:100px;object-fit:contain">
                            <br>
                            <form action="{{ route('settings.deleteLogo') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger mb-3">
                                    <i class="bi bi-trash me-1"></i> Remove Logo
                                </button>
                            </form>
                        @else
                            <div class="border rounded d-flex align-items-center justify-content-center mb-3"
                                style="height:100px;background:#f8f9fa">
                                <span class="text-muted small">No logo uploaded</span>
                            </div>
                        @endif

                        <input type="file" name="company_logo" class="form-control form-control-sm"
                            accept="image/png,image/jpeg">
                        <div class="form-text">PNG or JPG, max 2MB</div>
                        @error('company_logo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Preview Card --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-eye me-2"></i>Header Preview
                    </div>
                    <div class="card-body p-0">
                        <div id="preview-header" class="p-3 text-white rounded-bottom"
                            style="background: {{ $setting->invoice_color ?? '#212529' }}">
                            <div class="fw-bold">{{ $setting->company_name ?? 'Your Company' }}</div>
                            <div style="font-size:12px;opacity:.8">{{ $setting->company_email ?? 'email@company.com' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i> Save Settings
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function setColor(hex) {
            document.getElementById('color-picker').value = hex;
            document.getElementById('color-label').textContent = hex;
            document.getElementById('preview-header').style.background = hex;
            document.querySelectorAll('.color-preset').forEach(el => {
                el.style.border = el.dataset.color === hex
                    ? '2px solid #000'
                    : '2px solid transparent';
            });
        }

        document.getElementById('color-picker').addEventListener('input', function () {
            document.getElementById('color-label').textContent = this.value;
            document.getElementById('preview-header').style.background = this.value;
        });
    </script>
@endpush