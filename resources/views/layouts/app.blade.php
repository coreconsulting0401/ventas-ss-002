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

                @haspermission('view cambios')
                {{-- Tipo de Cambio --}}
                <li class="nav-item">
                    <a href="{{ route('cambios.index') }}"
                       class="nav-link {{ request()->routeIs('cambios.*') ? 'active' : '' }}">
                        <i class="bi bi-currency-exchange"></i>
                        <span>Tipo de Cambio</span>
                    </a>
                </li>
                @endhaspermission

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


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-gear"></i>
                        <span>Configuraciones</span>
                    </a>
                </li>

                @haspermission('view categorias')
                <li class="nav-item ms-3">
                    <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                @endhaspermission
                @haspermission('view estados')
                <li class="nav-item ms-3">
                    <a href="{{ route('estados.index') }}" class="nav-link {{ request()->routeIs('estados.*') ? 'active' : '' }}">
                        <i class="bi bi-flag"></i>
                        <span>Estados</span>
                    </a>
                </li>
                @endhaspermission
                @haspermission('view transacciones')
                <li class="nav-item ms-3">
                    <a href="{{ route('transacciones.index') }}" class="nav-link {{ request()->routeIs('transacciones.*') ? 'active' : '' }}">
                        <i class="bi bi-coin"></i>
                        <span>Transacciones</span>
                    </a>
                </li>
                @endhaspermission
                @haspermission('view temperaturas')
                <li class="nav-item ms-3">
                    <a href="{{ route('temperaturas.index') }}" class="nav-link {{ request()->routeIs('temperaturas.*') ? 'active' : '' }}">
                        <i class="bi bi-thermometer-sun"></i>
                        <span>Temperaturas</span>
                    </a>
                </li>
                @endhaspermission

                @haspermission('view descuentos')
                <li class="nav-item ms-3">
                    <a href="{{ route('descuentos.index') }}" class="nav-link {{ request()->routeIs('descuentos.*') ? 'active' : '' }}">
                        <i class="bi bi-percent"></i>
                        <span>Descuentos</span>
                    </a>
                </li>
                @endhaspermission

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


                @haspermission('view users')
                <li class="nav-item ms-3">
                    <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                @endhaspermission

                @haspermission('view roles')
                <li class="nav-item ms-3">
                    <a href="{{ route('roles.index') }}"
                    class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-fill-check"></i>
                        <span>Roles</span>
                    </a>
                </li>
                @endhaspermission

                @haspermission('view empresas')
                <li class="nav-item">
                    <a href="{{ route('empresas.index') }}"
                    class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Empresa</span>
                    </a>
                </li>
                @endhaspermission
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
                {{-- ── CAMPANA DE NOTIFICACIONES ── --}}
                <div class="dropdown" id="notif-dropdown">
                    <button class="btn p-0 border-0 bg-transparent position-relative"
                            id="notifBtn"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-bell" style="font-size:1.25rem;color:#495057;"></i>
                        <span id="notif-badge"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="font-size:.6rem;min-width:18px;display:none;">0</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end shadow-lg p-0"
                         aria-labelledby="notifBtn"
                         style="width:360px;max-width:94vw;border-radius:12px;overflow:hidden;border:1px solid #e9ecef;">
                        {{-- Header --}}
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-white">
                            <span style="font-size:.85rem;font-weight:700;color:#212529;">Notificaciones</span>
                            <button class="btn btn-link btn-sm p-0 text-decoration-none"
                                    style="font-size:.75rem;color:#6c5dd3;"
                                    onclick="marcarTodasLeidas(event)">
                                Marcar todas leídas
                            </button>
                        </div>

                        {{-- Lista dinámica --}}
                        <div id="notif-list"
                             style="max-height:380px;overflow-y:auto;background:#f8f9fa;">
                            <div class="text-center py-4 text-muted" id="notif-empty" style="display:none;font-size:.82rem;">
                                <i class="bi bi-bell-slash" style="font-size:1.8rem;display:block;margin-bottom:.4rem;"></i>
                                Sin notificaciones nuevas
                            </div>
                            <div class="text-center py-3" id="notif-loading">
                                <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="border-top bg-white text-center py-2">
                            <a href="{{ route('notificaciones.index') }}"
                               style="font-size:.78rem;color:#6c5dd3;text-decoration:none;font-weight:600;">
                                Ver todas las notificaciones <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
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

    {{-- ══ SISTEMA DE NOTIFICACIONES ══════════════════════════════════ --}}
    <script>
    (function () {
        const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
        const baseUrl = '{{ url("/notificaciones") }}';

        // ── Plantilla de ítem ─────────────────────────────────────────
        function itemHtml(n) {
            const colorMap = {
                warning : { bg: '#fffbeb', border: '#fbbf24', txt: '#92400e', icon: '#d97706' },
                danger  : { bg: '#fff1f2', border: '#fca5a5', txt: '#9f1239', icon: '#ef4444' },
                primary : { bg: '#eff6ff', border: '#93c5fd', txt: '#1e3a8a', icon: '#3b82f6' },
                success : { bg: '#f0fdf4', border: '#86efac', txt: '#14532d', icon: '#22c55e' },
            };
            const c = colorMap[n.color] ?? colorMap.primary;
            return `
            <div class="notif-item d-flex align-items-start gap-2 px-3 py-2"
                 data-id="${n.id}"
                 style="border-bottom:1px solid #e9ecef;background:${c.bg};cursor:pointer;transition:background .15s;"
                 onclick="abrirNotif(event, '${n.id}', ${n.proforma_id ?? 'null'})">
                <div style="width:34px;height:34px;flex-shrink:0;border-radius:8px;background:white;border:1px solid ${c.border};
                            display:flex;align-items:center;justify-content:center;margin-top:2px;">
                    <i class="bi ${n.icono}" style="color:${c.icon};font-size:.95rem;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.78rem;font-weight:700;color:${c.txt};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        ${n.titulo}
                    </div>
                    <div style="font-size:.73rem;color:#374151;line-height:1.35;margin-top:1px;">
                        ${n.mensaje}
                    </div>
                    <div style="font-size:.67rem;color:#6b7280;margin-top:3px;">${n.tiempo}</div>
                </div>
                <button onclick="eliminarNotif(event,'${n.id}')"
                        title="Descartar"
                        style="background:none;border:none;color:#9ca3af;padding:0;font-size:.8rem;flex-shrink:0;line-height:1;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>`;
        }

        // ── Cargar notificaciones ─────────────────────────────────────
        function cargar() {
            fetch(`${baseUrl}/recientes`, { headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(({ notificaciones, total_no_leidas }) => {
                    const list    = document.getElementById('notif-list');
                    const loading = document.getElementById('notif-loading');
                    const empty   = document.getElementById('notif-empty');
                    const badge   = document.getElementById('notif-badge');

                    if (loading) loading.style.display = 'none';

                    // Badge
                    if (total_no_leidas > 0) {
                        badge.textContent = total_no_leidas > 99 ? '99+' : total_no_leidas;
                        badge.style.display = '';
                    } else {
                        badge.style.display = 'none';
                    }

                    // Lista
                    const items = notificaciones.map(itemHtml).join('');
                    // Remover ítems viejos (mantener #notif-loading y #notif-empty)
                    list.querySelectorAll('.notif-item').forEach(el => el.remove());

                    if (notificaciones.length === 0) {
                        empty.style.display = '';
                        list.prepend(empty);
                    } else {
                        empty.style.display = 'none';
                        list.insertAdjacentHTML('afterbegin', items);
                    }
                })
                .catch(() => {});
        }

        // ── Abrir notificación → marcar leída y redirigir ─────────────
        window.abrirNotif = function (evt, id, proformaId) {
            if (evt.target.closest('button')) return; // clic en X → no redirigir
            fetch(`${baseUrl}/${id}/leer`, {
                method : 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            }).then(() => {
                if (proformaId) {
                    window.location.href = `{{ url('/proformas') }}/${proformaId}`;
                } else {
                    window.location.href = `${baseUrl}`;
                }
            });
        };

        // ── Eliminar un ítem ──────────────────────────────────────────
        window.eliminarNotif = function (evt, id) {
            evt.stopPropagation();
            fetch(`${baseUrl}/${id}`, {
                method : 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            }).then(() => {
                document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                cargar(); // refresca badge
            });
        };

        // ── Marcar todas leídas ───────────────────────────────────────
        window.marcarTodasLeidas = function (evt) {
            evt.stopPropagation();
            fetch(`${baseUrl}/leer-todas`, {
                method : 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            }).then(() => cargar());
        };

        // ── Cargar al abrir el dropdown ───────────────────────────────
        document.getElementById('notifBtn')?.addEventListener('show.bs.dropdown', cargar);

        // ── Polling cada 60 s ─────────────────────────────────────────
        cargar();
        setInterval(cargar, 60000);
    })();
    </script>
</body>
</html>
