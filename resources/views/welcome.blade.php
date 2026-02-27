<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Sistema de Proformas') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logoAura.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/logoAura.png') }}">
        <!-- Tailwind CSS CDN (para que funcione sin compilar) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen flex items-center justify-center bg-gray-900">

            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <!-- Si tienes la imagen, úsala. Si no, usamos un gradiente -->
                <div class="w-full h-full bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-black/80"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 w-full max-w-4xl px-6 text-center">

                <!-- Icon -->
                <div class="mb-8 inline-flex items-center justify-center p-4 bg-orange-600 rounded-2xl shadow-lg shadow-orange-500/20">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 tracking-tight">
                    SISTEMA DE <span class="text-orange-500">PROFORMAS</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Gestión inteligente de cotizaciones, clientes y productos. Accede al sistema para generar proformas profesionales.
                </p>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <!-- Usuario ya autenticado - Botón al Dashboard -->
                        <a href="{{ route('dashboard') }}"
                           class="px-10 py-4 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition duration-300 shadow-xl shadow-orange-600/30 text-lg uppercase tracking-wider">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Ir al Dashboard
                        </a>
                    @else
                        <!-- Usuario no autenticado - Botón de Login -->
                        <a href="{{ route('login') }}"
                           class="px-10 py-4 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition duration-300 shadow-xl shadow-orange-600/30 text-lg uppercase tracking-wider">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Acceder al Sistema
                        </a>
                    @endauth
                </div>

                <!-- Features -->
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg">
                        <div class="text-orange-500 mb-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold mb-2">Gestión de Proformas</h3>
                        <p class="text-gray-400 text-sm">Crea y administra cotizaciones profesionales con facilidad.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg">
                        <div class="text-orange-500 mb-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold mb-2">Control de Clientes</h3>
                        <p class="text-gray-400 text-sm">Administra tu cartera de clientes y contactos de forma eficiente.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg">
                        <div class="text-orange-500 mb-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold mb-2">Inventario</h3>
                        <p class="text-gray-400 text-sm">Controla productos, stock y proveedores en tiempo real.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-20 text-gray-500 text-sm border-t border-white/10 pt-8">
                    <p class="mb-2">
                        Desarrollado por <a href="https://web.whatsapp.com/send?phone=51933611628&text=*%C2%A1Hola+Core+Consulting%21*%0ADeseo+informaci%C3%B3n+sobre+un+proyecto+de+software.%0A%0A%C2%A1Gracias!" target="_blank" class="text-orange-500 hover:underline">Core Consulting</a>

                    </p>
                    &copy; {{ date('Y') }} Sistema de Gestión de Proformas - Todos los derechos reservados.
                </div>
            </div>
        </div>
    </body>
</html>
