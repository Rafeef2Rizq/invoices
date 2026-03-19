@extends('layouts.app')
@section('title', 'Reports')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Reports & Analytics</h4>
        <div class="d-flex gap-2">

            {{-- Year Filter --}}
            <form method="GET" action="{{ route('reports.index') }}" class="d-flex gap-2">
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                    <option value="{{ now()->year }}" {{ now()->year == $year ? 'selected' : '' }}>
                        {{ now()->year }}
                    </option>
                </select>
            </form>

            {{-- Export --}}
            <a href="{{ route('reports.export', ['year' => $year]) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Revenue</div>
                    <div class="fs-5 fw-bold text-success">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Invoices</div>
                    <div class="fs-5 fw-bold">{{ $totalInvoices }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Paid</div>
                    <div class="fs-5 fw-bold text-success">{{ $totalPaid }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Draft</div>
                    <div class="fs-5 fw-bold text-warning">{{ $totalDraft }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Customers</div>
                    <div class="fs-5 fw-bold">{{ $totalCustomers }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Avg Invoice</div>
                    <div class="fs-5 fw-bold">${{ number_format($avgInvoiceValue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">

        {{-- Monthly Revenue Bar Chart --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-bar-chart me-2"></i>Monthly Revenue — {{ $year }}
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Status Pie Chart --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-pie-chart me-2"></i>Invoice Status
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Customers + Last 30 Days --}}
    <div class="row g-3">

        {{-- Top Customers --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-trophy me-2"></i>Top Customers
                </div>
                <div class="card-body p-0">
                    @forelse($topCustomers as $i => $customer)
                        <div class="d-flex align-items-center px-3 py-3
                             {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="fw-bold text-muted me-3" style="width:20px">
                                {{ $i + 1 }}
                            </div>
                            <div class="rounded-circle d-flex align-items-center
                                        justify-content-center fw-semibold me-3" style="width:36px;height:36px;background:#eff6ff;
                                        color:#1d4ed8;font-size:12px;flex-shrink:0">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">{{ $customer->name }}</div>
                                <div class="text-muted" style="font-size:11px">
                                    {{ $customer->invoices_count }} invoice(s)
                                </div>
                            </div>
                            <div class="fw-bold text-success small">
                                ${{ number_format($customer->total_billed ?? 0, 2) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No data yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Last 30 Days Line Chart --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-graph-up me-2"></i>Revenue — Last 30 Days
                </div>
                <div class="card-body">
                    <canvas id="last30Chart" height="120"></canvas>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // ── بيانات من Laravel ──────────────────────────────
        const monthlyData = @json($monthlyData);
        const statusData = @json($statusData);
        const last30 = @json($last30Days);

        // ── Monthly Bar Chart ──────────────────────────────
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: 'Revenue ($)',
                    data: monthlyData.map(d => d.revenue),
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' $' + ctx.raw.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: val => '$' + val.toLocaleString()
                        },
                        grid: { color: '#f0f0f0' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // ── Status Pie Chart ───────────────────────────────
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Draft'],
                datasets: [{
                    data: [statusData.paid, statusData.draft],
                    backgroundColor: ['#16a34a', '#9ca3af'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 16, usePointStyle: true }
                    }
                }
            }
        });

        // ── Last 30 Days Line Chart ────────────────────────
        new Chart(document.getElementById('last30Chart'), {
            type: 'line',
            data: {
                labels: last30.map(d => d.date),
                datasets: [{
                    label: 'Revenue ($)',
                    data: last30.map(d => d.revenue),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' $' + ctx.raw.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: val => '$' + val.toLocaleString() },
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { maxTicksLimit: 8 }
                    }
                }
            }
        });
    </script>
@endpush