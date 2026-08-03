@extends('layouts.app')

@section('content')
<div x-data="dashboardData()" x-init="cargarDatos()" class="h-full flex flex-col space-y-6">

    <!-- Indicadores de Carga y Error Global -->
    <template x-if="cargando">
        <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 p-4 rounded-xl text-center text-sm font-semibold animate-pulse">
            Sincronizando datos con el servidor...
        </div>
    </template>
    
    <template x-if="error">
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl text-center text-sm font-semibold" x-text="error"></div>
    </template>

    <!-- TARJETAS SUPERIORES (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" x-show="!cargando && !error">
        
        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Ventas Totales</span>
                <h3 class="text-2xl font-black text-white font-mono" x-text="kpis.totalVentas"></h3>
                <p class="text-[11px] text-gray-500 mt-1">Operaciones registradas</p>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-xl text-xl">📈</div>
        </div>

        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Ingresos Totales</span>
                <h3 class="text-2xl font-black text-emerald-400 font-mono" x-text="formatoMoneda(kpis.ingresosTotales)"></h3>
                <p class="text-[11px] text-gray-500 mt-1">Suma bruta de transacciones</p>
            </div>
            <div class="bg-emerald-500/10 p-3 rounded-xl text-xl">💰</div>
        </div>

        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Efectivo en Caja</span>
                <h3 class="text-2xl font-black text-amber-400 font-mono" x-text="formatoMoneda(kpis.ingresoEfectivo)"></h3>
                <p class="text-[11px] text-gray-500 mt-1">Fondo ingresado en efectivo</p>
            </div>
            <div class="bg-amber-500/10 p-3 rounded-xl text-xl">💵</div>
        </div>

        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Stock Bajo</span>
                <h3 class="text-2xl font-black text-red-400 font-mono"><span x-text="kpis.productosBajoStock"></span> <span class="text-xs font-normal text-gray-400">items</span></h3>
                <p class="text-[11px] text-red-400/80 mt-1">🔥 Requieren reabastecimiento</p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-xl text-xl">⚠️</div>
        </div>

    </div>

    <!-- SECCIÓN CENTRAL (Últimas Ventas y Alertas de Stock) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-show="!cargando && !error">
        
        <!-- Tabla Últimas Ventas -->
        <div class="lg:col-span-2 bg-[#1f2937] border border-gray-700/70 rounded-2xl overflow-hidden shadow-xl flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-gray-700/60 flex justify-between items-center bg-[#1a222f]/50">
                    <h3 class="font-bold text-white text-base tracking-wide">Últimas Ventas (Top 5)</h3>
                    <a href="/ventas" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                        Ver historial completo →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#111827]/40 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-700/60">
                                <th class="py-3 px-5 font-bold">Folio</th>
                                <th class="py-3 px-5 font-bold">Cajero</th>
                                <th class="py-3 px-5 font-bold">Total</th>
                                <th class="py-3 px-5 font-bold">Método</th>
                                <th class="py-3 px-5 font-bold text-right">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/40 text-xs text-gray-200">
                            <template x-if="ultimasVentas.length === 0">
                                <tr><td colspan="5" class="py-6 text-center text-gray-500">No hay ventas registradas aún.</td></tr>
                            </template>
                            <template x-for="venta in ultimasVentas" :key="venta.id">
                                <tr class="hover:bg-[#111827]/30 transition">
                                    <td class="py-3.5 px-5 font-mono text-gray-400" x-text="venta.ticket_number"></td>
                                    <td class="py-3.5 px-5 font-medium text-white" x-text="venta.user ? venta.user.name : 'Cajero'"></td>
                                    <td class="py-3.5 px-5 font-mono font-bold text-white" x-text="formatoMoneda(venta.total)"></td>
                                    <td class="py-3.5 px-5">
                                        <span class="text-[11px] px-2.5 py-1 rounded-lg font-medium uppercase"
                                              :class="{
                                                  'bg-emerald-500/10 text-emerald-400': venta.payment_method === 'efectivo',
                                                  'bg-blue-500/10 text-blue-400': venta.payment_method === 'tarjeta',
                                                  'bg-purple-500/10 text-purple-400': venta.payment_method === 'transferencia'
                                              }"
                                              x-text="venta.payment_method">
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-5 text-right font-mono text-gray-400" x-text="formatoFecha(venta.created_at)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lista Stock Bajo -->
        <div class="lg:col-span-1 bg-[#1f2937] border border-gray-700/70 rounded-2xl overflow-hidden shadow-xl flex flex-col p-5 justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-white text-base tracking-wide">Alertas de Stock (< 10)</h3>
                    <a href="/inventario" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                        Ir a inventario →
                    </a>
                </div>

                <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
                    <template x-if="productosStockBajo.length === 0">
                        <div class="text-center py-4 text-emerald-400/80 text-sm">El inventario está sano. Ningún producto crítico.</div>
                    </template>
                    <template x-for="producto in productosStockBajo" :key="producto.id">
                        <div class="bg-[#111827]/40 p-3.5 rounded-xl border border-gray-800/80 flex items-center justify-between">
                            <div class="flex-1 truncate pr-2">
                                <p class="text-sm font-semibold text-white leading-tight truncate" x-text="producto.name"></p>
                                <span class="text-[10px] font-mono text-gray-400" x-text="producto.sku"></span>
                            </div>
                            <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-lg whitespace-nowrap"
                                  :class="producto.stock <= 5 ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30'"
                                  x-text="producto.stock + ' pzs'">
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <!-- SECCIÓN INFERIOR (Distribución de Métodos de Pago) -->
    <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 shadow-xl" x-show="!cargando && !error">
        <h3 class="font-bold text-white text-base tracking-wide mb-4">Distribución por Método de Pago</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Efectivo -->
            <div class="bg-[#111827]/50 p-4 rounded-xl border border-gray-800 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💵</span>
                        <span class="text-sm font-semibold text-gray-200">Efectivo</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-white" x-text="formatoMoneda(metodosPago.efectivo.monto)"></span>
                </div>
                <div>
                    <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden mb-2">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000" :style="`width: ${metodosPago.efectivo.porcentaje}%;`"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-medium" x-text="`${metodosPago.efectivo.cantidad} operaciones · ${metodosPago.efectivo.porcentaje}%`"></span>
                </div>
            </div>

            <!-- Tarjeta -->
            <div class="bg-[#111827]/50 p-4 rounded-xl border border-gray-800 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💳</span>
                        <span class="text-sm font-semibold text-gray-200">Tarjeta</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-white" x-text="formatoMoneda(metodosPago.tarjeta.monto)"></span>
                </div>
                <div>
                    <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden mb-2">
                        <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" :style="`width: ${metodosPago.tarjeta.porcentaje}%;`"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-medium" x-text="`${metodosPago.tarjeta.cantidad} operaciones · ${metodosPago.tarjeta.porcentaje}%`"></span>
                </div>
            </div>

            <!-- Transferencia -->
            <div class="bg-[#111827]/50 p-4 rounded-xl border border-gray-800 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🏦</span>
                        <span class="text-sm font-semibold text-gray-200">Transferencia</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-white" x-text="formatoMoneda(metodosPago.transferencia.monto)"></span>
                </div>
                <div>
                    <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden mb-2">
                        <div class="bg-purple-500 h-full rounded-full transition-all duration-1000" :style="`width: ${metodosPago.transferencia.porcentaje}%;`"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-medium" x-text="`${metodosPago.transferencia.cantidad} operaciones · ${metodosPago.transferencia.porcentaje}%`"></span>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- SCRIPT DE ALPINE PARA LA LÓGICA DEL DASHBOARD -->
