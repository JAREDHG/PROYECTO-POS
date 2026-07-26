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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // RF04 - Trazabilidad de turnos vinculando al empleado/cajero
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            // RF03 - Folio único de la operación para el ticket digital
            $table->string('ticket_number')->unique(); 
            
            // RF02 - Motor transaccional (Monto total de la venta)
            $table->decimal('total', 10, 2); 
            
            // RF02 - Registro del método de pago (Efectivo, Tarjeta, etc.)
            $table->string('payment_method'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};