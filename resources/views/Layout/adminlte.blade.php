<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles personnalisés pour les dropdowns -->
    <style>
        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            position: absolute;
            right: 0;
            left: auto;
            top: 100%;
        }
        .dropdown-item button {
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
        }
        .dropdown-item.p-0 {
            padding: 0 !important;
        }
        .btn-outline-danger {
            background-color: transparent;
            border: 1px solid #dc3545;
            color: #dc3545 !important;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white !important;
        }
    </style>

    <!-- AdminLTE CSS ONLY -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <style>
        /* Fix conflicts */
        .content-wrapper { min-height: calc(100vh - 150px); }
        .main-sidebar { z-index: 1038; }
        .navbar { z-index: 1030; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">Accueil</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Profile Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user"></i>
                        @if(Auth::check())
                            {{ Auth::user()->name }}
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="dropdown-item p-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger text-danger w-100 px-3 py-2">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                @if(Auth::check())
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>
                @endif

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('restaurants.index') }}" class="nav-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-utensils"></i>
                                <p>Restaurants</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isRestaurateur()) ? route('admin.categories.index') : route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Catégories</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ 
                                auth()->user() && auth()->user()->isAdmin() 
                                    ? route('admin.items.index') 
                                    : (auth()->user() && auth()->user()->isRestaurateur() && auth()->user()->restaurants->count() == 1 
                                        ? route('restaurants.items.index', auth()->user()->restaurants->first()) 
                                        : route('items.index')) 
                            }}" class="nav-link {{ request()->routeIs('items.*') || request()->routeIs('admin.items.*') || request()->routeIs('restaurants.items.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hamburger"></i>
                                <p>Items</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('header')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name', 'Laravel') }}</a>.</strong> All rights reserved.
        </footer>
        
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    
    <!-- Script pour s'assurer que le dropdown de déconnexion fonctionne correctement -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // S'assurer que le dropdown utilisateur fonctionne
            const userDropdown = document.querySelector('.nav-item.dropdown');
            if (userDropdown) {
                const dropdownToggle = userDropdown.querySelector('[data-toggle="dropdown"]');
                const dropdownMenu = userDropdown.querySelector('.dropdown-menu');
                
                if (dropdownToggle && dropdownMenu) {
                    // Ajouter un gestionnaire d'événements pour le clic
                    dropdownToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropdownMenu.classList.toggle('show');
                    });
                    
                    // Fermer le dropdown lors d'un clic à l'extérieur
                    document.addEventListener('click', function(e) {
                        if (!userDropdown.contains(e.target)) {
                            dropdownMenu.classList.remove('show');
                        }
                    });
                }
            }
            
            // S'assurer que le formulaire de déconnexion fonctionne
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    // Empêcher les comportements par défaut qui pourraient interférer
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>
</html>