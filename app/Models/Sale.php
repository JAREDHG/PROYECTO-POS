<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Campos permitidos para llenado masivo y protegidos contra inyecciones maliciosas
    protected $fillable = [
        'user_id', 
        'ticket_number', 
        'total', 
        'payment_method'
    ];

    /**
     * RF04: Trazabilidad y Auditoría de Turnos.
     * Vincula la venta directamente con el cajero/empleado que la procesó.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los artículos desglosados dentro del carrito virtual.
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}