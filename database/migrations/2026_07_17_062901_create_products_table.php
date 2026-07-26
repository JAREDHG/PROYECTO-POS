<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * RF01 - Gestión Centralizada de Inventario
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();          // SKU o Código de barras único (RF01)
            $table->string('name');                   // Nombre del producto (RF01)
            $table->decimal('purchase_price', 10, 2); // Precio de compra (RF01)
            $table->decimal('sale_price', 10, 2);     // Precio de venta (RF01)
            $table->integer('stock')->default(0);     // Existencias actuales (RF01)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};