<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Prescription System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: rgba(44, 62, 80, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        
        .main-content {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none;
        }
        
        .btn-success {
            background: linear-gradient(45deg, #27ae60, #229954);
            border: none;
        }
        
        .btn-warning {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            border: none;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(45deg, #2c3e50, #34495e);
            color: white;
            border: none;
            font-weight: 600;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .sidebar {
            background: rgba(44, 62, 80, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(52, 152, 219, 0.2);
            color: #fff;
            transform: translateX(5px);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin: 10px 0;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }
        
        .logo i {
            color: #3498db;
            margin-right: 10px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand logo" href="{{ route('dashboard') }}">
                <i class="fas fa-pills"></i>
                E-Prescription
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('resep.index') }}">
                            <i class="fas fa-prescription"></i> Resep
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('obatalkes.index') }}">
                            <i class="fas fa-pills"></i> Obat/Alkes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signa.index') }}">
                            <i class="fas fa-clipboard-list"></i> Signa
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                            <i class="fas fa-bell"></i> Notifikasi
                            @php
                                $unreadCount = Auth::user()->unreadNotifications()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2">
                <div class="sidebar">
                    <h6 class="text-white mb-3">
                        <i class="fas fa-bars"></i> Menu
                    </h6>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('resep.*') ? 'active' : '' }}" href="{{ route('resep.index') }}">
                            <i class="fas fa-prescription"></i> Resep
                        </a>
                        @if(Auth::user()->isApoteker())
                            <a class="nav-link {{ request()->routeIs('resep.processing') ? 'active' : '' }}" href="{{ route('resep.processing') }}">
                                <i class="fas fa-cog"></i> Sedang Diproses
                            </a>
                            <a class="nav-link {{ request()->routeIs('resep.completed') ? 'active' : '' }}" href="{{ route('resep.completed') }}">
                                <i class="fas fa-check"></i> Selesai
                            </a>
                        @endif
                        <a class="nav-link {{ request()->routeIs('obatalkes.*') ? 'active' : '' }}" href="{{ route('obatalkes.index') }}">
                            <i class="fas fa-pills"></i> Obat/Alkes
                        </a>
                        <a class="nav-link {{ request()->routeIs('signa.*') ? 'active' : '' }}" href="{{ route('signa.index') }}">
                            <i class="fas fa-clipboard-list"></i> Signa
                        </a>
                        <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                            <i class="fas fa-bell"></i> Notifikasi
                            @php
                                $unreadCount = Auth::user()->unreadNotifications()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                            @endif
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Show notifications from session
        @if(session('success'))
            toastr.success("{{ session('success') }}", "Berhasil!");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}", "Error!");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}", "Peringatan!");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}", "Informasi!");
        @endif

        // Show validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}", "Error!");
            @endforeach
        @endif
    </script>
    
    @stack('scripts')
</body>
</html> 