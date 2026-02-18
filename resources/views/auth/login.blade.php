<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logoAura.png') }}">
    <title>Login - Sistema de Proformas</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }

        .login-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-image i {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        .login-form {
            padding: 3rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .info-badge {
            background-color: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 5px;
        }

        .info-badge h6 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .info-badge p {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="login-container">
                    <div class="row g-0">
                        <!-- Lado izquierdo - Imagen/Branding -->
                        <div class="col-md-5 login-image">
                            <i class="bi bi-file-earmark-text"></i>
                            <h2 class="mb-3">Sistema de Proformas</h2>
                            <p class="mb-0">
                                Gestión integral de cotizaciones, clientes y productos
                            </p>
                        </div>

                        <!-- Lado derecho - Formulario -->
                        <div class="col-md-7 login-form">
                            <h3 class="mb-4">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </h3>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Información de usuarios de prueba -->
                            <div class="info-badge">
                                <h6><i class="bi bi-info-circle"></i> Usuarios de Prueba</h6>
                                <p><strong>Admin:</strong> admin@proformas.com / password</p>
                                <p><strong>Vendedor:</strong> vendedor@proformas.com / password</p>
                                <p><strong>Almacén:</strong> almacen@proformas.com / password</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Correo Electrónico
                                    </label>
                                    <input id="email"
                                           type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus
                                           autocomplete="username"
                                           placeholder="tu@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock"></i> Contraseña
                                    </label>
                                    <input id="password"
                                           type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           required
                                           autocomplete="current-password"
                                           placeholder="••••••••">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="remember_me"
                                           name="remember">
                                    <label class="form-check-label" for="remember_me">
                                        Recordarme
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-login">
                                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                    </button>
                                </div>

                                <!-- Forgot Password -->
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                                            <i class="bi bi-question-circle"></i> ¿Olvidaste tu contraseña?
                                        </a>
                                    </div>
                                @endif
                            </form>

                            <!-- Volver a inicio -->
                            <div class="text-center mt-4">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="bi bi-arrow-left"></i> Volver al inicio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
