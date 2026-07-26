<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar de forma masiva.
     * RF01 - Campos clave del catálogo de inventario.
     */
    protected $fillable = [
        'sku', 
        'name', 
        'purchase_price', 
        'sale_price', 
        'stock'
    ];
}