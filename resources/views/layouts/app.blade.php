<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>VITA - @yield('title', 'Dashboard')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema ERP VITA para gestión empresarial">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; }</style>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="flex flex-col bg-[#FAFAFA] min-h-screen text-gray-700">

    <noscript>
        <div class="bg-red-600 text-white text-center p-2">
            ⚠️ Por favor, habilita JavaScript para el correcto funcionamiento del sistema.
        </div>
    </noscript>

    <div class="flex flex-1">

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-[#1F1F1F] text-[#E0E0E0] flex flex-col justify-between py-8 px-6 shadow-md transition-transform duration-300 ease-in-out min-h-screen">

            <div>
                {{-- Logo + texto VITA --}}
                <div class="flex items-center gap-3 mb-10 px-2">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo VITA" class="h-10 w-auto object-contain" />
                    <h2 class="text-3xl font-bold tracking-wide text-white select-none">VITA</h2>
                </div>

                <nav class="space-y-5">
                    <a href="{{ url('/dashboard') }}"
                       class="flex items-center gap-4 p-3 rounded-lg transition-colors duration-200 {{ request()->is('dashboard') ? 'bg-[#B497BD] text-white' : 'hover:bg-[#B497BD] hover:text-white text-[#D1D5DB]' }}">
                        <!-- Home icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M3 12l9-9 9 9" />
                          <path d="M9 21V9h6v12" />
                        </svg>
                        <span class="font-semibold">Home</span>
                    </a>

                    <a href="/transacciones"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200">
                        <!-- Repeat / Transactions icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <polyline points="17 1 21 5 17 9" />
                          <line x1="21" y1="5" x2="9" y2="5" />
                          <polyline points="7 23 3 19 7 15" />
                          <line x1="3" y1="19" x2="15" y2="19" />
                        </svg>
                        <span>Transacciones</span>
                    </a>

                    <a href="/inventarios"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200">
                        <!-- Box / Inventory icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                          <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                          <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg>
                        <span>Kardex</span>
                    </a>

                    <a href="/compras"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200">
                        <!-- Clipboard / Purchases icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M9 12h6" />
                          <path d="M9 16h6" />
                          <path d="M9 8h6" />
                          <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
                          <path d="M9 2h6v4H9z" />
                        </svg>
                        <span>Compras</span>
                    </a>

                    <a href="{{ route('config.usuarios.index') }}"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200
                              {{ request()->is('usuarios*') ? 'bg-[#B497BD] text-white' : '' }}">
                        <!-- Icono de usuario -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M17 21v-2a4 4 0 0 0-3-3.87" />
                          <path d="M9 21v-2a4 4 0 0 1 3-3.87" />
                          <circle cx="12" cy="7" r="4" />
                        </svg>
                        <span>Usuarios</span>
                    </a>

                    <a href="{{ route('analisis.inventario') }}"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200">
                        <!-- Bar chart / Analysis icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <line x1="12" y1="20" x2="12" y2="10" />
                          <line x1="18" y1="20" x2="18" y2="4" />
                          <line x1="6" y1="20" x2="6" y2="16" />
                        </svg>
                        <span>Análisis</span>
                    </a>

                    <a href="/existencias"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200">
                        <!-- Map pin / Inventory icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M12 11c1.656 0 3-1.343 3-3s-1.344-3-3-3-3 1.343-3 3 1.344 3 3 3z" />
                          <path d="M12 22s8-4.5 8-10a8 8 0 1 0-16 0c0 5.5 8 10 8 10z" />
                        </svg>
                        <span>Inventarios</span>
                    </a>

                    <a href="{{ route('config.index') }}"
                       class="flex items-center gap-4 p-3 rounded-lg hover:bg-[#B497BD] hover:text-white text-[#D1D5DB] transition-colors duration-200">
                        <!-- Gear / Settings icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <circle cx="12" cy="12" r="3" />
                          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51z" />
                        </svg>
                        <span>Configuración</span>
                    </a>
                </nav>
            </div>

            <footer class="text-center text-xs text-gray-400 opacity-70 tracking-wide">© 2025 VITA ERP</footer>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-10 max-w-7xl mx-auto min-h-screen overflow-auto">

            <!-- Encabezado superior (user info) -->
            <div class="flex justify-between items-center mb-6">
                <!-- Botón toggle sidebar -->
                <button onclick="toggleSidebar()" 
                        class="px-4 py-2 border border-[#B497BD] rounded-md text-[#2C2C2C] hover:bg-[#B497BD] hover:text-white transition font-semibold">
                    ☰
                </button>

                <!-- Menú de usuario -->
                <div class="relative" id="userMenu">
                    <button id="userMenuButton" class="flex items-center gap-3 focus:outline-none" type="button" aria-expanded="false" aria-haspopup="true">
                        <div class="w-10 h-10 rounded-full bg-[#B497BD] flex items-center justify-center text-white font-semibold uppercase">
                            {{ strtoupper(substr(session('name_user'), 0, 1)) }}
                        </div>
                        <span class="text-gray-700 font-semibold">{{ session('name_user') }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                        <div class="px-4 py-2 text-gray-500 text-xs border-b border-gray-200">
                            {{ session('rol_user') }}
                        </div>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#B497BD] hover:text-white text-sm" onclick="closeDropdown()">
                            Perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white text-sm">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    @yield('scripts')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('absolute');
            sidebar.classList.toggle('z-50');
            sidebar.classList.toggle('bg-[#1F1F1F]');
        }

        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');

        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            if (userDropdown.classList.contains('hidden')) {
                userDropdown.classList.remove('hidden');
                document.addEventListener('click', outsideClickListener);
                userMenuButton.setAttribute('aria-expanded', 'true');
            } else {
                userDropdown.classList.add('hidden');
                document.removeEventListener('click', outsideClickListener);
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });

        function outsideClickListener(event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
                document.removeEventListener('click', outsideClickListener);
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        }

        function closeDropdown() {
            userDropdown.classList.add('hidden');
            document.removeEventListener('click', outsideClickListener);
            userMenuButton.setAttribute('aria-expanded', 'false');
        }
    </script>
</body>
</html>
