@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Ventas Hoy</span>
                <h3 class="text-2xl font-black text-white font-mono">10</h3>
                <p class="text-[11px] text-gray-500 mt-1">Operaciones del turno actual</p>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-xl text-xl">📈</div>
        </div>

        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Ingresos Hoy</span>
                <h3 class="text-2xl font-black text-emerald-400 font-mono">$1,018.00</h3>
                <p class="text-[11px] text-gray-500 mt-1">Suma bruta de transacciones</p>
            </div>
            <div class="bg-emerald-500/10 p-3 rounded-xl text-xl">💰</div>
        </div>

        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Efectivo en Caja</span>
                <h3 class="text-2xl font-black text-amber-400 font-mono">$703.00</h3>
                <p class="text-[11px] text-gray-500 mt-1">Fondo disponible en efectivo</p>
            </div>
            <div class="bg-amber-500/10 p-3 rounded-xl text-xl">💵</div>
        </div>

        <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Stock Bajo</span>
                <h3 class="text-2xl font-black text-red-400 font-mono">4 <span class="text-xs font-normal text-gray-400">items</span></h3>
                <p class="text-[11px] text-red-400/80 mt-1">🔥 Requieren reabastecimiento</p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-xl text-xl">⚠️</div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-[#1f2937] border border-gray-700/70 rounded-2xl overflow-hidden shadow-xl flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-gray-700/60 flex justify-between items-center bg-[#1a222f]/50">
                    <h3 class="font-bold text-white text-base tracking-wide">Ventas Recientes</h3>
                    <a href="/dashboard" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                        Ver todas →
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
                                <th class="py-3 px-5 font-bold text-right">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/40 text-xs text-gray-200">
                            <tr class="hover:bg-[#111827]/30 transition">
                                <td class="py-3.5 px-5 font-mono text-gray-400">TK-A1B2C3</td>
                                <td class="py-3.5 px-5 font-medium text-white">María García</td>
                                <td class="py-3.5 px-5 font-mono font-bold text-white">$102.00</td>
                                <td class="py-3.5 px-5"><span class="bg-emerald-500/10 text-emerald-400 text-[11px] px-2.5 py-1 rounded-lg font-medium">💵 Efectivo</span></td>
                                <td class="py-3.5 px-5 text-right font-mono text-gray-400">10:51 p.m.</td>
                            </tr>
                            <tr class="hover:bg-[#111827]/30 transition">
                                <td class="py-3.5 px-5 font-mono text-gray-400">TK-D4E5F6</td>
                                <td class="py-3.5 px-5 font-medium text-white">Juan López</td>
                                <td class="py-3.5 px-5 font-mono font-bold text-white">$127.00</td>
                                <td class="py-3.5 px-5"><span class="bg-blue-500/10 text-blue-400 text-[11px] px-2.5 py-1 rounded-lg font-medium">💳 Tarjeta</span></td>
                                <td class="py-3.5 px-5 text-right font-mono text-gray-400">10:27 p.m.</td>
                            </tr>
                            <tr class="hover:bg-[#111827]/30 transition">
                                <td class="py-3.5 px-5 font-mono text-gray-400">TK-G7H8I9</td>
                                <td class="py-3.5 px-5 font-medium text-white">María García</td>
                                <td class="py-3.5 px-5 font-mono font-bold text-white">$76.00</td>
                                <td class="py-3.5 px-5"><span class="bg-emerald-500/10 text-emerald-400 text-[11px] px-2.5 py-1 rounded-lg font-medium">💵 Efectivo</span></td>
                                <td class="py-3.5 px-5 text-right font-mono text-gray-400">09:41 p.m.</td>
                            </tr>
                            <tr class="hover:bg-[#111827]/30 transition">
                                <td class="py-3.5 px-5 font-mono text-gray-400">TK-J0K1L2</td>
                                <td class="py-3.5 px-5 font-medium text-white">Juan López</td>
                                <td class="py-3.5 px-5 font-mono font-bold text-white">$92.00</td>
                                <td class="py-3.5 px-5"><span class="bg-purple-500/10 text-purple-400 text-[11px] px-2.5 py-1 rounded-lg font-medium">🏦 Transferencia</span></td>
                                <td class="py-3.5 px-5 text-right font-mono text-gray-400">08:59 p.m.</td>
                            </tr>
                            <tr class="hover:bg-[#111827]/30 transition">
                                <td class="py-3.5 px-5 font-mono text-gray-400">TK-M3N405</td>
                                <td class="py-3.5 px-5 font-medium text-white">María García</td>
                                <td class="py-3.5 px-5 font-mono font-bold text-white">$136.00</td>
                                <td class="py-3.5 px-5"><span class="bg-emerald-500/10 text-emerald-400 text-[11px] px-2.5 py-1 rounded-lg font-medium">💵 Efectivo</span></td>
                                <td class="py-3.5 px-5 text-right font-mono text-gray-400">08:14 p.m.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 bg-[#1f2937] border border-gray-700/70 rounded-2xl overflow-hidden shadow-xl flex flex-col p-5 justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-white text-base tracking-wide">Stock Bajo</h3>
                    <a href="/inventario" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                        Gestionar →
                    </a>
                </div>

                <div class="space-y-3">
                    
                    <div class="bg-[#111827]/40 p-3.5 rounded-xl border border-gray-800/80 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white leading-tight">Jabón Palmolive 150g</p>
                            <span class="text-[10px] font-mono text-gray-400">LIM001</span>
                        </div>
                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 font-mono text-xs font-bold px-2.5 py-1 rounded-lg">8 pzs</span>
                    </div>

                    <div class="bg-[#111827]/40 p-3.5 rounded-xl border border-gray-800/80 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white leading-tight">Ruffles 45g</p>
                            <span class="text-[10px] font-mono text-gray-400">BOT002</span>
                        </div>
                        <span class="bg-red-500/20 text-red-400 border border-red-500/30 font-mono text-xs font-bold px-2.5 py-1 rounded-lg">4 pzs</span>
                    </div>

                    <div class="bg-[#111827]/40 p-3.5 rounded-xl border border-gray-800/80 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white leading-tight">Pepsi 600ml</p>
                            <span class="text-[10px] font-mono text-gray-400">BEB004</span>
                        </div>
                        <span class="bg-red-500/20 text-red-400 border border-red-500/30 font-mono text-xs font-bold px-2.5 py-1 rounded-lg">3 pzs</span>
                    </div>

                    <div class="bg-[#111827]/40 p-3.5 rounded-xl border border-gray-800/80 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white leading-tight">Queso Oaxaca 400g</p>
                            <span class="text-[10px] font-mono text-gray-400">LAC003</span>
                        </div>
                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 font-mono text-xs font-bold px-2.5 py-1 rounded-lg">7 pzs</span>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="bg-[#1f2937] border border-gray-700/70 rounded-2xl p-5 shadow-xl">
        <h3 class="font-bold text-white text-base tracking-wide mb-4">Ventas por Método de Pago</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div class="bg-[#111827]/50 p-4 rounded-xl border border-gray-800 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💵</span>
                        <span class="text-sm font-semibold text-gray-200">Efectivo</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-white">$522.00</span>
                </div>
                <div>
                    <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden mb-2">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: 62%;"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-medium">5 operaciones · 62%</span>
                </div>
            </div>

            <div class="bg-[#111827]/50 p-4 rounded-xl border border-gray-800 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💳</span>
                        <span class="text-sm font-semibold text-gray-200">Tarjeta</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-white">$223.00</span>
                </div>
                <div>
                    <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden mb-2">
                        <div class="bg-blue-500 h-full rounded-full" style="width: 27%;"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-medium">2 operaciones · 27%</span>
                </div>
            </div>

            <div class="bg-[#111827]/50 p-4 rounded-xl border border-gray-800 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🏦</span>
                        <span class="text-sm font-semibold text-gray-200">Transferencia</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-white">$92.00</span>
                </div>
                <div>
                    <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden mb-2">
                        <div class="bg-purple-500 h-full rounded-full" style="width: 11%;"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-medium">1 operaciones · 11%</span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection