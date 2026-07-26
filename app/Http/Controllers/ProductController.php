<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: "/products",
        summary: "Obtener inventario",
        description: "Devuelve la lista completa de productos disponibles",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Lista de productos obtenida correctamente")]
    public function index()
    {
        return response()->json(Product::all());
    }

    #[OA\Post(
        path: "/products",
        summary: "Registrar producto",
        description: "Añade un nuevo producto al catálogo",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["sku", "name", "purchase_price", "sale_price", "stock"],
            properties: [
                new OA\Property(property: "sku", type: "string", example: "PROD-001"),
                new OA\Property(property: "name", type: "string", example: "Teclado Mecánico"),
                new OA\Property(property: "purchase_price", type: "number", format: "float", example: 500.50),
                new OA\Property(property: "sale_price", type: "number", format: "float", example: 850.00),
                new OA\Property(property: "stock", type: "integer", example: 15)
            ]
        )
    )]
    #[OA\Response(response: 201, description: "Producto registrado en el sistema")]
    public function store(Request $request)
    {
        $data = $request->validate([
            'sku'            => 'required|string|unique:products,sku',
            'name'           => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
        ]);
        
        return response()->json(Product::create($data), 201);
    }
}