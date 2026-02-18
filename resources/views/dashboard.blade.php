@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <!-- User Greeting Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">Dashboard</h1>
                        <p class="text-muted">Bienvenido al Sistema de Gestión de Proformas</p>
                    </div>
                    <div class="text-end">
                        <h4 class="mb-0">Felicidades {{ Auth::user()->name }}!</h4>
                        <p class="text-muted mb-0">Has realizado un 72% de tus Lead esta semana.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Meta</h6>
                                <h3 class="mb-0">$12000</h3>
                            </div>
                            <div class="bg-light p-2 rounded-circle">
                                <i class="bi bi-target text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Cotizado</h6>
                                <h3 class="mb-0">$3000</h3>
                            </div>
                            <div class="bg-light p-2 rounded-circle">
                                <i class="bi bi-currency-dollar text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Proformas</h6>
                                <h3 class="mb-0">{{ App\Models\Proforma::count() }}</h3>
                            </div>
                            <div class="bg-light p-2 rounded-circle">
                                <i class="bi bi-file-earmark-text text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Statistics Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Estadísticas de Proformas</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartPeriod" data-bs-toggle="dropdown">
                                Últimos 7 días
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="chartPeriod">
                                <li><a class="dropdown-item" href="#">Últimos 7 días</a></li>
                                <li><a class="dropdown-item" href="#">Este mes</a></li>
                                <li><a class="dropdown-item" href="#">Este año</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <!-- Placeholder for chart - would be replaced with actual chart library -->
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-bar-chart-line" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Categorías de Proformas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-laptop text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Electrónicos</h6>
                                <p class="text-muted mb-0">82.5k</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-heart text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Moda</h6>
                                <p class="text-muted mb-0">23.8k</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-house text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Decoración</h6>
                                <p class="text-muted mb-0">849k</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-sport text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Deportes</h6>
                                <p class="text-muted mb-0">99</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Notificaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center">
                                <div class="bg-light p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-credit-card text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Pago recibido</h6>
                                    <p class="text-muted mb-0">+82.6 USD</p>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="bg-light p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-wallet text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Wallet</h6>
                                    <p class="text-muted mb-0">+270.69 USD</p>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="bg-light p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-arrow-clock text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Transferencia</h6>
                                    <p class="text-muted mb-0">+637.91 USD</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Proformas Section -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Últimas Proformas Creadas</h5>
                        <a href="{{ route('proformas.index') }}" class="text-decoration-none text-primary">
                            Ver todas <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cliente</th>
                                        <th>Creado por</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ultimasProformas = App\Models\Proforma::with(['cliente', 'user'])
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    @endphp

                                    @if($ultimasProformas->count() > 0)
                                        @foreach($ultimasProformas as $proforma)
                                            <tr>
                                                <td><code>{{ Str::limit($proforma->codigo, 13) }}</code></td>
                                                <td>{{ $proforma->cliente->razon ?? 'N/A' }}</td>
                                                <td>{{ $proforma->user->name ?? 'N/A' }}</td>
                                                <td>{{ $proforma->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-success">Completada</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('proformas.show', $proforma) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <p class="text-muted mb-0">No hay proformas registradas aún.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this script to initialize charts when available -->
    @section('scripts')
        <script>
            // This is where you would initialize your chart libraries
            // For example:
            // new Chart(document.getElementById('salesChart'), { ... });
        </script>
    @endsection
@endsection
