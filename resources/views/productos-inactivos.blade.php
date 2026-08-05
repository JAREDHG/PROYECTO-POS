@extends('layouts.app')

@section('content')
<div x-data="{
    token: localStorage.getItem('auth_token') || null,
    search: '',
    cargando: false,
    errorMsg: '',
    productosInactivos: [],

    async init() {
        await this.cargarInactivos();
    },

    async cargarInactivos() {
        this.cargando = true;
        this.errorMsg = '';
        try {
            const res = await fetch('/api/products/inactive', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                }
            });
            if (!res.ok) throw new Error('No se pudo cargar la papelera de productos.');
            const data = await res.json();
            const raw = Array.isArray(data) ? data : (data.data || []);

            this.productosInactivos = raw.map(p => ({
                id: p.id,
                sku: p.sku || 'N/A',
                codigo_barras: p.barcode || '7500000000000',
                nombre: p.name,
                categoria: p.category || 'General',
                compra: Number(p.purchase_price || 0),
                venta: Number(p.sale_price || 0),
                stock: Number(p.stock || 0)
            }));
        } catch (e) {
            this.errorMsg = e.message;
            console.error(e);
        } finally {
            this.cargando = false;
        }
    },

    get productosFiltrados() {
        if (!this.search.trim()) return this.productosInactivos;
        const q = this.search.toLowerCase().trim();
        return this.productosInactivos.filter(p => 
            p.nombre.toLowerCase().includes(q) ||
            p.sku.toLowerCase().includes(q) ||
            p.categoria.toLowerCase().includes(q)
        );
    },

    async reactivarProducto(prod) {
        if (!confirm('¿Deseas reactivar el producto ' + prod.nombre + ' y devolverlo al inventario?')) return;

        try {
            const res = await fetch(`/api/products/${prod.id}/restore`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                }
            });
            if (!res.ok) throw new Error('No se pudo reactivar el producto.');

            this.productosInactivos = this.productosInactivos.filter(p => p.id !== prod.id);
            alert('Producto reactivado con éxito.');
        } catch (e) {
            alert('Error: ' + e.message);
        }
    }
}" class="space-y-6">

    <!-- ENCABEZADO -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Productos Inactivos</h1>
            <p class="text-xs text-gray-400 mt-0.5">Histórico de productos dados de baja / Papelera</p>
        </div>
        <a href="/inventario" 
           class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition">
            ⬅️ Volver al Inventario
        </a>
    </div>

    <!-- MENSAJES -->
    <template x-if="cargando">
        <div class="text-xs text-blue-400 bg-blue-500/10 border border-blue-500/20 p-3 rounded-xl animate-pulse text-center">
            Cargando historial de productos inactivos...
        </div>
    </template>
    <template x-if="errorMsg">
        <div class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 p-3 rounded-xl text-center" x-text="errorMsg"></div>
    </template>

    <!-- BÚSQUEDA -->
    <div class="relative w-full max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">🔍</span>
        <input type="text" 
               x-model="search" 
               placeholder="Buscar producto inactivo por nombre o SKU..." 
               class="w-full bg-[#1b2431] text-white placeholder-gray-500 text-xs rounded-xl pl-11 pr-4 py-3 border border-gray-700/80 focus:outline-none focus:border-emerald-500 transition" />
    </div>

    <!-- TABLA -->
    <div class="bg-[#131b26] rounded-2xl border border-gray-800/80 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 text-[11px] font-bold text-gray-400 uppercase tracking-wider bg-[#101721]">
                        <th class="py-4 px-6">SKU</th>
                        <th class="py-4 px-6">PRODUCTO</th>
                        <th class="py-4 px-6">CATEGORÍA</th>
                        <th class="py-4 px-6">ÚLTIMO P. VENTA</th>
                        <th class="py-4 px-6 text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-xs font-medium text-gray-300">
                    <template x-for="prod in productosFiltrados" :key="prod.id">
                        <tr class="hover:bg-gray-800/30 transition opacity-75 hover:opacity-100">
                            <td class="py-4 px-6 font-mono text-gray-400" x-text="prod.sku"></td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-300 line-through text-xs" x-text="prod.nombre"></div>
                                <div class="text-[10px] text-gray-500 font-mono" x-text="prod.codigo_barras"></div>
                            </td>
                            <td class="py-4 px-6" x-text="prod.categoria"></td>
                            <td class="py-4 px-6 font-mono text-gray-400" x-text="'$' + prod.venta.toFixed(2)"></td>
                            <td class="py-4 px-6 text-center">
                                <button @click="reactivarProducto(prod)" 
                                        class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1.5 rounded-lg text-[11px] font-bold transition">
                                    ♻️ Reactivar
                                </button>
                            </td>
                        </tr>
                    </template>

                    <template x-if="!cargando && productosFiltrados.length === 0">
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 text-xs">
                                No hay productos deshabilitados en el historial.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection