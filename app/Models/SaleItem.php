<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    // Permitimos la asignación masiva de los campos requeridos para el desglose (RF02)
    protected $fillable = [
        'sale_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    /**
     * Relación: Cada artículo del detalle pertenece a un producto del inventario (RF01)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}