<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: "/products",
        summary: "Obtener inventario activo",
        description: "Devuelve la lista completa de productos activos disponibles",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Lista de productos obtenida correctamente")]
    public function index()
    {
        return response()->json(Product::where('is_active', true)->get());
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
            'category'       => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
        ]);
        
        return response()->json(Product::create($data), 201);
    }

    #[OA\Put(
        path: "/products/{id}",
        summary: "Actualizar producto",
        description: "Modifica los datos de un producto existente",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Producto actualizado correctamente")]
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku'            => 'required|string|unique:products,sku,' . $product->id,
            'name'           => 'required|string',
            'category'       => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
        ]);

        $product->update($data);

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'product' => $product
        ]);
    }

    #[OA\Delete(
        path: "/products/{id}",
        summary: "Desactivar producto",
        description: "Realiza una baja lógica del producto en el inventario",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Producto desactivado correctamente")]
    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        $product->save();

        return response()->json([
            'message' => 'Producto desactivado correctamente',
            'product' => $product
        ], 200);
    }

    #[OA\Get(
        path: "/products/inactive",
        summary: "Obtener productos inactivos",
        description: "Devuelve los productos dados de baja lógicamente",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Lista de productos inactivos obtenida correctamente")]
    public function inactive()
    {
        return response()->json(Product::where('is_active', false)->get());
    }

    #[OA\Put(
        path: "/products/{id}/restore",
        summary: "Reactivar producto",
        description: "Restaura un producto dado de baja lógica al inventario activo",
        security: [["sanctum" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Producto reactivado correctamente")]
    public function restore(Product $product)
    {
        $product->update(['is_active' => true]);

        return response()->json([
            'message' => 'Producto reactivado correctamente',
            'product' => $product
        ], 200);
    }
}