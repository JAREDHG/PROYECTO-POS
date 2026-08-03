<!DOCTYPE html>
<html lang="es" class="h-full bg-[#111827]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abarrotes El Surtidor - POS</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js (Para componentes dinámicos y el reloj en tiempo real) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans text-gray-200 antialiased flex">

    <!-- SIDEBAR (Menú Lateral izquierdo) -->
    <aside class="w-64 bg-[#1f2937] flex flex-col justify-between border-r border-gray-700">
        <div>
            <!-- Logo / Nombre de la Tienda -->
            <div class="p-6 flex items-center gap-3 border-b border-gray-700">
                <div class="bg-emerald-500 p-2 rounded-lg text-black font-bold text-xl">🛒</div>
                <div>
                    <h1 class="font-bold text-white tracking-wide leading-none text-sm">ABARROTES</h1>
                    <span class="text-xs text-emerald-400 font-medium">EL SURTIDOR</span>
                </div>
            </div>

            <!-- Enlaces de Navegación Inteligentes -->
            <nav class="p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ url('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ Request::is('dashboard') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span>📊</span> Dashboard
                </a>
                
                <!-- Punto de Venta -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ Request::is('/') || Request::is('pos') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span>💻</span> Punto de Venta
                </a>
                
                <!-- Inventario -->
                <a href="{{ url('inventario') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ Request::is('inventario') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span>📦</span> Inventario
                </a>
                
                <!-- Reportes -->
                <a href="{{ url('reportes') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ Request::is('reportes') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span>📈</span> Reportes
                </a>

                <!-- Historial de Ventas -->
                <a href="{{ url('ventas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ Request::is('ventas') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span>🧾</span> Historial de Ventas
                </a>
            </nav>
        </div>

        <!-- Información del Usuario y Cierre de Sesión en la Barra Lateral -->
        <div class="p-4 border-t border-gray-700 bg-[#1a222f] flex items-center justify-between" x-data="{
            nombreUsuario: localStorage.getItem('user_name') || 'Cajero',
            rolUsuario: localStorage.getItem('user_role') || 'Cajero - Turno A',
            cerrarSesion() {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_name');
                localStorage.removeItem('user_role');
                window.location.href = '/';
            }
        }">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center font-bold text-gray-900 shrink-0" 
                     x-text="nombreUsuario.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()">
                </div>
                <div class="truncate">
                    <p class="text-sm font-semibold text-white leading-none truncate" x-text="nombreUsuario"></p>
                    <span class="text-xs text-gray-400 truncate block mt-0.5" x-text="rolUsuario"></span>
                </div>
            </div>
            <!-- Botón de Logout -->
            <button @click="cerrarSesion()" 
                    title="Cerrar Sesión"
                    class="text-gray-400 hover:text-red-400 hover:bg-red-500/10 p-2 rounded-xl transition shrink-0">
                🚪
            </button>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Barra Superior Dinámica -->
        <header class="h-16 bg-[#1f2937] border-b border-gray-700 px-8 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">
                {{ Request::is('dashboard') ? 'Dashboard de Estadísticas' : '' }}
                {{ Request::is('/') || Request::is('pos') ? 'Punto de Venta' : '' }}
                {{ Request::is('inventario') ? 'Gestión de Inventario' : '' }}
                {{ Request::is('ventas') ? 'Historial de Ventas' : '' }}
                {{ Request::is('reportes') ? 'Corte de Caja y Reportes' : '' }}
            </h2>

            <!-- RELOJ Y FECHA REAL Y EN TIEMPO REAL -->
            <div x-data="{
                    fechaTexto: '',
                    horaTexto: '',
                    actualizarReloj() {
                        const ahora = new Date();
                        
                        // Formato de Fecha en Español (Ej: Lunes, 20 de Julio de 2026)
                        const opcionesFecha = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
                        let f = ahora.toLocaleDateString('es-ES', opcionesFecha);
                        this.fechaTexto = f.charAt(0).toUpperCase() + f.slice(1);

                        // Formato de Hora en 12h (Ej: 09:13:05 p.m.)
                        let h = ahora.getHours();
                        const m = String(ahora.getMinutes()).padStart(2, '0');
                        const s = String(ahora.getSeconds()).padStart(2, '0');
                        const ampm = h >= 12 ? 'p.m.' : 'a.m.';
                        h = h % 12 || 12;
                        
                        this.horaTexto = `${String(h).padStart(2, '0')}:${m}:${s} ${ampm}`;
                    }
                }" 
                x-init="actualizarReloj(); setInterval(() => actualizarReloj(), 1000)" 
                class="text-sm text-gray-400 font-medium flex items-center gap-2">
                <span x-text="fechaTexto"></span>
                <span>—</span>
                <span class="text-white font-mono font-bold" x-text="horaTexto"></span>
            </div>
        </header>

        <!-- Espacio para las Vistas dinámicas -->
        <div class="flex-1 p-6 overflow-y-auto bg-[#111827]">
            @yield('content')
        </div>
    </main>

</body>
</html>