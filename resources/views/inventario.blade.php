@extends('layouts.app')

@section('content')
<!-- CONTENEDOR PRINCIPAL DE INVENTARIO CONECTADO A LA API -->
<div x-data="{
    token: localStorage.getItem('auth_token') || null,
    search: '',
    openAgregar: false,
    openEditar: false,
    openEliminar: false,
    cargando: false,
    errorMsg: '',

    // Formulario de Producto Activo / Nuevo
    form: {
        id: null,
        sku: '',
        barcode: '',
        name: '',
        category: 'Lácteos',
        purchase_price: 0,
        sale_price: 0,
        stock: 0
    },

    // Lista real obtenida de la API
    productos: [],

    async init() {
        await this.cargarInventario();
    },

    async cargarInventario() {
        this.cargando = true;
        this.errorMsg = '';
        try {
            const res = await fetch('/api/products', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                }
            });
            if (!res.ok) throw new Error('No se pudo cargar el inventario.');
            const data = await res.json();
            const raw = Array.isArray(data) ? data : (data.data || []);

            // Mapeamos los campos del backend a la vista
            this.productos = raw.map(p => ({
                id: p.id,
                sku: p.sku || 'N/A',
                codigo_barras: p.barcode || '7500000000000',
                nombre: p.name,
                categoria: p.category || 'General',
                icono: this.obtenerIcono(p.category || 'General'),
                compra: Number(p.purchase_price || 0),
                venta: Number(p.sale_price || 0),
                stock: Number(p.stock || 0),
                is_active: p.is_active !== undefined ? p.is_active : true
            }));
        } catch (e) {
            this.errorMsg = e.message;
            console.error(e);
        } finally {
            this.cargando = false;
        }
    },

    // Filtro Reactivo
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

    // Abrir Modal de Edición
    editarProducto(prod) {
        this.form = {
            id: prod.id,
            sku: prod.sku,
            barcode: prod.codigo_barras,
            name: prod.nombre,
            category: prod.categoria,
            purchase_price: prod.compra,
            sale_price: prod.venta,
            stock: prod.stock
        };
        this.openEditar = true;
    },

    // Guardar Cambios (PUT /api/products/{id})
    async guardarEdicion() {
        try {
            const res = await fetch(`/api/products/${this.form.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                },
                body: JSON.stringify(this.form)
            });
            if (!res.ok) {
                const errData = await res.json();
                throw new Error(errData.message || 'Error al actualizar el producto.');
            }
            this.openEditar = false;
            await this.cargarInventario();
        } catch (e) {
            alert('Error: ' + e.message);
        }
    },

    // Preparar y Confirmar Eliminación (DELETE /api/products/{id})
    prepararEliminar(prod) {
        this.form = { id: prod.id, name: prod.nombre };
        this.openEliminar = true;
    },
    async confirmarEliminar() {
        const idEliminar = this.form.id; // Guardamos el ID antes de limpiar
        
        // 1. Quitamos el producto de la vista LOCAL de inmediato (Efecto instantáneo)
        this.productos = this.productos.filter(p => p.id !== idEliminar);
        this.openEliminar = false; // Cerramos el modal de inmediato

        try {
            // 2. Mandamos la petición al servidor en segundo plano
            const res = await fetch(`/api/products/${idEliminar}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                }
            });
            
            if (!res.ok) {
                console.error('El servidor no pudo procesar la baja lógica, pero se removió de la vista.');
            }
        } catch (e) {
            console.error('Error de red: ', e.message);
        }
    },

    // Agregar Producto Nuevo (POST /api/products)
    async guardarNuevo() {
        try {
            const payload = {
                sku: this.form.sku || ('SKU-' + Math.floor(Math.random() * 9000 + 1000)),
                barcode: this.form.barcode || '7500000000000',
                name: this.form.name,
                category: this.form.category,
                purchase_price: Number(this.form.purchase_price),
                sale_price: Number(this.form.sale_price),
                stock: Number(this.form.stock)
            };

            const res = await fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                const errData = await res.json();
                throw new Error(errData.message || 'Error al registrar el producto.');
            }

            this.openAgregar = false;
            this.resetForm();
            await this.cargarInventario();
        } catch (e) {
            alert('Error: ' + e.message);
        }
    },

    resetForm() {
        this.form = { id: null, sku: '', barcode: '', name: '', category: 'Lácteos', purchase_price: 0, sale_price: 0, stock: 0 };
    },

    obtenerIcono(cat) {
        const mapa = { 'Lácteos': , 'Panadería': , 'Bebidas': , 'Botanas': , 'Limpieza': , 'Básicos':  };
        return mapa[cat] || ;
    }
}" class="space-y-6">

    <!-- ENCABEZADO E INFORMACIÓN -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Inventario</h1>
            <p class="text-xs text-gray-400 mt-0.5">RF01 · Gestión centralizada de productos (Conectado a BD)</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- BOTÓN PAPELERA DE PRODUCTOS INACTIVOS -->
            <a href="/productos-inactivos"
                class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 border border-gray-700/80 transition">
                🗑️ Ver Papelera
            </a>

            <!-- BOTÓN AGREGAR PRODUCTO -->
            <button @click="resetForm(); openAgregar = true"
                class="bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-lg shadow-emerald-500/15">
                <span class="text-base font-black">+</span> Agregar Producto
            </button>
        </div>
    </div>

    <!-- MENSAJE DE CARGA O ERROR -->
    <template x-if="cargando">
        <div class="text-xs text-blue-400 bg-blue-500/10 border border-blue-500/20 p-3 rounded-xl animate-pulse text-center">
            Sincronizando inventario con el servidor...
        </div>
    </template>
    <template x-if="errorMsg">
        <div class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 p-3 rounded-xl text-center" x-text="errorMsg"></div>
    </template>

    <!-- BARRA DE BÚSQUEDA -->
    <div class="relative w-full max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">🔍</span>
        <input type="text"
            x-model="search"
            placeholder="Buscar producto, SKU o categoría..."
            class="w-full bg-[#1b2431] text-white placeholder-gray-500 text-xs rounded-xl pl-11 pr-4 py-3 border border-gray-700/80 focus:outline-none focus:border-emerald-500 transition" />
    </div>

    <!-- TABLA DE PRODUCTOS -->
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

                            <!-- CATEGORÍA -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-semibold bg-gray-800/80 border border-gray-700/50 text-gray-200">
                                    <span x-text="prod.icono"></span>
                                    <span x-text="prod.categoria"></span>
                                </span>
                            </td>

                            <!-- P. COMPRA -->
                            <td class="py-4 px-6 font-mono text-gray-300" x-text="'$' + prod.compra.toFixed(2)"></td>

                            <!-- P. VENTA -->
                            <td class="py-4 px-6 font-mono font-bold text-emerald-400" x-text="'$' + prod.venta.toFixed(2)"></td>

                            <!-- EXISTENCIAS -->
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

                            <!-- ACCIONES -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-3">
                                    <button @click="editarProducto(prod)" title="Editar producto" class="p-1.5 hover:bg-gray-700/60 rounded-lg text-gray-400 hover:text-emerald-400 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button @click="prepararEliminar(prod)" title="Eliminar producto" class="p-1.5 hover:bg-gray-700/60 rounded-lg text-gray-400 hover:text-red-400 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-if="!cargando && productosFiltrados.length === 0">
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500 text-xs">
                                No se encontraron productos en la base de datos.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>


    <!-- MODAL: EDITAR PRODUCTO -->
    <div x-show="openEditar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" style="display: none;">
        <div @click.away="openEditar = false" class="bg-[#1b2431] border border-gray-700 w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-[#131b26]">
                <h3 class="font-bold text-white text-sm">Editar Producto (Base de Datos)</h3>
                <button @click="openEditar = false" class="text-gray-400 hover:text-white">✕</button>
            </div>
            <div class="p-5 space-y-4 text-xs">
                <div>
                    <label class="block text-gray-400 font-bold mb-1">Nombre del Producto</label>
                    <input type="text" x-model="form.name" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">SKU</label>
                        <input type="text" x-model="form.sku" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Categoría</label>
                        <select x-model="form.category" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500">
                            <option value="Lácteos">Lácteos</option>
                            <option value="Panadería">Panadería</option>
                            <option value="Bebidas">Bebidas</option>
                            <option value="Botanas">Botanas</option>
                            <option value="Limpieza">Limpieza</option>
                            <option value="Básicos">Básicos</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <!-- Precio de Compra (Permite números y punto decimal, bloquea e, E, -, +) -->
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Compra</label>
                        <input type="number"
                            step="0.01"
                            min="0"
                            x-model="form.purchase_price"
                            @keydown="if (['e', 'E', '-', '+'].includes($event.key)) $event.preventDefault()"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9.]/g, '')"
                            class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>

                    <!-- Precio de Venta (Permite números y punto decimal, bloquea e, E, -, +) -->
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Venta</label>
                        <input type="number"
                            step="0.01"
                            min="0"
                            x-model="form.sale_price"
                            @keydown="if (['e', 'E', '-', '+'].includes($event.key)) $event.preventDefault()"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9.]/g, '')"
                            class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>

                    <!-- Stock (Solo enteros positivos: bloquea e, E, -, +, y el punto decimal .) -->
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Stock</label>
                        <input type="number"
                            step="1"
                            min="0"
                            x-model="form.stock"
                            @keydown="if (['e', 'E', '-', '+', '.'].includes($event.key)) $event.preventDefault()"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                            class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                </div>
            </div>
            <div class="p-4 bg-[#131b26] border-t border-gray-700 flex justify-end gap-2">
                <button @click="openEditar = false" class="px-4 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 text-xs font-semibold">Cancelar</button>
                <button @click="guardarEdicion()" class="bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-xs">Guardar Cambios</button>
            </div>
        </div>
    </div>


    <!-- MODAL: AGREGAR NUEVO PRODUCTO -->
    <div x-show="openAgregar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" style="display: none;">
        <div @click.away="openAgregar = false" class="bg-[#1b2431] border border-gray-700 w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-[#131b26]">
                <h3 class="font-bold text-white text-sm">Nuevo Producto (Base de Datos)</h3>
                <button @click="openAgregar = false" class="text-gray-400 hover:text-white">✕</button>
            </div>
            <div class="p-5 space-y-4 text-xs">
                <div>
                    <label class="block text-gray-400 font-bold mb-1">Nombre del Producto</label>
                    <input type="text" x-model="form.name" placeholder="Ej. Coca-Cola 600ml" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">SKU</label>
                        <input type="text" x-model="form.sku" placeholder="BEB001" class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Categoría</label>
                        <select x-model="form.category" class="w-full bg-[#101721] text-white rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500">
                            <option value="Lácteos">Lácteos</option>
                            <option value="Panadería">Panadería</option>
                            <option value="Bebidas">Bebidas</option>
                            <option value="Botanas">Botanas</option>
                            <option value="Limpieza">Limpieza</option>
                            <option value="Básicos">Básicos</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <!-- Precio de Compra (Permite números y punto decimal, bloquea e, E, -, +) -->
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Compra</label>
                        <input type="number"
                            step="0.01"
                            min="0"
                            x-model="form.purchase_price"
                            @keydown="if (['e', 'E', '-', '+'].includes($event.key)) $event.preventDefault()"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9.]/g, '')"
                            class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>

                    <!-- Precio de Venta (Permite números y punto decimal, bloquea e, E, -, +) -->
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">P. Venta</label>
                        <input type="number"
                            step="0.01"
                            min="0"
                            x-model="form.sale_price"
                            @keydown="if (['e', 'E', '-', '+'].includes($event.key)) $event.preventDefault()"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9.]/g, '')"
                            class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>

                    <!-- Stock (Solo enteros positivos: bloquea e, E, -, +, y el punto decimal .) -->
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Stock</label>
                        <input type="number"
                            step="1"
                            min="0"
                            x-model="form.stock"
                            @keydown="if (['e', 'E', '-', '+', '.'].includes($event.key)) $event.preventDefault()"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                            class="w-full bg-[#101721] text-white font-mono rounded-xl p-3 border border-gray-700 focus:outline-none focus:border-emerald-500" />
                    </div>
                </div>
            </div>
            <div class="p-4 bg-[#131b26] border-t border-gray-700 flex justify-end gap-2">
                <button @click="openAgregar = false" class="px-4 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 text-xs font-semibold">Cancelar</button>
                <button @click="guardarNuevo()" class="bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-xs">Guardar Producto</button>
            </div>
        </div>
    </div>


    <!-- MODAL: DESACTIVAR / ELIMINAR PRODUCTO -->
    <div x-show="openEliminar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" style="display: none;">
        <div @click.away="openEliminar = false" class="bg-[#1b2431] border border-gray-700 w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl p-5 text-center space-y-4">
            <div class="w-12 h-12 bg-red-500/10 text-red-400 rounded-full flex items-center justify-center mx-auto text-xl">⚠️</div>
            <div>
                <h3 class="font-bold text-white text-base">¿Desactivar Producto?</h3>
                <p class="text-xs text-gray-400 mt-1">
                    ¿Estás seguro de que deseas desactivar <span class="text-white font-bold" x-text="form.name"></span> de la base de datos?
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