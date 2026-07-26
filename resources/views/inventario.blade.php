@extends('layouts.app')

@section('content')
<!-- CONTENEDOR PRINCIPAL DE INVENTARIO (ALPINE.JS) -->
<div x-data="{
    search: '',
    openAgregar: false,
    openEditar: false,
    openEliminar: false,

    // Formulario de Producto Activo / Nuevo
    form: {
        id: null,
        sku: '',
        codigo_barras: '',
        nombre: '',
        categoria: 'Lácteos',
        precio_compra: 0,
        precio_venta: 0,
        stock: 0
    },

    // Lista de productos basada en tu segunda imagen (image_b7aae3.png)
    productos: [
        { id: 1, sku: 'LAC001', codigo_barras: '7501030470492', nombre: 'Leche Lala 1L', categoria: 'Lácteos', icono: '🥛', compra: 18.00, venta: 24.00, stock: 48 },
        { id: 2, sku: 'PAN001', codigo_barras: '7501000103027', nombre: 'Pan Bimbo Grande', categoria: 'Panadería', icono: '🍞', compra: 42.00, venta: 55.00, stock: 12 },
        { id: 3, sku: 'BEB001', codigo_barras: '7501055312638', nombre: 'Coca-Cola 600ml', categoria: 'Bebidas', icono: '🥤', compra: 12.00, venta: 18.00, stock: 71 },
        { id: 4, sku: 'BOT001', codigo_barras: '7501005592036', nombre: 'Sabritas Clásicas 45g', categoria: 'Botanas', icono: '🍿', compra: 10.00, venta: 15.00, stock: 35 },
        { id: 5, sku: 'LIM001', codigo_barras: '7509546056265', nombre: 'Jabón Palmolive 150g', categoria: 'Limpieza', icono: '🧼', compra: 20.00, venta: 28.00, stock: 8 },
        { id: 6, sku: 'BAS001', codigo_barras: '7502227019012', nombre: 'Arroz Morelos 1kg', categoria: 'Básicos', icono: '🌾', compra: 24.00, venta: 34.00, stock: 25 },
        { id: 7, sku: 'BAS002', codigo_barras: '7502001100020', nombre: 'Frijol Negro 1kg', categoria: 'Básicos', icono: '🌾', compra: 28.00, venta: 38.00, stock: 18 },
        { id: 8, sku: 'BAS003', codigo_barras: '7501020610004', nombre: 'Aceite 1-2-3 1L', categoria: 'Básicos', icono: '🌾', compra: 42.00, venta: 58.00, stock: 15 }
    ],

    // Filtro Reactivo e Insensible a Mayúsculas/Minúsculas
    get productosFiltrados() {
        if (!this.search.trim()) return this.productos;
        const q = this.search.toLowerCase().trim();
        return this.productos.filter(p => 
            p.nombre.toLowerCase().includes(q) ||
            p.sku.toLowerCase().includes(q) ||
            p.categoria.toLowerCase().includes(q) ||
            p.codigo_barras.toLowerCase().includes(q)
        );
    },

    // Abrir Modal de Edición con Datos Cargados
    editarProducto(prod) {
        this.form = { ...prod, precio_compra: prod.compra, precio_venta: prod.venta };
        this.openEditar = true;
    },

    // Guardar Cambios de Edición
    guardarEdicion() {
        let index = this.productos.findIndex(p => p.id === this.form.id);
        if (index !== -1) {
            this.productos[index].nombre = this.form.nombre;
            this.productos[index].sku = this.form.sku;
            this.productos[index].categoria = this.form.categoria;
            this.productos[index].compra = parseFloat(this.form.precio_compra);
            this.productos[index].venta = parseFloat(this.form.precio_venta);
            this.productos[index].stock = parseInt(this.form.stock);
        }
        this.openEditar = false;
    },

    // Preparar y Confirmar Eliminación
    prepararEliminar(prod) {
        this.form = { ...prod };
        this.openEliminar = true;
    },
    confirmarEliminar() {
        this.productos = this.productos.filter(p => p.id !== this.form.id);
        this.openEliminar = false;
    },

    // Agregar Producto Nuevo
    guardarNuevo() {
        const idNuevo = Date.now();
        this.productos.unshift({
            id: idNuevo,
            sku: this.form.sku || 'SKU' + Math.floor(Math.random() * 900 + 100),
            codigo_barras: this.form.codigo_barras || '7500000000000',
            nombre: this.form.nombre,
            categoria: this.form.categoria,
            icono: this.obtenerIcono(this.form.categoria),
            compra: parseFloat(this.form.precio_compra) || 0,
            venta: parseFloat(this.form.precio_venta) || 0,
            stock: parseInt(this.form.stock) || 0
        });
        this.openAgregar = false;
        this.resetForm();
    },

    resetForm() {
        this.form = { id: null, sku: '', codigo_barras: '', nombre: '', categoria: 'Lácteos', precio_compra: 0, precio_venta: 0, stock: 0 };
    },

    obtenerIcono(cat) {
        const mapa = { 'Lácteos': '🥛', 'Panadería': '🍞', 'Bebidas': '🥤', 'Botanas': '🍿', 'Limpieza': '🧼', 'Básicos': '🌾' };
        return mapa[cat] || '📦';
    }
}" class="space-y-6">

    <!-- ENCABEZADO E INFORMACIÓN DEL SISTEMA -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Inventario</h1>
            <p class="text-xs text-gray-400 mt-0.5">RF01 · Gestión centralizada de productos</p>
        </div>

        <button @click="resetForm(); openAgregar = true" 
                class="bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-lg shadow-emerald-500/10">
            <span class="text-base font-black">+</span> Agregar Producto
        </button>
    </div>

    <!-- BARRA DE BÚSQUEDA Y FILTRADO -->
    <div class="relative w-full max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">🔍</span>
        <input type="text" 
               x-model="search" 
               placeholder="Buscar producto, SKU o categoría..." 
               class="w-full bg-[#1b2431] text-white placeholder-gray-500 text-xs rounded-xl pl-11 pr-4 py-3 border border-gray-700/80 focus:outline-none focus:border-emerald-500 transition" />
    </div>

    <!-- TABLA DE PRODUCTOS (ESTILO IDÉNTICO A LA SEGUNDA IMAGEN) -->
    <div class="bg-[#131b26] rounded-2xl border border-gray-800/80 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 text-[11px] font-bold text-gray-400 uppercase tracking-wider bg-[#101721]">
                        <th class="py-4 px-6">SKU</th>
                        <th class="py-4 px-6">PRODUCTO</th>
                        <th class="py-4 px-6">CATEGORÍA</th>
                        <th class="py-4 px-6">P. COMPRA</th>
                        <th class="py-4 px-6">P. VENTA</th>
                        <th class="py-4 px-6">EXISTENCIAS</th>
                        <th class="py-4 px-6 text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-xs font-medium text-gray-300">
                    <template x-for="prod in productosFiltrados" :key="prod.id">
                        <tr class="hover:bg-gray-800/30 transition">
                            
                            <!-- SKU -->
                            <td class="py-4 px-6 font-mono text-gray-400" x-text="prod.sku"></td>

                            <!-- PRODUCTO & CÓDIGO DE BARRAS -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-white text-xs" x-text="prod.nombre"></div>
                                <div class="text-[10px] text-gray-500 font-mono mt-0.5" x-text="prod.codigo_barras"></div>
                            </td>

                            <!-- CATEGORÍA BADGE -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-semibold bg-gray-800/80 border border-gray-700/50 text-gray-200">
                                    <span x-text="prod.icono"></span>
                                    <span x-text="prod.categoria"></span>
                                </span>
                            </td>

                            <!-- P. COMPRA -->
                            <td class="py-4 px-6 font-mono text-gray-300" x-text="'$' + prod.compra.toFixed(2)"></td>

                            <!-- P. VENTA (DESTACADO EN VERDE) -->
                            <td class="py-4 px-6 font-mono font-bold text-emerald-400" x-text="'$' + prod.venta.toFixed(2)"></td>

                            <!-- EXISTENCIAS & BADGE BAJO STOCK -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold font-mono"
                                          :class="prod.stock <= 10 ? 'bg-amber-500/10 text-amber-400' : 'bg-emerald-500/10 text-emerald-400'"
                                          x-text="prod.stock + ' pzs'"></span>
                                    
                                    <template x-if="prod.stock <= 10">
                                        <span class="text-[10px] font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-500/20">⚠️ Bajo</span>
                                    </template>
                                </div>
                            </td>

                            <!-- ACCIONES (EDITAR Y ELIMINAR TAL CUAL LA SEGUNDA IMAGEN) -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-3">
                                    <button @click="editarProducto(prod)" 
                                            title="Editar producto"
                                            class="p-1.5 hover:bg-gray-700/60 rounded-lg text-gray-400 hover:text-emerald-400 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <button @click="prepararEliminar(prod)" 
                                            title="Eliminar producto"
                                            class="p-1.5 hover:bg-gray-700/60 rounded-lg text-gray-400 hover:text-red-400 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    </template>

                    <template x-if="productosFiltrados.length === 0">
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500 text-xs">
                                No se encontraron productos que coincidan con "<span x-text="search" class="text-gray-300 font-bold"></span>".
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL: EDITAR PRODUCTO -->
    <!-- ========================================================================= -->
    <div x-show="openEditar" 
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" style="display: none;">
        
        <div @click.away="openEditar = false" class="bg-[#1b2431] border border-gray-700 w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-[#131b26]">
                <h3 class="font-bold text-white text-sm">Editar Producto</h3>
                <button @click="openEditar = false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <div class="p-5 space-y-4 text-xs">
                <div>
                    <label class="block text-gray-400 font-bold mb-1">Nombre del Producto</label>
                    <input type="text" x-model="form.nombre" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">SKU</label>
                        <input type="text" x-model="form.sku" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Categoría</label>
                        <select x-model="form.categoria" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500">
                            <option value="Lácteos">🥛 Lácteos</option>
                            <option value="Panadería">🍞 Panadería</option>
                            <option value="Bebidas">🥤 Bebidas</option>
                            <option value="Botanas">🍿 Botanas</option>
                            <option value="Limpieza">🧼 Limpieza</option>
                            <option value="Básicos">🌾 Básicos</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Compra</label>
                        <input type="number" step="0.01" x-model="form.precio_compra" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Venta</label>
                        <input type="number" step="0.01" x-model="form.precio_venta" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Stock</label>
                        <input type="number" x-model="form.stock" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#131b26] border-t border-gray-700 flex justify-end gap-2">
                <button @click="openEditar = false" class="px-4 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 text-xs font-semibold">Cancelar</button>
                <button @click="guardarEdicion()" class="bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-xs">Guardar Cambios</button>
            </div>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL: AGREGAR NUEVO PRODUCTO -->
    <!-- ========================================================================= -->
    <div x-show="openAgregar" 
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" style="display: none;">
        
        <div @click.away="openAgregar = false" class="bg-[#1b2431] border border-gray-700 w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-[#131b26]">
                <h3 class="font-bold text-white text-sm">Nuevo Producto</h3>
                <button @click="openAgregar = false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <div class="p-5 space-y-4 text-xs">
                <div>
                    <label class="block text-gray-400 font-bold mb-1">Nombre del Producto</label>
                    <input type="text" x-model="form.nombre" placeholder="Ej. Coca-Cola 600ml" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">SKU</label>
                        <input type="text" x-model="form.sku" placeholder="BEB001" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Categoría</label>
                        <select x-model="form.categoria" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500">
                            <option value="Lácteos">🥛 Lácteos</option>
                            <option value="Panadería">🍞 Panadería</option>
                            <option value="Bebidas">🥤 Bebidas</option>
                            <option value="Botanas">🍿 Botanas</option>
                            <option value="Limpieza">🧼 Limpieza</option>
                            <option value="Básicos">🌾 Básicos</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Compra</label>
                        <input type="number" step="0.01" x-model="form.precio_compra" placeholder="0.00" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Venta</label>
                        <input type="number" step="0.01" x-model="form.precio_venta" placeholder="0.00" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Stock Inicial</label>
                        <input type="number" x-model="form.stock" placeholder="0" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#131b26] border-t border-gray-700 flex justify-end gap-2">
                <button @click="openAgregar = false" class="px-4 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 text-xs font-semibold">Cancelar</button>
                <button @click="guardarNuevo()" class="bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-xs">Guardar Producto</button>
            </div>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL: ELIMINAR PRODUCTO -->
    <!-- ========================================================================= -->
    <div x-show="openEliminar" 
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" style="display: none;">
        
        <div @click.away="openEliminar = false" class="bg-[#1b2431] border border-gray-700 w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl p-5 text-center space-y-4">
            <div class="w-12 h-12 bg-red-500/10 text-red-400 rounded-full flex items-center justify-center mx-auto text-xl">⚠️</div>
            <div>
                <h3 class="font-bold text-white text-base">¿Eliminar Producto?</h3>
                <p class="text-xs text-gray-400 mt-1">
                    ¿Estás seguro de que deseas eliminar <span class="text-white font-bold" x-text="form.nombre"></span>? Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="flex gap-2 justify-center pt-2">
                <button @click="openEliminar = false" class="w-full py-2.5 rounded-xl text-gray-400 bg-gray-800 hover:bg-gray-700 text-xs font-semibold">Cancelar</button>
                <button @click="confirmarEliminar()" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl text-xs">Eliminar</button>
            </div>
        </div>
    </div>

</div>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection