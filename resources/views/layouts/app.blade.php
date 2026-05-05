<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rental Mobil WakPim | @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }

        .main-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: none;
        }

        .main-header .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
        }

        .main-header .navbar-nav .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        /* Sidebar Styling */
        .main-sidebar {
            background: linear-gradient(180deg, var(--dark-color) 0%, #111827 100%);
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
        }

        .brand-link {
            background-color: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .brand-link .brand-text {
            font-weight: 600;
            color: white;
            letter-spacing: 0.5px;
        }

        .sidebar {
            padding-top: 20px;
        }

        .nav-sidebar > .nav-item > .nav-link {
            color: #cbd5e1;
            margin: 4px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-sidebar > .nav-item > .nav-link:hover {
            background-color: rgba(59, 130, 246, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .nav-sidebar .nav-icon {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .nav-treeview .nav-link {
            padding-left: 50px !important;
            margin-left: 15px;
            border-left: 2px solid rgba(255,255,255,0.1);
        }

        /* Content Header */
        .content-header {
            background: white;
            border-radius: 10px;
            margin: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px 25px !important;
        }

        .content-header h1 {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.8rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
        }

        .breadcrumb-item a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: #6b7280;
        }

        /* Main Content */
        .content-wrapper {
            background-color: #f5f7fa;
        }

        .content {
            padding: 0 15px;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 2px solid #e5e7eb;
            padding: 20px 25px;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-title {
            color: var(--dark-color);
            font-weight: 700;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        /* Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
            border-top: none;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-top: 1px solid #e5e7eb;
        }

        /* Badge Styling */
        .badge {
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 20px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .btn-group .btn {
            margin-right: 5px;
            border-radius: 6px;
        }

        /* Alert Styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .alert-dismissible .close {
            padding: 0.75rem 1.25rem;
        }

        /* Footer */
        .main-footer {
            background: white;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            padding: 20px;
            margin: 0 15px 15px;
            border-radius: 10px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }

        /* User Dropdown */
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 10px 20px;
            color: #4b5563;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-header {
            font-weight: 600;
            color: var(--dark-color);
            background-color: #f8fafc;
        }

        /* Form Controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Logo Styling */
        .brand-link img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-right: 10px;
            filter: brightness(0) invert(1);
        }

        /* Small Screen Adjustments */
        @media (max-width: 768px) {
            .content-header {
                margin: 10px;
                padding: 15px !important;
            }
            
            .card-header {
                padding: 15px 20px;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .main-footer {
                margin: 0 10px 10px;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Animation for page transitions */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-active { background-color: var(--secondary-color); }
        .status-inactive { background-color: #9ca3af; }
        .status-pending { background-color: var(--accent-color); }

        /* Custom badge colors */
        .badge-success { background-color: var(--secondary-color); }
        .badge-warning { background-color: var(--accent-color); }
        .badge-danger { background-color: #ef4444; }
        .badge-info { background-color: var(--primary-light); }
        .badge-secondary { background-color: #6b7280; }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                    <div class="user-avatar mr-2">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/profiles/' . Auth::user()->photo) }}" 
                                 class="img-circle elevation-1" alt="User Image" 
                                 style="width: 36px; height: 36px; object-fit: cover; border: 2px solid white;">
                        @else
                            <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);">
                                <span class="text-white font-weight-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="user-info d-none d-md-block">
                        <span class="font-weight-bold">{{ Auth::user()->name }}</span>
                        <small class="d-block text-muted">
                            {{ Auth::user()->isAdmin() ? 'Administrator' : 'Karyawan' }}
                        </small>
                    </div>
                    <i class="fas fa-chevron-down ml-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <div class="mb-2">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/profiles/' . Auth::user()->photo) }}" 
                                     class="img-circle" alt="User Image" 
                                     style="width: 60px; height: 60px; object-fit: cover; border: 3px solid var(--primary-light);">
                            @else
                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);">
                                    <span class="text-white font-weight-bold" style="font-size: 1.5rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile.index') }}" class="dropdown-item">
                        <i class="fas fa-user-circle mr-2"></i> Profile Saya
                    </a>
                    <a href="{{ route('profile.password') }}" class="dropdown-item">
                        <i class="fas fa-key mr-2"></i> Ubah Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" 
                       class="dropdown-item text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link text-center py-3">
            <div class="brand-logo mb-2">
                <div class="logo-circle mx-auto d-flex align-items-center justify-content-center"
                     style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%); border-radius: 12px;">
                     <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height: 60px;">
                </div>
            </div>
            <span class="brand-text font-weight-bold">RENTAL WAKPIM</span>
            
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header text-uppercase small font-weight-bold text-light opacity-75 mb-2">Menu Utama</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isKaryawan())
                    <li class="nav-header text-uppercase small font-weight-bold text-light opacity-75 mb-2 mt-3">Operasional</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('mobils.index') }}" class="nav-link {{ request()->routeIs('mobils.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-car"></i>
                            <p>Manajemen Mobil</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('pelanggans.index') }}" class="nav-link {{ request()->routeIs('pelanggans.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manajemen Pelanggan</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('penyewaans.index') }}" class="nav-link {{ request()->routeIs('penyewaans.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Penyewaan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pengembalians.index') }}" class="nav-link {{ request()->routeIs('pengembalians.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-undo-alt"></i>
                            <p>Pengembalian</p>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                    <li class="nav-header text-uppercase small font-weight-bold text-light opacity-75 mb-2 mt-3">Administrasi</li>
                    
                    <li class="nav-item {{ request()->routeIs('laporan.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('laporan.harian') }}" class="nav-link {{ request()->routeIs('laporan.harian') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Harian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.bulanan') }}" class="nav-link {{ request()->routeIs('laporan.bulanan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Bulanan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.tahunan') }}" class="nav-link {{ request()->routeIs('laporan.tahunan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Tahunan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.mobil') }}" class="nav-link {{ request()->routeIs('laporan.mobil') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Mobil</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.pelanggan') }}" class="nav-link {{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Pelanggan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Manajemen User</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <i class="fas @yield('icon', 'fa-file-alt') mr-2 text-primary"></i>
                            @yield('title')
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content fade-in">
            <div class="container-fluid">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check-circle"></i> Berhasil!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-exclamation-circle"></i> Gagal!</h5>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-info-circle"></i> Informasi!</h5>
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <strong>Copyright &copy; {{ date('Y') }} <a href="#" class="text-primary">Rental Mobil WakPim</a>.</strong>
                    All rights reserved.
                </div>
                <div class="col-md-6 text-right">
                    <span class="badge badge-info">v1.0.0</span>
                    <small class="text-muted ml-2">
                        <i class="fas fa-clock mr-1"></i>
                        {{ date('d F Y, H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom JavaScript -->
<script>
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Auto dismiss alerts after 5 seconds
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });

    // Add active class to current page in sidebar
    $(document).ready(function() {
        $('.nav-link').each(function() {
            if ($(this).attr('href') === window.location.pathname) {
                $(this).addClass('active');
                $(this).parents('.nav-item').addClass('menu-open');
            }
        });
    });

    // Smooth scroll to top
    $('a[href="#"]').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 500);
    });
</script>

@stack('scripts')
</body>
</html>