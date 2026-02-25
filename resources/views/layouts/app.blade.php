<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Proformas') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @stack('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logoAura.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logoAura.png') }}">
    <style>
        :root {
            --primary-color: #6c5dd3;
            --sidebar-width: 260px;
        }

        .sidebar {
            width: var(--sidebar-width);
            transition: all 0.3s;
            z-index: 1000;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #f8f9fa;
            border-right: 1px solid #e9ecef;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
            background: white;
        }

        .sidebar-brand img {
            height: 32px;
            margin-right: 1rem;
        }

        .sidebar-brand h1 {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-link {
            color: #495057;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .sidebar-nav .nav-link.active {
            background: #eef2ff;
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }

        .sidebar-nav .nav-link i {
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 24px;
        }

        .sidebar-nav .nav-link:hover {
            background: #eef2ff;
            color: var(--primary-color);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }

        .top-bar {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            position: fixed;
            width: calc(100% - var(--sidebar-width));
            right: 0;
            top: 0;
            z-index: 900;
        }

        .search-bar {
            max-width: 500px;
            width: 100%;
        }

        .search-bar input {
            border-radius: 30px;
            padding-left: 2.5rem;
            border: 1px solid #e9ecef;
        }

        .search-bar i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
        }

        .content-area {
            padding: 2rem;
            margin-top: 60px;
            min-height: calc(100vh - 60px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: #495057;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background: #ff4444;
            border-radius: 50%;
        }

        .main-content.collapsed {
            margin-left: 80px;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-brand h1,
        .sidebar.collapsed .sidebar-nav .nav-link span,
        .sidebar.collapsed .sidebar-nav .nav-link i + span {
            display: none;
        }

        .sidebar.collapsed .sidebar-brand img {
            margin-right: 0;
        }

        .sidebar.collapsed .sidebar-brand {
            padding: 1.5rem;
        }

        .sidebar.collapsed .sidebar-nav .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .sidebar-nav .nav-link i {
            margin-right: 0;
        }

        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #495057;
            cursor: pointer;
            margin-right: 1rem;
        }

        .toggle-sidebar-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #495057;
            cursor: pointer;
            margin-right: 1rem;
            display: none;
        }

        .toggle-sidebar-btn:focus {
            outline: none;
        }

        .hamburger:focus {
            outline: none;
        }

        @media (max-width: 992px) {
            .hamburger {
                display: block;
            }

            .toggle-sidebar-btn {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
            }

            .top-bar {
                width: 100%;
            }
        }

        .stats-card {
            transition: all 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stats-card .card-body {
            padding: 1.25rem;
        }

        .stats-card .card-footer {
            padding: 0.75rem 1.25rem;
            background: #f8f9fa;
        }

        .stats-card .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
        }

        .stats-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .stats-card .stat-desc {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .notification-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .notification-item .details {
            flex: 1;
        }

        .notification-item .title {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .notification-item .time {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .notification-item .amount {
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .notification-item .positive {
            color: #28a745;
        }

        .notification-item .negative {
            color: #dc3545;
        }

        .card-img {
            height: 120px;
            background: #eef2ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-img i {
            font-size: 3rem;
            color: var(--primary-color);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #212529;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #212529;
        }

        .section-header .btn {
            font-weight: 500;
        }

        .welcome-message {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }

        .welcome-message h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .welcome-message p {
            color: #495057;
            font-size: 1.1rem;
        }

        .welcome-message .illustration {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }

        .welcome-message .illustration img {
            max-width: 100%;
            height: auto;
        }

        .profile-dropdown {
            min-width: 200px;
        }

        .profile-dropdown .dropdown-item {
            padding: 0.75rem 1rem;
        }

        .profile-dropdown .dropdown-item i {
            margin-right: 0.5rem;
            width: 20px;
        }

        .profile-dropdown .dropdown-header {
            font-weight: 600;
            padding: 0.75rem 1rem;
            color: var(--primary-color);
        }

        .profile-dropdown .dropdown-divider {
            margin: 0.5rem 0;
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }

            .welcome-message h2 {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 992px) {
            .toggle-sidebar-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logoAura.png') }}" alt="Logo" class="logo">
            <h1 class="mb-0">Aura</h1>
        </div>

        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                @can('view proformas')
                <li class="nav-item">
                    <a href="{{ route('proformas.index') }}" class="nav-link {{ request()->routeIs('proformas.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Proformas</span>
                    </a>
                </li>
                @endcan

                @can('view clientes')
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                @endcan

                @can('view contactos')
                <li class="nav-item">
                    <a href="{{ route('contactos.index') }}" class="nav-link {{ request()->routeIs('contactos.*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Contactos</span>
                    </a>
                </li>
                @endcan

                @can('view productos')
                <li class="nav-item">
                    <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Productos</span>
                    </a>
                </li>
                @endcan

                {{-- Tipo de Cambio --}}
                <li class="nav-item">
                    <a href="{{ route('cambios.index') }}"
                       class="nav-link {{ request()->routeIs('cambios.*') ? 'active' : '' }}">
                        <i class="bi bi-currency-exchange"></i>
                        <span>Tipo de Cambio</span>
                    </a>
                </li>

                <!-- productos virtuales -->
                @can('view virtuals')

                <!-- productos virtuales
                <li class="nav-item">
                    <a href="{{ route('virtuals.index') }}" class="nav-link {{ request()->routeIs('virtuals.*') ? 'active' : '' }}">
                        <i class="bi bi-laptop"></i>
                        <span>Productos Virtuales</span>
                    </a>
                </li>


                @endcan

                <!-- proveedores    -->
                @can('view proveedores')

                <!-- proveedores
                <li class="nav-item">
                    <a href="{{ route('proveedores.index') }}" class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                -->
                @endcan

                @role('Administrador')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-gear"></i>
                        <span>Configuraciones</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i>
                        <span>Categorías</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('estados.index') }}" class="nav-link {{ request()->routeIs('estados.*') ? 'active' : '' }}">
                        <i class="bi bi-flag"></i>
                        <span>Estados</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('transacciones.index') }}" class="nav-link {{ request()->routeIs('transacciones.*') ? 'active' : '' }}">
                        <i class="bi bi-coin"></i>
                        <span>Transacciones</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('temperaturas.index') }}" class="nav-link {{ request()->routeIs('temperaturas.*') ? 'active' : '' }}">
                        <i class="bi bi-thermometer-sun"></i>
                        <span>Temperaturas</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('descuentos.index') }}" class="nav-link {{ request()->routeIs('descuentos.*') ? 'active' : '' }}">
                        <i class="bi bi-percent"></i>
                        <span>Descuentos</span>
                    </a>
                </li>

                <!-- creditos por el momento no necesita vistas de configuracion

                <li class="nav-item ms-3">
                    <a href="{{ route('creditos.index') }}" class="nav-link {{ request()->routeIs('creditos.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i>
                        <span>Créditos</span>
                    </a>
                </li>
                -->

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-gear"></i>
                        <span>Administración</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Usuarios</span>
                    </a>
                </li>

                <li class="nav-item ms-3">
                    <a href="{{ route('roles.index') }}"
                    class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-fill-check"></i>
                        <span>Roles</span>
                    </a>
                </li>

                @endrole
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <button class="hamburger" id="hamburger">
                    <i class="bi bi-list"></i>
                </button>
                <button class="toggle-sidebar-btn" id="toggle-sidebar">
                    <i class="bi bi-arrow-bar-left"></i>
                </button>
            </div>

            <!-- buscador extra
            <div class="search-bar position-relative">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>
            -->

            <div class="user-profile">
                <div class="notification-badge">
                    <i class="bi bi-bell" style="font-size: 1.2rem; color: #495057;"></i>
                </div>
                <div class="dropdown">
                    <div class="avatar" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <ul class="dropdown-menu profile-dropdown dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->name }}
                            </h6>
                        </li>
                        <li>
                            <span class="dropdown-item text-muted">
                                <i class="bi bi-envelope"></i>
                                {{ Auth::user()->email }}
                            </span>
                        </li>
                        <li>
                            <span class="dropdown-item text-muted">
                                <i class="bi bi-shield"></i>
                                @if(Auth::user()->getRoleNames()->isNotEmpty())
                                    {{ Auth::user()->getRoleNames()->first() }}
                                @else
                                    Sin rol asignado
                                @endif
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i>
                                Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear"></i>
                                Configuración
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="d-none d-sm-block">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.85rem;">
                        @if(Auth::user()->getRoleNames()->isNotEmpty())
                            {{ Auth::user()->getRoleNames()->first() }}
                        @else
                            Sin rol asignado
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('page-title')

            @yield('content')

            <!-- Footer -->
            <footer class="text-center text-muted mt-5 pt-4 border-top">
                <div class="container">
                    <p>© {{ date('Y') }} Sistema de Gestión de Proformas - Todos los derechos reservados</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const hamburger = document.getElementById('hamburger');
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const toggleIcon = toggleSidebar.querySelector('i');

            // Toggle sidebar on mobile
            hamburger.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Toggle sidebar collapse/expand
            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');

                // Change icon based on state
                if (sidebar.classList.contains('collapsed')) {
                    toggleIcon.classList.remove('bi-arrow-bar-left');
                    toggleIcon.classList.add('bi-arrow-bar-right');
                } else {
                    toggleIcon.classList.remove('bi-arrow-bar-right');
                    toggleIcon.classList.add('bi-arrow-bar-left');
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    const isSidebarClick = sidebar.contains(event.target);
                    const isHamburgerClick = hamburger.contains(event.target);

                    if (!isSidebarClick && !isHamburgerClick && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
