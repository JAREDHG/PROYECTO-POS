@extends('layouts.app')

@section('content')
<!-- ESTILOS EXCLUSIVOS PARA IMPRESIÓN (PDF / CORTE) -->
<style>
    @media print {
        /* Ocultar elementos de la interfaz general (Sidebar, Header, Botones) */
        aside, header, .no-print {
            display: none !important;
        }

        body {
            background-color: #ffffff !important;
            color: #111827 !important;
            font-family: system-ui, -apple-system, sans-serif !important;
        }

        .main-content, main, div {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Mostrar la plantilla de impresión */
        #plantilla-impresion {
            display: block !important;
        }
    }

    /* Ocultar la plantilla de impresión en la vista normal de la web */
    #plantilla-impresion {
        display: none;
    }
</style>

<!-- CONTENEDOR PRINCIPAL CORTE DE CAJA / REPORTES -->
<div x-data="reportesComponent()" x-init="cargarVentas()" class="space-y-6">

    <!-- ENCABEZADO CORTE DE CAJA (WEB) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
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

    <!-- FILTROS Y BÚSQUEDA (WEB) -->
    <div class="flex flex-wrap items-center justify-between gap-3 no-print">
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

    <!-- TARJETAS SUPERIORES DE MÉTRICAS GENERALES (WEB) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 no-print">
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

    <!-- DESGLOSE POR MÉTODO DE PAGO (WEB) -->
    <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl p-6 space-y-4 shadow-xl no-print">
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

    <!-- REGISTRO DE VENTAS DEL TURNO (WEB) -->
    <div class="bg-[#131b26] border border-gray-800/80 rounded-2xl overflow-hidden shadow-2xl no-print">
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

    <!-- ================================================================= -->
    <!-- 📄 PLANTILLA INSTITUCIONAL EXCLUSIVA PARA IMPRESIÓN Y PDF -->
    <!-- ================================================================= -->
    <div id="plantilla-impresion" class="p-6 text-black space-y-6">
        
        <!-- ENCABEZADO INSTITUCIONAL DE LA UPT -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #059669; padding-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <!-- Logo UPT -->
                <img src="{{ asset('imagenes/logo_uptex.png') }}" alt="Logo UPT" style="height: 55px; width: auto;">
                <div>
                    <h2 style="font-size: 16px; font-weight: 800; color: #111827; margin: 0; text-transform: uppercase;">Universidad Politécnica de Texcoco</h2>
                    <p style="font-size: 11px; color: #4b5563; margin: 2px 0 0 0;">Sistema POS - Reporte Oficial de Corte de Caja</p>
                </div>
            </div>
            <div style="text-align: right; font-size: 11px; color: #374151;">
                <p style="margin: 0;"><strong>Fecha de Generación:</strong> <span x-text="new Date().toLocaleDateString('es-MX')"></span></p>
                <p style="margin: 2px 0 0 0;"><strong>Hora:</strong> <span x-text="new Date().toLocaleTimeString('es-MX')"></span></p>
                <p style="margin: 2px 0 0 0; color: #059669; font-weight: bold;">Filtro: <span x-text="cajeroFiltro"></span></p>
            </div>
        </div>

        <!-- TARJETAS DE MÉTRICAS (IMPRESIÓN) -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 15px;">
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; background-color: #f9fafb;">
                <span style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Total Operaciones</span>
                <p style="font-size: 20px; font-weight: 800; color: #111827; margin: 4px 0 0 0;" x-text="totalOperaciones"></p>
            </div>
            <div style="border: 1px solid #10b981; border-radius: 8px; padding: 10px; background-color: #ecfdf5;">
                <span style="font-size: 10px; font-weight: 700; color: #047857; text-transform: uppercase;">Ingresos Totales</span>
                <p style="font-size: 20px; font-weight: 800; color: #047857; margin: 4px 0 0 0;" x-text="'$' + ingresosTotales.toFixed(2)"></p>
            </div>
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; background-color: #f9fafb;">
                <span style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Ticket Promedio</span>
                <p style="font-size: 20px; font-weight: 800; color: #111827; margin: 4px 0 0 0;" x-text="'$' + ticketPromedio.toFixed(2)"></p>
            </div>
        </div>

        <!-- DESGLOSE DE MÉTODOS DE PAGO (IMPRESIÓN) -->
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background-color: #ffffff; margin-top: 10px;">
            <h4 style="font-size: 12px; font-weight: 700; color: #374151; margin: 0 0 8px 0; text-transform: uppercase;">Desglose por Método de Pago</h4>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; font-size: 11px;">
                <div>
                    <span style="color: #6b7280;">💵 Efectivo:</span>
                    <strong style="color: #111827;" x-text="'$' + totalEfectivo.toFixed(2) + ' (' + opsEfectivo + ' ops)'"></strong>
                </div>
                <div>
                    <span style="color: #6b7280;">💳 Tarjeta:</span>
                    <strong style="color: #111827;" x-text="'$' + totalTarjeta.toFixed(2) + ' (' + opsTarjeta + ' ops)'"></strong>
                </div>
                <div>
                    <span style="color: #6b7280;">📲 Transferencia:</span>
                    <strong style="color: #111827;" x-text="'$' + totalTransferencia.toFixed(2) + ' (' + opsTransferencia + ' ops)'"></strong>
                </div>
            </div>
        </div>

        <!-- TABLA DE AUDITORÍA DE VENTAS (IMPRESIÓN) -->
        <div style="margin-top: 15px;">
            <h4 style="font-size: 12px; font-weight: 700; color: #111827; margin: 0 0 8px 0; text-transform: uppercase;">Detalle de Transacciones Registradas</h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 10px; text-align: left;">
                <thead>
                    <tr style="background-color: #f3f4f6; border-bottom: 1px solid #d1d5db; color: #374151; font-weight: 700;">
                        <th style="padding: 6px;">FOLIO</th>
                        <th style="padding: 6px;">PRODUCTOS</th>
                        <th style="padding: 6px;">MÉTODO</th>
                        <th style="padding: 6px;">CAJERO</th>
                        <th style="padding: 6px;">HORA</th>
                        <th style="padding: 6px; text-align: right;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="v in ventasFiltradas" :key="v.id || v.folio">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 6px; font-weight: bold; color: #047857;" x-text="v.folio"></td>
                            <td style="padding: 6px; color: #374151;" x-text="v.productos"></td>
                            <td style="padding: 6px; color: #374151;" x-text="v.metodo"></td>
                            <td style="padding: 6px; color: #374151;" x-text="v.cajero"></td>
                            <td style="padding: 6px; color: #6b7280;" x-text="v.hora"></td>
                            <td style="padding: 6px; text-align: right; font-weight: bold; color: #111827;" x-text="'$' + (v.total || 0).toFixed(2)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- FIRMAS INSTITUCIONALES -->
        <div style="margin-top: 40px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; text-align: center; font-size: 11px; color: #4b5563;">
            <div>
                <div style="border-bottom: 1px solid #9ca3af; margin-bottom: 6px; height: 35px;"></div>
                <p style="margin: 0; font-weight: bold;">Firma del Cajero en Turno</p>
            </div>
            <div>
                <div style="border-bottom: 1px solid #9ca3af; margin-bottom: 6px; height: 35px;"></div>
                <p style="margin: 0; font-weight: bold;">Firma del Administrador / Validador</p>
            </div>
        </div>

    </div>

</div>

<!-- LÓGICA JAVASCRIPT -->
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