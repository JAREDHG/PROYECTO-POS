@extends('layouts.app')

@section('content')
<!-- CONTENEDOR PRINCIPAL CORTE DE CAJA / REPORTES -->
<div x-data="reportesComponent()" x-init="cargarVentas()" class="space-y-6">

    <!-- ENCABEZADO CORTE DE CAJA -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Corte de Caja</h1>
            <p class="text-xs text-gray-400 mt-0.5">RF04 · Trazabilidad y auditoría del turno</p>
        </div>

        <div class="flex gap-2">
            <button @click="exportarExcel()" class="bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 border border-emerald-500/30 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                📊 Exportar Excel
            </button>
            <button @click="imprimirCorte()" class="bg-gray-800 hover:bg-gray-700 text-white border border-gray-700 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                🖨️ Imprimir Corte
            </button>
        </div>
    </div>

    <!-- FILTROS Y BÚSQUEDA -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="bg-[#18202c] p-1 rounded-xl border border-gray-800 flex items-center gap-1 overflow-x-auto">
            <template x-for="c in cajerosDisponibles" :key="c">
                <button @click="cajeroFiltro = c" 
                        :class="cajeroFiltro === c ? 'bg-emerald-600/30 text-emerald-400 font-bold border border-emerald-500/30' : 'text-gray-400 hover:text-white'"
                        class="px-4 py-1.5 rounded-lg text-xs transition whitespace-nowrap"
                        x-text="c">
                </button>
            </template>
        </div>

        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-xs">🔍</span>
            <input type="text" 
                   x-model="busqueda" 
                   placeholder="Buscar folio o producto..." 
                   class="w-full bg-[#18202c] border border-gray-800 rounded-xl pl-9 pr-3 py-1.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition">
        </div>
    </div>

    <!-- TARJETAS SUPERIORES DE MÉTRICAS GENERALES -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-5 relative overflow-hidden shadow-xl">
            <div class="w-9 h-9 rounded-xl bg-gray-800/60 text-gray-300 flex items-center justify-center text-sm mb-4">📋</div>
            <div class="text-3xl font-black text-white font-mono" x-text="totalOperaciones"></div>
            <div class="text-xs font-semibold text-gray-400 mt-1">Total Operaciones</div>
            <div class="text-[10px] text-gray-500 mt-0.5">ventas del turno</div>
        </div>

        <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-5 relative overflow-hidden shadow-xl">
            <div class="w-9 h-9 rounded-xl bg-gray-800/60 text-amber-400 flex items-center justify-center text-sm mb-4">💰</div>
            <div class="text-3xl font-black text-white font-mono" x-text="'$' + ingresosTotales.toFixed(2)"></div>
            <div class="text-xs font-semibold text-gray-400 mt-1">Ingresos Totales</div>
            <div class="text-[10px] text-gray-500 mt-0.5">suma de ventas</div>
        </div>

        <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-5 relative overflow-hidden shadow-xl">
            <div class="w-9 h-9 rounded-xl bg-gray-800/60 text-purple-400 flex items-center justify-center text-sm mb-4">📊</div>
            <div class="text-3xl font-black text-white font-mono" x-text="'$' + ticketPromedio.toFixed(2)"></div>
            <div class="text-xs font-semibold text-gray-400 mt-1">Ticket Promedio</div>
            <div class="text-[10px] text-gray-500 mt-0.5">por operación</div>
        </div>
    </div>

    <!-- DESGLOSE POR MÉTODO DE PAGO -->
    <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-6 space-y-4 shadow-xl">
        <h3 class="text-sm font-bold text-white">Desglose por Método de Pago</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#18202c] border border-gray-800 rounded-xl p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-lg">💵</div>
                <div>
                    <div class="text-xs text-gray-400 font-medium">Efectivo</div>
                    <div class="text-xl font-bold text-white font-mono" x-text="'$' + totalEfectivo.toFixed(2)"></div>
                    <div class="text-[10px] text-gray-500 font-mono" x-text="opsEfectivo + ' ops.'"></div>
                </div>
            </div>

            <div class="bg-[#18202c] border border-gray-800 rounded-xl p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-blue-500/10 text-blue-400 text-lg">💳</div>
                <div>
                    <div class="text-xs text-gray-400 font-medium">Tarjeta</div>
                    <div class="text-xl font-bold text-white font-mono" x-text="'$' + totalTarjeta.toFixed(2)"></div>
                    <div class="text-[10px] text-gray-500 font-mono" x-text="opsTarjeta + ' ops.'"></div>
                </div>
            </div>

            <div class="bg-[#18202c] border border-gray-800 rounded-xl p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-purple-500/10 text-purple-400 text-lg">📲</div>
                <div>
                    <div class="text-xs text-gray-400 font-medium">Transferencia</div>
                    <div class="text-xl font-bold text-white font-mono" x-text="'$' + totalTransferencia.toFixed(2)"></div>
                    <div class="text-[10px] text-gray-500 font-mono" x-text="opsTransferencia + ' ops.'"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- REGISTRO DE VENTAS DEL TURNO -->
    <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-gray-800 flex justify-between items-center bg-[#101721]">
            <h3 class="text-sm font-bold text-white">Registro de Ventas del Turno</h3>
            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold px-3 py-1 rounded-lg font-mono"
                  x-text="ventasFiltradas.length + ' operaciones'"></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 text-[11px] font-bold text-gray-400 uppercase tracking-wider bg-[#0f151e]">
                        <th class="py-4 px-6">FOLIO</th>
                        <th class="py-4 px-6">PRODUCTOS</th>
                        <th class="py-4 px-6">TOTAL</th>
                        <th class="py-4 px-6">MÉTODO</th>
                        <th class="py-4 px-6">CAJERO</th>
                        <th class="py-4 px-6">HORA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-xs font-medium text-gray-300">
                    <template x-if="cargando">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Cargando transacciones de la base de datos...</td>
                        </tr>
                    </template>

                    <template x-if="!cargando && error">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-red-400" x-text="error"></td>
                        </tr>
                    </template>

                    <template x-if="!cargando && !error && ventasFiltradas.length === 0">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">No hay ventas registradas para este filtro.</td>
                        </tr>
                    </template>

                    <template x-for="v in ventasFiltradas" :key="v.id || v.folio">
                        <tr class="hover:bg-gray-800/30 transition">
                            <td class="py-4 px-6 font-mono font-bold text-emerald-400" x-text="v.folio"></td>
                            <td class="py-4 px-6 text-gray-300 max-w-xs truncate" x-text="v.productos"></td>
                            <td class="py-4 px-6 font-mono font-black text-white" x-text="'$' + (v.total || 0).toFixed(2)"></td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-300">
                                    <span x-text="v.iconoMetodo"></span>
                                    <span x-text="v.metodo"></span>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-300" x-text="v.cajero"></td>
                            <td class="py-4 px-6 font-mono text-gray-400" x-text="v.hora"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-[#0f151e] border-t border-gray-800 flex justify-between items-center text-xs">
            <span class="text-gray-500 font-mono">Corte en tiempo real</span>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 font-bold uppercase tracking-wider">Total:</span>
                <span class="text-lg font-black text-emerald-400 font-mono" x-text="'$' + ingresosTotales.toFixed(2)"></span>
            </div>
        </div>
    </div>
</div>

<!-- LÓGICA JAVASCRIPT SEPARADA DE LOS ATRIBUTOS HTML -->
<script>
function reportesComponent() {
    return {
        cajeroFiltro: 'Todos',
        busqueda: '',
        ventas: [],
        cargando: true,
        error: '',

        async cargarVentas() {
            const tokenActual = localStorage.getItem('auth_token');
            if (!tokenActual) {
                window.location.href = '/';
                return;
            }

            this.cargando = true;
            this.error = '';

            try {
                const response = await fetch('/api/sales', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + tokenActual
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        localStorage.clear();
                        window.location.href = '/';
                        return;
                    }
                    throw new Error('Error al conectar con el servidor.');
                }

                const jsonResponse = await response.json();
                const ventasRaw = jsonResponse.data || (Array.isArray(jsonResponse) ? jsonResponse : []);

                this.ventas = ventasRaw.map(v => {
                    let horaStr = 'Reciente';
                    if (v && v.created_at) {
                        try {
                            const fechaObj = new Date(v.created_at);
                            horaStr = fechaObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }).toLowerCase();
                        } catch(e){}
                    }

                    let resumenProductos = 'Venta de productos';
                    if (v && v.items && Array.isArray(v.items) && v.items.length > 0) {
                        resumenProductos = v.items.map(i => {
                            const cant = i.quantity || 1;
                            const nom = (i.product && i.product.name) ? i.product.name : (i.name || 'Producto');
                            return cant + '× ' + nom;
                        }).join(', ');
                    }

                    let metodoRaw = String((v && v.payment_method) ? v.payment_method : 'efectivo').toLowerCase();
                    let metodoFormatted = metodoRaw.charAt(0).toUpperCase() + metodoRaw.slice(1);
                    let icono = '💵';
                    if (metodoRaw === 'tarjeta') icono = '💳';
                    if (metodoRaw === 'transferencia') icono = '📲';

                    return {
                        id: (v && v.id) ? v.id : Math.random(),
                        folio: (v && v.ticket_number) ? v.ticket_number : ('TK-' + String((v && v.id) ? v.id : '0').padStart(6, '0')),
                        productos: resumenProductos,
                        total: Number((v && v.total) ? v.total : 0),
                        metodo: metodoFormatted,
                        iconoMetodo: icono,
                        cajero: (v && v.user && v.user.name) ? v.user.name : 'Cajero General',
                        hora: horaStr
                    };
                });

            } catch (err) {
                this.error = err.message;
                console.error('Error al cargar reportes:', err);
            } finally {
                this.cargando = false;
            }
        },

        get cajerosDisponibles() {
            if (!Array.isArray(this.ventas)) return ['Todos'];
            const nombres = this.ventas.map(v => v.cajero);
            return ['Todos', ...new Set(nombres)];
        },

        get ventasFiltradas() {
            if (!Array.isArray(this.ventas)) return [];
            return this.ventas.filter(v => {
                const coincideCajero = this.cajeroFiltro === 'Todos' || v.cajero === this.cajeroFiltro;
                const coincideBusqueda = String(v.folio || '').toLowerCase().includes(this.busqueda.toLowerCase()) || 
                                         String(v.productos || '').toLowerCase().includes(this.busqueda.toLowerCase());
                return coincideCajero && coincideBusqueda;
            });
        },

        get totalOperaciones() {
            return this.ventasFiltradas.length;
        },
        get ingresosTotales() {
            return this.ventasFiltradas.reduce((sum, v) => sum + (Number(v.total) || 0), 0);
        },
        get ticketPromedio() {
            return this.totalOperaciones > 0 ? (this.ingresosTotales / this.totalOperaciones) : 0;
        },

        get totalEfectivo() {
            return this.ventasFiltradas.filter(v => String(v.metodo || '').toLowerCase() === 'efectivo').reduce((sum, v) => sum + v.total, 0);
        },
        get opsEfectivo() {
            return this.ventasFiltradas.filter(v => String(v.metodo || '').toLowerCase() === 'efectivo').length;
        },
        get totalTarjeta() {
            return this.ventasFiltradas.filter(v => String(v.metodo || '').toLowerCase() === 'tarjeta').reduce((sum, v) => sum + v.total, 0);
        },
        get opsTarjeta() {
            return this.ventasFiltradas.filter(v => String(v.metodo || '').toLowerCase() === 'tarjeta').length;
        },
        get totalTransferencia() {
            return this.ventasFiltradas.filter(v => String(v.metodo || '').toLowerCase() === 'transferencia').reduce((sum, v) => sum + v.total, 0);
        },
        get opsTransferencia() {
            return this.ventasFiltradas.filter(v => String(v.metodo || '').toLowerCase() === 'transferencia').length;
        },

        exportarExcel() {
            if (this.ventasFiltradas.length === 0) {
                alert('No hay ventas registradas para exportar.');
                return;
            }

            let csv = 'Folio,Productos,Total,Metodo,Cajero,Hora\n';
            this.ventasFiltradas.forEach(v => {
                const prodLimpio = String(v.productos || '').split('"').join('""');
                csv += '"' + v.folio + '","' + prodLimpio + '",' + Number(v.total || 0).toFixed(2) + ',"' + v.metodo + '","' + v.cajero + '","' + v.hora + '"\n';
            });

            const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Corte_Caja_' + new Date().toISOString().slice(0, 10) + '.csv';
            link.click();
        },

        imprimirCorte() {
            window.print();
        }
    }
}
</script>
@endsection