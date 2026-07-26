<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class RealProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Borramos los datos respetando la jerarquía relacional
        \App\Models\SaleItem::query()->delete(); // Primero los detalles de la venta
        \App\Models\Sale::query()->delete();     // Luego los tickets de venta
        Product::query()->delete();              // Finalmente los productos

        // 2. Definimos el catálogo real de tu frontend
        $productos = [
            [
                'sku' => 'LAC001',
                'name' => 'Leche Lala 1L',
                'purchase_price' => 18.00,
                'sale_price' => 24.00,
                'stock' => 48,
            ],
            [
                'sku' => 'PAN001',
                'name' => 'Pan Bimbo Grande',
                'purchase_price' => 42.00,
                'sale_price' => 55.00,
                'stock' => 12,
            ],
            [
                'sku' => 'BEB001',
                'name' => 'Coca-Cola 600ml',
                'purchase_price' => 12.00,
                'sale_price' => 18.00,
                'stock' => 71,
            ],
            [
                'sku' => 'BOT001',
                'name' => 'Sabritas Clásicas 45g',
                'purchase_price' => 10.00,
                'sale_price' => 15.00,
                'stock' => 35,
            ],
            [
                'sku' => 'LIM001',
                'name' => 'Jabón Palmolive 150g',
                'purchase_price' => 20.00,
                'sale_price' => 28.00,
                'stock' => 8,
            ],
            [
                'sku' => 'BAS001',
                'name' => 'Arroz Morelos 1kg',
                'purchase_price' => 24.00,
                'sale_price' => 34.00,
                'stock' => 25,
            ]
        ];

        // 3. Insertamos cada producto en la base de datos
        foreach ($productos as $producto) {
            Product::create($producto);
        }
    }
}