<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea hacia la tabla de ventas (Borrado en cascada si se elimina la venta)
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            
            // Llave foránea hacia el catálogo de productos[cite: 1]
            $table->foreignId('product_id')->constrained();
            
            // Cantidad de unidades vendidas de este producto en particular[cite: 1]
            $table->integer('quantity');
            
            // Guardamos el precio de venta histórico al momento de la transacción[cite: 1]
            // Esto evita que si el precio del producto cambia en el futuro, los reportes viejos se alteren.
            $table->decimal('price', 10, 2); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};