<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Pantalla de Acceso al POS (Login)
Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

// 2. Interfaz del Punto de Venta (Carrito, Cobro y Modal de Pago)
Route::get('/pos', function () {
    return view('pos');
})->name('pos');

// 3. Gestión de Inventario (Tabla de stock, Alertas y Modal de alta/edición)
Route::get('/inventario', function () {
    return view('inventario');
})->name('inventario');

// 4. Dashboard de Estadísticas (KPIs de ventas, Métodos de pago y Filtros)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// 5. Corte de Caja y Reportes (Auditoría, Desglose por método y Registro de ventas)
Route::get('/reportes', function () {
    return view('reportes');
})->name('reportes');

// 6. Historial de Ventas y Auditoría de Tickets
Route::get('/ventas', function () {
    return view('ventas');
})->name('ventas');

// 7. Histórico de Productos Inactivos (Papelera)
Route::get('/productos-inactivos', function () {
    return view('productos-inactivos');
})->name('productos-inactivos');