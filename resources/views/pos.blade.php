@extends('layouts.app')

@section('content')
<div x-data="{
    openCobrar: false,
    openTicket: false,
    metodo: 'efectivo',
    recibido: 0,
    
    // Datos del cajero activo
    cajero: 'María García',

    // Catálogo de productos
    productos: [
        { id: 1, sku: 'LAC001', nombre: 'Leche Lala 1L', categoria: 'Lácteos', categoriaIcono: '🥛', precio: 24.00, stock: 47 },
        { id: 2, sku: 'PAN001', nombre: 'Pan Bimbo Grande', categoria: 'Panadería', categoriaIcono: '🍞', precio: 55.00, stock: 12 },
        { id: 3, sku: 'BEB001', nombre: 'Jugo Del Valle 1L', categoria: 'Bebidas', categoriaIcono: '🥤', precio: 26.00, stock: 20 },
        { id: 4, sku: 'BOT001', nombre: 'Sabritas Clásicas 45g', categoria: 'Botanas', categoriaIcono: '🍿', precio: 15.00, stock: 35 },
        { id: 5, sku: 'LIM001', nombre: 'Papel Higiénico 4r', categoria: 'Limpieza', categoriaIcono: '🧻', precio: 42.00, stock: 30 },
        { id: 6, sku: 'BEB002', nombre: 'Coca-Cola 600ml', categoria: 'Bebidas', categoriaIcono: '🥤', precio: 18.00, stock: 50 },
        { id: 7, sku: 'BAS001', nombre: 'Frijol Negro 1kg', categoria: 'Básicos', categoriaIcono: '🌾', precio: 38.00, stock: 20 },
        { id: 8, sku: 'BEB003', nombre: 'Agua Bonafont 1.5L', categoria: 'Bebidas', categoriaIcono: '🥤', precio: 14.00, stock: 60 }
    ],

    // Estado del Carrito
    cart: [],
    categoriaSeleccionada: 'Todos',
    busqueda: '',

    // Objeto para almacenar la información del ticket generado
    ticket: {
        folio: '',
        fecha: '',
        hora: '',
        items: [],
        total: 0,
        metodo: '',
        recibido: 0,
        cambio: 0
    },

    // Funciones del Carrito
    addToCart(producto) {
        let item = this.cart.find(i => i.id === producto.id);
        if (item) {
            if (item.cantidad < producto.stock) {
                item.cantidad++;
            } else {
                alert('¡No hay más existencias disponibles!');
            }
        } else {
            this.cart.push({ ...producto, cantidad: 1 });
        }
    },
    increaseQty(item) {
        let prod = this.productos.find(p => p.id === item.id);
        if (item.cantidad < prod.stock) {
            item.cantidad++;
        }
    },
    decreaseQty(item) {
        if (item.cantidad > 1) {
            item.cantidad--;
        } else {
            this.removeFromCart(item);
        }
    },
    removeFromCart(item) {
        this.cart = this.cart.filter(i => i.id !== item.id);
    },
    clearCart() {
        this.cart = [];
    },
    
    // Totales Calculados
    get total() {
        return this.cart.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    },
    get totalArticulos() {
        return this.cart.reduce((sum, item) => sum + item.cantidad, 0);
    },
    
    // Filtro de productos
    get productosFiltrados() {
        return this.productos.filter(p => {
            let coincideCat = this.categoriaSeleccionada === 'Todos' || p.categoria === this.categoriaSeleccionada;
            let coincideBusqueda = p.nombre.toLowerCase().includes(this.busqueda.toLowerCase()) || 
                                    p.sku.toLowerCase().includes(this.busqueda.toLowerCase());
            return coincideCat && coincideBusqueda;
        });
    },

    // Procesar Venta y Generar Ticket
    finalizarVenta() {
        const ahora = new Date();
        const folioRandom = 'TK-' + Math.random().toString(36).substring(2, 8).toUpperCase();
        
        // Formatear Fecha (DD/MM/YY) y Hora
        const dia = String(ahora.getDate()).padStart(2, '0');
        const mes = String(ahora.getMonth() + 1).padStart(2, '0');
        const anio = String(ahora.getFullYear()).slice(-2);
        const fechaFormateada = `${dia}/${mes}/${anio}`;
        const horaFormateada = ahora.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }).toLowerCase();

        this.ticket = {
            folio: folioRandom,
            fecha: fechaFormateada,
            hora: horaFormateada,
            items: JSON.parse(JSON.stringify(this.cart)),
            total: this.total,
            metodo: this.metodo,
            recibido: this.metodo === 'efectivo' ? this.recibido : this.total,
            cambio: this.metodo === 'efectivo' ? Math.max(0, this.recibido - this.total) : 0
        };

        this.openCobrar = false;
        this.openTicket = true;
    },

    // Reiniciar para una Nueva Venta
    nuevaVenta() {
        this.clearCart();
        this.openTicket = false;
        this.recibido = 0;
        this.metodo = 'efectivo';
    }
}" class="h-full flex flex-col">

    <div class="flex h-full gap-6">
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            
            <div class="mb-6">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">🔍</span>
                    <input type="text" 
                           x-model="busqueda"
                           placeholder="Buscar producto por nombre, SKU o código de barras..." 
                           class="w-full bg-[#1f2937] text-white placeholder-gray-500 text-sm rounded-xl pl-11 pr-4 py-3 border border-gray-700 focus:outline-none focus:border-emerald-500 transition" />
                </div>
            </div>

            <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-thin">
                <button @click="categoriaSeleccionada = 'Todos'" 
                        :class="categoriaSeleccionada === 'Todos' ? 'bg-emerald-600 text-black font-bold' : 'bg-[#1f2937] text-gray-400 hover:text-white hover:bg-gray-800'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">Todos</button>

                <button @click="categoriaSeleccionada = 'Lácteos'" 
                        :class="categoriaSeleccionada === 'Lácteos' ? 'bg-emerald-600 text-black font-bold' : 'bg-[#1f2937] text-gray-400 hover:text-white hover:bg-gray-800'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">🥛 Lácteos</button>

                <button @click="categoriaSeleccionada = 'Panadería'" 
                        :class="categoriaSeleccionada === 'Panadería' ? 'bg-emerald-600 text-black font-bold' : 'bg-[#1f2937] text-gray-400 hover:text-white hover:bg-gray-800'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">🍞 Panadería</button>

                <button @click="categoriaSeleccionada = 'Bebidas'" 
                        :class="categoriaSeleccionada === 'Bebidas' ? 'bg-emerald-600 text-black font-bold' : 'bg-[#1f2937] text-gray-400 hover:text-white hover:bg-gray-800'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">🥤 Bebidas</button>

                <button @click="categoriaSeleccionada = 'Botanas'" 
                        :class="categoriaSeleccionada === 'Botanas' ? 'bg-emerald-600 text-black font-bold' : 'bg-[#1f2937] text-gray-400 hover:text-white hover:bg-gray-800'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">🍿 Botanas</button>

                <button @click="categoriaSeleccionada = 'Limpieza'" 
                        :class="categoriaSeleccionada === 'Limpieza' ? 'bg-emerald-600 text-black font-bold' : 'bg-[#1f2937] text-gray-400 hover:text-white hover:bg-gray-800'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">🧻 Limpieza</button>
            </div>

            <div class="flex-1 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 pr-1">
                <template x-for="producto in productosFiltrados" :key="producto.id">
                    <div @click="addToCart(producto)" 
                         class="bg-[#1f2937] border border-gray-700/60 rounded-2xl p-4 flex flex-col justify-between relative hover:border-emerald-500/50 cursor-pointer transition select-none group">
                        
                        <span class="absolute top-3 right-3 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md" 
                              x-text="producto.stock + ' pza'"></span>

                        <div class="mb-4">
                            <span class="text-[11px] text-gray-500 block font-medium uppercase tracking-wider mb-1" 
                                  x-text="producto.categoriaIcono + ' ' + producto.categoria"></span>
                            <h3 class="font-semibold text-white text-sm leading-snug group-hover:text-emerald-400 transition" 
                                x-text="producto.nombre"></h3>
                        </div>

                        <div class="text-lg font-bold text-white font-mono" x-text="'$' + producto.precio.toFixed(2)"></div>
                    </div>
                </template>
            </div>

        </div>

        <div class="w-96 bg-[#1f2937] rounded-2xl border border-gray-700 flex flex-col h-full overflow-hidden shadow-xl">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-[#1a222f]">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-white text-sm tracking-wide">Carrito Actual</h3>
                    <button x-show="cart.length > 0" @click="clearCart()" class="text-[10px] text-red-400 hover:underline">(Vaciar)</button>
                </div>
                <span class="bg-gray-800 text-gray-400 text-xs font-semibold px-2.5 py-1 rounded-md" 
                      x-text="totalArticulos + ' Artículos'"></span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-gray-500 space-y-2">
                        <span class="text-3xl">🛒</span>
                        <p class="text-xs">Toca un producto para agregarlo al carrito</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div class="bg-[#111827]/60 rounded-xl p-3 border border-gray-800 flex justify-between items-center">
                        <div class="flex-1 pr-2">
                            <h4 class="text-xs font-semibold text-white" x-text="item.nombre"></h4>
                            <span class="text-[11px] text-gray-400 font-mono" x-text="'$' + item.precio.toFixed(2) + ' c/u'"></span>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="decreaseQty(item)" class="w-6 h-6 rounded-lg bg-gray-800 text-gray-300 flex items-center justify-center font-bold text-xs hover:bg-gray-700">-</button>
                            <span class="text-xs font-bold text-white font-mono w-4 text-center" x-text="item.cantidad"></span>
                            <button @click="increaseQty(item)" class="w-6 h-6 rounded-lg bg-gray-800 text-gray-300 flex items-center justify-center font-bold text-xs hover:bg-gray-700">+</button>
                        </div>

                        <div class="text-right pl-3">
                            <span class="text-xs font-bold text-emerald-400 font-mono block" x-text="'$' + (item.precio * item.cantidad).toFixed(2)"></span>
                            <button @click="removeFromCart(item)" class="text-[10px] text-gray-500 hover:text-red-400">🗑️</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 border-t border-gray-700 bg-[#1a222f] space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-400">TOTAL</span>
                    <span class="text-2xl font-black text-emerald-400 font-mono" x-text="'$' + total.toFixed(2)"></span>
                </div>
                <button @click="recibido = total; openCobrar = true" 
                        :disabled="cart.length === 0"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 disabled:opacity-40 disabled:hover:bg-emerald-500 text-gray-950 font-bold py-3.5 px-4 rounded-xl text-sm transition tracking-wide flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10">
                    💰 Cobrar <span x-text="'$' + total.toFixed(2)"></span>
                </button>
            </div>
        </div>
    </div>

    <div x-show="openCobrar" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4" 
         style="display: none;">
        
        <div @click.away="openCobrar = false" class="bg-[#1f2937] border border-gray-700 w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl">
            
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-[#1a222f]">
                <h3 class="font-bold text-white text-base tracking-wide">Cobrar Venta</h3>
                <button @click="openCobrar = false" class="text-gray-400 hover:text-white transition">✕</button>
            </div>

            <div class="p-6 space-y-5">
                <div class="bg-[#111827]/60 rounded-xl p-4 border border-gray-800 text-xs space-y-1.5 text-gray-400 max-h-28 overflow-y-auto font-mono">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between">
                            <span x-text="item.cantidad + '× ' + item.nombre"></span>
                            <span class="text-gray-200 font-mono" x-text="'$' + (item.precio * item.cantidad).toFixed(2)"></span>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center border-b border-gray-700/60 pb-3">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">TOTAL A PAGAR</span>
                    <span class="text-3xl font-black text-emerald-400 font-mono" x-text="'$' + total.toFixed(2)"></span>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 tracking-wider uppercase">Método de Pago</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button @click="metodo = 'efectivo'" :class="metodo === 'efectivo' ? 'bg-emerald-600 text-black border-emerald-500 font-bold' : 'bg-[#111827] text-gray-400 border-gray-800'" class="flex flex-col items-center justify-center py-3 rounded-xl border text-xs transition gap-1">
                            <span>💵</span> Efectivo
                        </button>
                        <button @click="metodo = 'tarjeta'" :class="metodo === 'tarjeta' ? 'bg-emerald-600 text-black border-emerald-500 font-bold' : 'bg-[#111827] text-gray-400 border-gray-800'" class="flex flex-col items-center justify-center py-3 rounded-xl border text-xs transition gap-1">
                            <span>💳</span> Tarjeta
                        </button>
                        <button @click="metodo = 'transferencia'" :class="metodo === 'transferencia' ? 'bg-emerald-600 text-black border-emerald-500 font-bold' : 'bg-[#111827] text-gray-400 border-gray-800'" class="flex flex-col items-center justify-center py-3 rounded-xl border text-xs transition gap-1">
                            <span>🏦</span> Transferencia
                        </button>
                    </div>
                </div>

                <div x-show="metodo === 'efectivo'" class="space-y-3" x-transition>
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-gray-400 tracking-wider uppercase">Efectivo Recibido</label>
                        <div class="flex gap-2">
                            <button @click="recibido = Math.ceil(total / 50) * 50" class="px-3 py-1 text-[11px] font-bold rounded-lg bg-gray-800 text-gray-300 hover:bg-gray-700" x-text="'$' + (Math.ceil(total / 50) * 50)"></button>
                            <button @click="recibido = 200" class="px-3 py-1 text-[11px] font-bold rounded-lg bg-gray-800 text-gray-300 hover:bg-gray-700">$200</button>
                            <button @click="recibido = 500" class="px-3 py-1 text-[11px] font-bold rounded-lg bg-gray-800 text-gray-300 hover:bg-gray-700">$500</button>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-lg font-bold text-gray-500 font-mono">$</span>
                        <input type="number" x-model.number="recibido" class="w-full bg-[#111827] text-white font-mono font-bold text-xl rounded-xl pl-10 pr-4 py-3 border border-gray-800 focus:outline-none focus:border-emerald-500 text-right" />
                    </div>

                    <div class="bg-[#1a222f] rounded-xl p-4 flex justify-between items-center border border-gray-800">
                        <span class="text-xs font-bold text-gray-400">CAMBIO:</span>
                        <span class="text-xl font-bold font-mono text-emerald-400" x-text="'$' + (recibido - total >= 0 ? (recibido - total).toFixed(2) : '0.00')"></span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#1a222f] border-t border-gray-700">
                <button @click="finalizarVenta()" 
                        :disabled="metodo === 'efectivo' && recibido < total"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 disabled:opacity-40 text-gray-950 font-black py-4 px-4 rounded-xl text-sm tracking-wide uppercase transition shadow-lg shadow-emerald-500/10 flex items-center justify-center gap-2">
                    ✅ Finalizar Venta
                </button>
            </div>

        </div>
    </div>


    <div x-show="openTicket" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
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
                    <span x-text="ticket.folio"></span>
                </div>
                <div class="flex justify-between">
                    <span x-text="ticket.fecha"></span>
                    <span x-text="ticket.hora"></span>
                </div>
                <div class="flex justify-between">
                    <span>CAJERO:</span>
                    <span x-text="cajero"></span>
                </div>
            </div>

            <div class="border-b border-dashed border-gray-300 my-2"></div>

            <div>
                <div class="grid grid-cols-12 text-[10px] font-bold text-gray-400 uppercase mb-2">
                    <span class="col-span-7">PRODUCTO</span>
                    <span class="col-span-2 text-center">QTY</span>
                    <span class="col-span-3 text-right">TOTAL</span>
                </div>

                <div class="space-y-1.5 text-xs text-black font-semibold">
                    <template x-for="item in ticket.items" :key="item.id">
                        <div class="grid grid-cols-12 items-center">
                            <span class="col-span-7 truncate" x-text="item.nombre"></span>
                            <span class="col-span-2 text-center font-normal text-gray-600" x-text="item.cantidad"></span>
                            <span class="col-span-3 text-right font-bold" x-text="'$' + (item.precio * item.cantidad).toFixed(2)"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="border-b border-dashed border-gray-300 my-2"></div>

            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between items-center font-black text-base text-black">
                    <span>TOTAL</span>
                    <span class="text-xl" x-text="'$' + ticket.total.toFixed(2)"></span>
                </div>

                <div class="flex justify-between text-gray-600 text-[11px] pt-1">
                    <span>MÉTODO:</span>
                    <span class="font-bold text-black flex items-center gap-1">
                        <span x-show="ticket.metodo === 'efectivo'">💵 Efectivo</span>
                        <span x-show="ticket.metodo === 'tarjeta'">💳 Tarjeta</span>
                        <span x-show="ticket.metodo === 'transferencia'">🏦 Transferencia</span>
                    </span>
                </div>

                <template x-if="ticket.metodo === 'efectivo'">
                    <div class="space-y-1 text-[11px] text-gray-600">
                        <div class="flex justify-between">
                            <span>RECIBIDO:</span>
                            <span class="font-bold text-black" x-text="'$' + ticket.recibido.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between font-bold text-black text-xs">
                            <span>CAMBIO:</span>
                            <span x-text="'$' + ticket.cambio.toFixed(2)"></span>
                        </div>
                    </div>
                </template>
            </div>

            <div class="border-b border-dashed border-gray-300 my-2"></div>

            <div class="text-center text-xs font-bold text-gray-700 pt-1">
                <p>¡Gracias por su compra!</p>
                <p class="text-[11px] font-normal text-gray-500 mt-0.5">Vuelva pronto 😊</p>
            </div>

        </div>

        <div class="w-full max-w-sm mt-4">
            <button @click="nuevaVenta()" 
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-extrabold py-3.5 px-4 rounded-xl text-sm transition tracking-wide flex items-center justify-center gap-2 shadow-xl shadow-emerald-500/20">
                🛒 Nueva Venta
            </button>
        </div>

    </div>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection