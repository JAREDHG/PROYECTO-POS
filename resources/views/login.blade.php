<!DOCTYPE html>
<html lang="es" class="h-full bg-[#0d131d]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al POS - Abarrotes El Surtidor</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans text-gray-200 antialiased flex items-center justify-center p-4">

    <div x-data="{
        email: 'admin@pos.com',
        password: 'password123',
        errorMessage: '',
        cargando: false,

        async login() {
            this.errorMessage = '';
            this.cargando = true;

            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Credenciales incorrectas'));
                }

                // Guardamos la sesión en el navegador
                localStorage.setItem('auth_token', data.access_token);
                localStorage.setItem('user_name', data.user.name);
                localStorage.setItem('user_email', data.user.email);
                
                // Guardamos los roles devueltos por Spatie
                const roles = data.roles || [];
                localStorage.setItem('user_roles', JSON.stringify(roles));

                const primerRol = roles.length > 0 ? roles[0] : (data.user.email.includes('admin') ? 'admin' : 'cashier');
                localStorage.setItem('user_role', primerRol);

                // Redireccionamos al módulo del POS
                window.location.href = '/pos';

            } catch (err) {
                this.errorMessage = err.message;
            } finally {
                this.cargando = false;
            }
        }
    }" class="w-full max-w-md bg-[#161f2c] border border-gray-800 rounded-2xl p-8 shadow-2xl">

        <!-- LOGO Y TÍTULO -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                🏪
            </div>
            <h1 class="text-2xl font-black text-white tracking-wide">Acceso al POS</h1>
            <p class="text-xs text-gray-400 mt-1">Abarrotes El Surtidor</p>
        </div>

        <!-- ALERTA DE ERROR -->
        <template x-if="errorMessage">
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs text-center font-medium" x-text="errorMessage"></div>
        </template>

        <!-- FORMULARIO -->
        <form @submit.prevent="login()" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                <input type="email" 
                       x-model="email" 
                       required 
                       placeholder="admin@pos.com" 
                       class="w-full bg-[#0d131d] border border-gray-700 focus:border-emerald-500 text-white rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Contraseña</label>
                <input type="password" 
                       x-model="password" 
                       required 
                       placeholder="••••••••" 
                       class="w-full bg-[#0d131d] border border-gray-700 focus:border-emerald-500 text-white rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>

            <button type="submit" 
                    :disabled="cargando" 
                    class="w-full bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-bold py-3.5 rounded-xl transition shadow-lg shadow-emerald-500/10 flex items-center justify-center gap-2 text-sm disabled:opacity-50">
                <span x-show="!cargando">🔐 INICIAR SESIÓN</span>
                <span x-show="cargando">Cargando...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-800 text-center text-[11px] text-gray-500">
            Sistema de Punto de Venta v1.0
        </div>
    </div>

</body>
</html>