<script>
    function dashboardData() {
        return {
            token: localStorage.getItem('auth_token'),
            cargando: true,
            error: '',
            
            // Datos en crudo
            ventas: [],
            productos: [],
            
            // Datos procesados para la vista
            kpis: {
                totalVentas: 0,
                ingresosTotales: 0,
                ingresoEfectivo: 0,
                productosBajoStock: 0
            },
            ultimasVentas: [],
            productosStockBajo: [],
            metodosPago: {
                efectivo: { cantidad: 0, monto: 0, porcentaje: 0 },
                tarjeta: { cantidad: 0, monto: 0, porcentaje: 0 },
                transferencia: { cantidad: 0, monto: 0, porcentaje: 0 }
            },

            async cargarDatos() {
                this.cargando = true;
                this.error = '';

                if (!this.token) {
                    this.error = 'No hay sesión activa. Inicia sesión en el Punto de Venta.';
                    this.cargando = false;
                    return;
                }

                const headers = { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                };

                try {
                    // 1. Peticiones simultáneas a las APIs de ventas y productos
                    const [resVentas, resProductos] = await Promise.all([
                        fetch('/api/sales', { headers }),
                        fetch('/api/products', { headers })
                    ]);

                    if (!resVentas.ok || !resProductos.ok) {
                        if (resVentas.status === 401 || resProductos.status === 401) throw new Error('Sesión expirada.');
                        throw new Error('Error al conectar con la API.');
                    }

                    const dataVentas = await resVentas.json();
                    const dataProductos = await resProductos.json();

                    // Asegurar que sean arreglos
                    this.ventas = Array.isArray(dataVentas) ? dataVentas : (dataVentas.data || []);
                    this.productos = Array.isArray(dataProductos) ? dataProductos : (dataProductos.data || []);

                    // 2. Procesar los datos
                    this.procesarKPIs();
                    this.procesarStockBajo();
                    this.procesarDistribucionPagos();

                } catch (err) {
                    console.error("Error cargando Dashboard:", err);
                    this.error = err.message;
                } finally {
                    this.cargando = false;
                }
            },

            procesarKPIs() {
                this.kpis.totalVentas = this.ventas.length;
                this.kpis.ingresosTotales = this.ventas.reduce((sum, v) => sum + Number(v.total), 0);
                
                const ventasEfectivo = this.ventas.filter(v => v.payment_method.toLowerCase() === 'efectivo');
                this.kpis.ingresoEfectivo = ventasEfectivo.reduce((sum, v) => sum + Number(v.total), 0);

                // Top 5 últimas ventas (asumiendo que vienen ordenadas o las ordenamos por fecha)
                this.ultimasVentas = [...this.ventas].sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 5);
            },

            procesarStockBajo() {
                // Consideramos bajo stock si es menor a 10
                this.productosStockBajo = this.productos.filter(p => p.stock < 10).sort((a, b) => a.stock - b.stock);
                this.kpis.productosBajoStock = this.productosStockBajo.length;
            },

            procesarDistribucionPagos() {
                const total = this.kpis.ingresosTotales;
                if (total === 0) return;

                this.ventas.forEach(v => {
                    const metodo = v.payment_method.toLowerCase();
                    const monto = Number(v.total);
                    if (this.metodosPago[metodo] !== undefined) {
                        this.metodosPago[metodo].cantidad += 1;
                        this.metodosPago[metodo].monto += monto;
                    }
                });

                // Calcular porcentajes
                ['efectivo', 'tarjeta', 'transferencia'].forEach(m => {
                    this.metodosPago[m].porcentaje = ((this.metodosPago[m].monto / total) * 100).toFixed(1);
                });
            },

            formatoMoneda(valor) {
                return '$' + Number(valor).toFixed(2);
            },
            
            formatoFecha(fechaStr) {
                if (!fechaStr) return '';
                const d = new Date(fechaStr);
                return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
        }
    }
</script>
@endsection