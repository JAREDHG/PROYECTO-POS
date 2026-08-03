<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// RUTAS PÚBLICAS
// =========================================================================
Route::post('/login', [AuthController::class, 'login']);

// =========================================================================
// MOTOR POS - RUTAS PROTEGIDAS (Autenticación + Autorización)
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /**
     * RF01 - Gestión Centralizada de Inventario
     * Capa de seguridad: Solo usuarios con permiso 'manage products' (Admin)
     */
    Route::middleware('can:manage products')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });

    /**
     * RF02 - Motor Transaccional de Ventas
     * Capa de seguridad: Usuarios con permiso 'process sales' (Admin y Cajero)
     */
    Route::middleware('can:process sales')->group(function () {
        Route::post('/sales', [SaleController::class, 'store']);
        Route::get('/sales', [SaleController::class, 'index']);
    });
});