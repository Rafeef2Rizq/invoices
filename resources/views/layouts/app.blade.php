<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            padding-top: 1rem;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: .6rem 1.25rem;
            border-radius: .375rem;
            margin: .1rem .75rem;
            transition: background .15s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #343a40;
            color: #fff;
        }

        .sidebar .brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            padding: .5rem 1.25rem 1rem;
            display: block;
        }

        .main-content {
            padding: 2rem;
        }

        .nav-divider {
            border-top: 1px solid #343a40;
            margin: .5rem .75rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar --}}
            <nav class="col-md-2 sidebar d-none d-md-block">
                <a href="{{ route('dashboard') }}" class="brand">
                    <i class="bi bi-receipt"></i> InvoiceApp
                </a>
                <div class="nav-divider"></div>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('customers.index') }}"
                    class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Customers
                </a>
                <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Invoices
                </a>
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart me-2"></i> Reports
                </a>
                <a href="{{ route('settings.edit') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
                <div class="nav-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit()"
                        style="color:#adb5bd;text-decoration:none;display:block;padding:.6rem 1.25rem;border-radius:.375rem;margin:.1rem .75rem;">
                        <i class="bi bi-box-arrow-left me-2"></i> Logout
                    </a>
                </form>
            </nav>

            {{-- Main Content --}}
            <main class="col-md-10 main-content">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>