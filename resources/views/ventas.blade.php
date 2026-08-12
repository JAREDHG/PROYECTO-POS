@extends('layouts.app')

@section('content')
<div x-data="{
    token: localStorage.getItem('auth_token') || null,
    ventas: [],
    cargando: false,
    error: '',
    busqueda: '',
    
    // Estado para ver el detalle de un ticket específico
    ticketSeleccionado: null,
    modalTicket: false,

    // 🕐 FUNCIÓN PARA FORMATEAR LA FECHA A HORA LOCAL
    formatFecha(fechaIso) {
        if (!fechaIso) return '';
        
        // Convertimos la cadena ISO a objeto Date real
        const date = new Date(fechaIso);
        
        // Si no es fecha válida, la regresamos tal cual
        if (isNaN(date.getTime())) return fechaIso;

        // Formato legible en español de México (ej. 11/08/2026, 11:44 p. m.)
        return date.toLocaleString('es-MX', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    },

    async cargarVentas() {
        this.cargando = true;
        this.error = '';

        try {
            const headers = { Accept: 'application/json' };
            if (this.token) {
                headers.Authorization = `Bearer ${this.token}`;
            } else {
                throw new Error('No hay sesión activa.');
            }

            const response = await fetch('/api/sales', { headers });

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Sesión expirada. Por favor inicie sesión nuevamente.');
                }
                throw new Error('No se pudo obtener el historial de ventas desde la API.');
            }

            const data = await response.json();
            
            // Blindaje: Aseguramos que 'listaVentas' sea siempre un arreglo
            const listaVentas = Array.isArray(data) ? data : (data.data || []);

            // Adaptamos los datos recibidos del backend
            this.ventas = listaVentas.map(venta => ({
                id: venta.id,
                folio: venta.ticket_number,
                total: Number(venta.total),
                metodo: venta.payment_method,
                fecha: venta.created_at,
                usuario: venta.user ? venta.user.name : 'Cajero',
                items: venta.items ? venta.items.map(item => ({
                    id: item.id,
                    nombre: item.product ? item.product.name : 'Producto',
                    cantidad: item.quantity,
                    precio: Number(item.price)
                })) : []
            }));

        } catch (err) {
            this.error = err.message;
            console.error('Error al cargar ventas:', err);
        } finally {
            this.cargando = false;
        }
    },

    get ventasFiltradas() {
        if (!this.busqueda) return this.ventas;
        return this.ventas.filter(v => 
            v.folio.toLowerCase().includes(this.busqueda.toLowerCase()) ||
            v.usuario.toLowerCase().includes(this.busqueda.toLowerCase())
        );
    },

    verTicket(venta) {
        this.ticketSeleccionado = venta;
        this.modalTicket = true;
    }
}" x-init="if(token) cargarVentas()" class="h-full flex flex-col space-y-6">

    <!-- BARRA DE ACCESOS Y BUSCADOR -->
    <div class="flex justify-between items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">🔍</span>
            <input type="text"
                x-model="busqueda"
                placeholder="Buscar por folio de ticket o cajero..."
                class="w-full bg-[#1f2937] text-white placeholder-gray-500 text-sm rounded-xl pl-11 pr-4 py-3 border border-gray-700 focus:outline-none focus:border-emerald-500 transition" />
        </div>
        <button @click="cargarVentas()"
            class="bg-[#1f2937] border border-gray-700 hover:border-emerald-500 text-gray-300 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition flex items-center gap-2">
            <span>🔄</span> Actualizar Lista
        </button>
    </div>

    <!-- TABLA DE HISTORIAL DE VENTAS -->
    <div class="flex-1 bg-[#1f2937] border border-gray-700 rounded-2xl overflow-hidden flex flex-col shadow-xl">
        <div class="overflow-y-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#1a222f] border-b border-gray-700 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="p-4">Folio Ticket</th>
                        <th class="p-4">Fecha y Hora</th>
                        <th class="p-4">Cajero</th>
                        <th class="p-4">Método de Pago</th>
                        <th class="p-4 text-right">Total</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-sm">
                    <!-- Estado de Carga -->
                    <template x-if="cargando">
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">Cargando historial de ventas...</td>
                        </tr>
                    </template>

                    <!-- Estado de Error -->
                    <template x-if="!cargando && error">
                        <tr>
                            <td colspan="6" class="text-center py-12 text-red-400" x-text="error"></td>
                        </tr>
                    </template>

                    <!-- Sin Resultados -->
                    <template x-if="!cargando && !error && ventasFiltradas.length === 0">
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">No se encontraron registros de ventas.</td>
                        </tr>
                    </template>

                    <!-- Listado de Ventas -->
                    <template x-for="venta in ventasFiltradas" :key="venta.id">
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="p-4 font-mono font-bold text-emerald-400" x-text="venta.folio"></td>
                            <!-- 🕒 FECHA FORMATEADA EN LA TABLA -->
                            <td class="p-4 text-gray-300 text-xs font-mono" x-text="formatFecha(venta.fecha)"></td>
                            <td class="p-4 text-gray-300" x-text="venta.usuario"></td>
                            <td class="p-4">
                                <span class="bg-gray-800 text-gray-300 text-xs px-2.5 py-1 rounded-md uppercase font-semibold" x-text="venta.metodo"></span>
                            </td>
                            <td class="p-4 text-right font-mono font-bold text-white" x-text="'$' + venta.total.toFixed(2)"></td>
                            <td class="p-4 text-center">
                                <button @click="verTicket(venta)" class="bg-emerald-600/20 hover:bg-emerald-600 text-emerald-400 hover:text-black text-xs font-bold px-3 py-1.5 rounded-lg transition">
                                    Ver Ticket 🧾
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PARA REIMPRIMIR / VER TICKET PASADO -->
    <div x-show="modalTicket"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto"
        style="display: none;">

        <div class="bg-white text-gray-900 rounded-3xl p-6 sm:p-7 w-full max-w-sm shadow-2xl font-mono relative border border-gray-200 space-y-4">
            <div class="text-center space-y-1">
                <h2 class="text-lg font-black tracking-tight text-black">Abarrotes El Surtidor</h2>
                <p class="text-[11px] text-gray-500 leading-tight">Av. Hidalgo 45, Col. Centro<br>Tel. (55) 2345-6789</p>
            </div>
            <div class="border-b border-dashed border-gray-300 my-2"></div>
            <div class="text-[11px] text-gray-600 space-y-1">
                <div class="flex justify-between font-bold text-black">
                    <span>FOLIO:</span>
                    <span x-text="ticketSeleccionado ? ticketSeleccionado.folio : ''"></span>
                </div>
                <!-- 🕒 FECHA FORMATEADA DENTRO DEL TICKET -->
                <div class="flex justify-between">
                    <span>FECHA:</span>
                    <span x-text="formatFecha(ticketSeleccionado ? ticketSeleccionado.fecha : '')"></span>
                </div>
                <div class="flex justify-between">
                    <span>CAJERO:</span>
                    <span x-text="ticketSeleccionado ? ticketSeleccionado.usuario : ''"></span>
                </div>
            </div>
            <div class="border-b border-dashed border-gray-300 my-2"></div>
            <div>
                <div class="grid grid-cols-12 text-[10px] font-bold text-gray-400 uppercase mb-2">
                    <span class="col-span-7">PRODUCTO</span>
                    <span class="col-span-2 text-center">QTY</span>
                    <span class="col-span-3 text-right">TOTAL</span>
                </div>
                <div class="space-y-1.5 text-xs text-black font-semibold max-h-40 overflow-y-auto">
                    <template x-if="ticketSeleccionado">
                        <template x-for="item in ticketSeleccionado.items" :key="item.id">
                            <div class="grid grid-cols-12 items-center">
                                <span class="col-span-7 truncate" x-text="item.nombre"></span>
                                <span class="col-span-2 text-center font-normal text-gray-600" x-text="item.cantidad"></span>
                                <span class="col-span-3 text-right font-bold" x-text="'$' + (item.precio * item.cantidad).toFixed(2)"></span>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
            <div class="border-b border-dashed border-gray-300 my-2"></div>
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between items-center font-black text-base text-black">
                    <span>TOTAL</span>
                    <span class="text-xl" x-text="ticketSeleccionado ? '$' + ticketSeleccionado.total.toFixed(2) : ''"></span>
                </div>
                <div class="flex justify-between text-gray-600 text-[11px] pt-1">
                    <span>MÉTODO:</span>
                    <span class="font-bold text-black uppercase" x-text="ticketSeleccionado ? ticketSeleccionado.metodo : ''"></span>
                </div>
            </div>
            <div class="border-b border-dashed border-gray-300 my-2"></div>
            <div class="text-center text-xs font-bold text-gray-700 pt-1">
                <p>¡Gracias por su compra!</p>
            </div>
        </div>

        <div class="w-full max-w-sm mt-4 flex gap-3">
            <button @click="modalTicket = false"
                class="w-full bg-gray-700 hover:bg-gray-600 text-white font-extrabold py-3.5 px-4 rounded-xl text-sm transition tracking-wide shadow-xl">
                Cerrar Ventana
            </button>
        </div>
    </div>
</div>
@endsection