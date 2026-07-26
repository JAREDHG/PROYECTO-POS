@extends('layouts.app')

@section('content')
<!-- CONTENEDOR PRINCIPAL CORTE DE CAJA / REPORTES (ALPINE.JS) -->
<div x-data="{
    cajeroFiltro: 'Todos',
    fechaFiltro: '2026-07-20',

    // Registro de ventas del turno (Coincide exacto con la imagen 2: image_b804d5.png)
    ventas: [
        { folio: 'TK-I3BINK', productos: '1× Coca-Cola 600ml, 2× Frijol Negro 1kg, 1× Ag...', total: 108.00, metodo: 'Efectivo', iconoMetodo: '💵', cajero: 'María García', hora: '11:27 p.m.' },
        { folio: 'TK-A1B2C3', productos: '2× Leche Lala 1L, 3× Coca-Cola 600ml', total: 102.00, metodo: 'Efectivo', iconoMetodo: '💵', cajero: 'María García', hora: '10:51 p.m.' },
        { folio: 'TK-D4E5F6', productos: '1× Pan Bimbo Grande, 1× Arroz Morelos 1kg, 1×...', total: 127.00, metodo: 'Tarjeta', iconoMetodo: '💳', cajero: 'Juan López', hora: '10:27 p.m.' },
        { folio: 'TK-G7H8I9', productos: '2× Sabritas Clásicas 45g, 2× Pepsi 600ml, 1× A...', total: 76.00, metodo: 'Efectivo', iconoMetodo: '💵', cajero: 'María García', hora: '09:41 p.m.' },
        { folio: 'TK-J0K1L2', productos: '1× Jabón Palmolive 150g, 1× Papel Higiénico 4r,...', total: 92.00, metodo: 'Transferencia', iconoMetodo: '📲', cajero: 'Juan López', hora: '08:59 p.m.' },
        { folio: 'TK-M3N4O5', productos: '1× Huevo Bachoco 12pz, 1× Aceite 1-2-3 1L, 1× ...', total: 136.00, metodo: 'Efectivo', iconoMetodo: '💵', cajero: 'María García', hora: '08:14 p.m.' },
        { folio: 'TK-P6Q7R8', productos: '1× Queso Oaxaca 400g, 1× Leche Lala 1L', total: 96.00, metodo: 'Tarjeta', iconoMetodo: '💳', cajero: 'Juan López', hora: '07:21 p.m.' },
        { folio: 'TK-S9T0U1', productos: '3× Galletas Marías 200g, 2× Jugo Del Valle 1L', total: 106.00, metodo: 'Efectivo', iconoMetodo: '💵', cajero: 'María García', hora: '06:27 p.m.' },
        { folio: 'TK-V2W3X4', productos: '4× Coca-Cola 600ml, 2× Ruffles 45g', total: 102.00, metodo: 'Efectivo', iconoMetodo: '💵', cajero: 'Juan López', hora: '05:24 p.m.' }
    ],

    // Filtrar por cajero activo
    get ventasFiltradas() {
        if (this.cajeroFiltro === 'Todos') return this.ventas;
        return this.ventas.filter(v => v.cajero === this.cajeroFiltro);
    },

    // Métricas Calculadas
    get totalOperaciones() {
        return this.ventasFiltradas.length;
    },
    get ingresosTotales() {
        return this.ventasFiltradas.reduce((sum, v) => sum + v.total, 0);
    },
    get ticketPromedio() {
        return this.totalOperaciones > 0 ? (this.ingresosTotales / this.totalOperaciones) : 0;
    },

    // Desglose por Método
    get totalEfectivo() {
        return this.ventasFiltradas.filter(v => v.metodo === 'Efectivo').reduce((sum, v) => sum + v.total, 0);
    },
    get opsEfectivo() {
        return this.ventasFiltradas.filter(v => v.metodo === 'Efectivo').length;
    },
    get totalTarjeta() {
        return this.ventasFiltradas.filter(v => v.metodo === 'Tarjeta').reduce((sum, v) => sum + v.total, 0);
    },
    get opsTarjeta() {
        return this.ventasFiltradas.filter(v => v.metodo === 'Tarjeta').length;
    },
    get totalTransferencia() {
        return this.ventasFiltradas.filter(v => v.metodo === 'Transferencia').reduce((sum, v) => sum + v.total, 0);
    },
    get opsTransferencia() {
        return this.ventasFiltradas.filter(v => v.metodo === 'Transferencia').length;
    }
}" class="space-y-6">

    <!-- ENCABEZADO CORTE DE CAJA -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Corte de Caja</h1>
            <p class="text-xs text-gray-400 mt-0.5">RF04 · Trazabilidad y auditoría del turno</p>
        </div>
    </div>

    <!-- FILTROS (CAJEROS Y FECHA) -->
    <div class="flex flex-wrap items-center gap-3">
        <!-- Selector de Cajeros -->
        <div class="bg-[#18202c] p-1 rounded-xl border border-gray-800 flex items-center gap-1">
            <button @click="cajeroFiltro = 'Todos'" 
                    :class="cajeroFiltro === 'Todos' ? 'bg-emerald-600/30 text-emerald-400 font-bold border border-emerald-500/30' : 'text-gray-400 hover:text-white'"
                    class="px-4 py-1.5 rounded-lg text-xs transition">
                Todos
            </button>
            <button @click="cajeroFiltro = 'María García'" 
                    :class="cajeroFiltro === 'María García' ? 'bg-emerald-600/30 text-emerald-400 font-bold border border-emerald-500/30' : 'text-gray-400 hover:text-white'"
                    class="px-4 py-1.5 rounded-lg text-xs transition">
                María García
            </button>
            <button @click="cajeroFiltro = 'Juan López'" 
                    :class="cajeroFiltro === 'Juan López' ? 'bg-emerald-600/30 text-emerald-400 font-bold border border-emerald-500/30' : 'text-gray-400 hover:text-white'"
                    class="px-4 py-1.5 rounded-lg text-xs transition">
                Juan López
            </button>
        </div>

        <!-- Selector de Fecha -->
        <div class="bg-[#18202c] px-3 py-1.5 rounded-xl border border-gray-800 text-xs text-gray-300 flex items-center gap-2">
            <span>📅</span>
            <span>20 de julio de 2026</span>
        </div>
    </div>

    <!-- TARJETAS SUPERIORES DE MÉTRICAS GENERALES -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Total Operaciones -->
        <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-5 relative overflow-hidden shadow-xl">
            <div class="w-9 h-9 rounded-xl bg-gray-800/60 text-gray-300 flex items-center justify-center text-sm mb-4">
                📋
            </div>
            <div class="text-3xl font-black text-white font-mono" x-text="totalOperaciones"></div>
            <div class="text-xs font-semibold text-gray-400 mt-1">Total Operaciones</div>
            <div class="text-[10px] text-gray-500 mt-0.5">ventas del turno</div>
        </div>

        <!-- Ingresos Totales -->
        <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-5 relative overflow-hidden shadow-xl">
            <div class="w-9 h-9 rounded-xl bg-gray-800/60 text-amber-400 flex items-center justify-center text-sm mb-4">
                💰
            </div>
            <div class="text-3xl font-black text-white font-mono" x-text="'$' + ingresosTotales.toFixed(2)"></div>
            <div class="text-xs font-semibold text-gray-400 mt-1">Ingresos Totales</div>
            <div class="text-[10px] text-gray-500 mt-0.5">suma de ventas</div>
        </div>

        <!-- Ticket Promedio -->
        <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-5 relative overflow-hidden shadow-xl">
            <div class="w-9 h-9 rounded-xl bg-gray-800/60 text-purple-400 flex items-center justify-center text-sm mb-4">
                📊
            </div>
            <div class="text-3xl font-black text-white font-mono" x-text="'$' + ticketPromedio.toFixed(2)"></div>
            <div class="text-xs font-semibold text-gray-400 mt-1">Ticket Promedio</div>
            <div class="text-[10px] text-gray-500 mt-0.5">por operación</div>
        </div>

    </div>

    <!-- DESGLOSE POR MÉTODO DE PAGO -->
    <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-6 space-y-4 shadow-xl">
        <h3 class="text-sm font-bold text-white">Desglose por Método de Pago</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Efectivo -->
            <div class="bg-[#18202c] border border-gray-800 rounded-xl p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-lg">💵</div>
                <div>
                    <div class="text-xs text-gray-400 font-medium">Efectivo</div>
                    <div class="text-xl font-bold text-white font-mono" x-text="'$' + totalEfectivo.toFixed(2)"></div>
                    <div class="text-[10px] text-gray-500 font-mono" x-text="opsEfectivo + ' ops.'"></div>
                </div>
            </div>

            <!-- Tarjeta -->
            <div class="bg-[#18202c] border border-gray-800 rounded-xl p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-blue-500/10 text-blue-400 text-lg">💳</div>
                <div>
                    <div class="text-xs text-gray-400 font-medium">Tarjeta</div>
                    <div class="text-xl font-bold text-white font-mono" x-text="'$' + totalTarjeta.toFixed(2)"></div>
                    <div class="text-[10px] text-gray-500 font-mono" x-text="opsTarjeta + ' ops.'"></div>
                </div>
            </div>

            <!-- Transferencia -->
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

    <!-- REGISTRO DE VENTAS DEL TURNO (TABLA DETALLADA EXACTA A LA SEGUNDA IMAGEN) -->
    <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl overflow-hidden shadow-2xl">
        
        <!-- Encabezado Tabla -->
        <div class="p-5 border-b border-gray-800 flex justify-between items-center bg-[#101721]">
            <h3 class="text-sm font-bold text-white">Registro de Ventas del Turno</h3>
            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold px-3 py-1 rounded-lg font-mono"
                  x-text="ventasFiltradas.length + ' operaciones'"></span>
        </div>

        <!-- Tabla -->
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
                    <template x-for="v in ventasFiltradas" :key="v.folio">
                        <tr class="hover:bg-gray-800/30 transition">
                            
                            <!-- FOLIO (DESTACADO EN VERDE) -->
                            <td class="py-4 px-6 font-mono font-bold text-emerald-400" x-text="v.folio"></td>

                            <!-- PRODUCTOS DESGLOSADOS -->
                            <td class="py-4 px-6 text-gray-300 max-w-xs truncate" x-text="v.productos"></td>

                            <!-- TOTAL (MONOESPACIADO BOLD) -->
                            <td class="py-4 px-6 font-mono font-black text-white" x-text="'$' + v.total.toFixed(2)"></td>

                            <!-- MÉTODO -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-300">
                                    <span x-text="v.iconoMetodo"></span>
                                    <span x-text="v.metodo"></span>
                                </span>
                            </td>

                            <!-- CAJERO -->
                            <td class="py-4 px-6 text-gray-300" x-text="v.cajero"></td>

                            <!-- HORA -->
                            <td class="py-4 px-6 font-mono text-gray-400" x-text="v.hora"></td>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- PIE DE TABLA CON TOTAL CORTE DE CAJA -->
        <div class="p-4 bg-[#0f151e] border-t border-gray-800 flex justify-between items-center text-xs">
            <span class="text-gray-500 font-mono">Corte al 11:35 p.m.</span>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 font-bold uppercase tracking-wider">Total:</span>
                <span class="text-lg font-black text-emerald-400 font-mono" x-text="'$' + ingresosTotales.toFixed(2)"></span>
            </div>
        </div>

    </div>

</div>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection