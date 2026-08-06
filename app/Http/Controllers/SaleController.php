<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class SaleController extends Controller
{
    // =======================================================
    // 1. MÉTODO PARA EL HISTORIAL (GET)
    // =======================================================
    #[OA\Get(
        path: "/sales",
        summary: "Obtener historial de ventas",
        description: "Recupera todas las ventas registradas en el sistema, ordenadas de la más reciente a la más antigua, incluyendo el detalle de los productos vendidos.",
        security: [["sanctum" => []]],
        tags: ["Ventas"]
    )]
    #[OA\Response(
        response: 200,
        description: "Historial de ventas recuperado con éxito",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "boolean", example: true),
                new OA\Property(property: "message", type: "string", example: "Historial recuperado con éxito."),
                new OA\Property(
                    property: "data",
                    type: "array",
                    items: new OA\Items(type: "object")
                )
            ]
        )
    )]
    public function index()
{
    // Carga explícita del cajero (user) y los productos vendidos (items.product)
    $sales = Sale::with(['user', 'items.product'])->orderBy('created_at', 'desc')->get();

    return response()->json([
        'success' => true,
        'message' => 'Historial recuperado con éxito.',
        'data' => $sales
    ], 200);
}

    // =======================================================
    // 2. MÉTODO PARA PROCESAR LA VENTA (POST)
    // =======================================================
    #[OA\Post(
        path: "/sales",
        summary: "Procesar nueva venta",
        description: "Registra una transacción de venta (carrito) de forma atómica y descuenta el stock en tiempo real.",
        security: [["sanctum" => []]],
        tags: ["Ventas"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["payment_method", "items"],
            properties: [
                new OA\Property(property: "payment_method", type: "string", example: "Tarjeta de Crédito"),
                new OA\Property(
                    property: "items",
                    type: "array",
                    items: new OA\Items(
                        required: ["product_id", "quantity"],
                        properties: [
                            new OA\Property(property: "product_id", type: "integer", example: 1),
                            new OA\Property(property: "quantity", type: "integer", example: 2)
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201, 
        description: "Transacción completada exitosamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "boolean", example: true),
                new OA\Property(property: "message", type: "string", example: "Transacción completada exitosamente."),
                new OA\Property(
                    property: "ticket", 
                    type: "object",
                    properties: [
                        new OA\Property(property: "folio", type: "string", example: "TK-A1B2C3D4-1678901234"),
                        new OA\Property(property: "fecha", type: "string", example: "2026-07-25 14:30:00"),
                        new OA\Property(property: "cajero", type: "string", example: "Admin POS"),
                        new OA\Property(property: "total", type: "number", format: "float", example: 1500.50),
                        new OA\Property(property: "metodo_pago", type: "string", example: "Tarjeta de Crédito")
                    ]
                )
            ]
        )
    )]
    #[OA\Response(response: 400, description: "Error lógico (ej. stock insuficiente o falla en base de datos)")]
    #[OA\Response(response: 422, description: "Error de validación (ej. el producto no existe o formato inválido)")]
    public function store(Request $request)
    {
        // RNF02 - Prevención de Inyecciones y manipulación de precios mediante validación estricta del ORM
        $request->validate([
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // RNF01 - Transacciones Atómicas (Rollback): Si la luz o la red fallan, nada se guarda a medias
        DB::beginTransaction();

        try {
            $total = 0;
            $saleItemsData = [];

            // RF03 - Generación de folio único de operación para el ticket digital
            $ticketNumber = 'TK-' . strtoupper(Str::random(8)) . '-' . time();

            // Procesar los productos enviados en el "carrito" virtual
            foreach ($request->items as $item) {
                // Bloqueo de fila (lockForUpdate) para evitar que dos cajeros vendan el mismo producto al mismo tiempo
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                // Verificar si hay existencias suficientes en el inventario
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto: {$product->name}. Disponibles: {$product->stock}");
                }

                // Calcular subtotal usando SIEMPRE el precio guardado en el servidor (Seguridad RNF02)
                $subtotal = $product->sale_price * $item['quantity'];
                $total += $subtotal;

                // RF02 - Descontar las unidades del inventario en tiempo real
                $product->stock -= $item['quantity'];
                $product->save();

                // Preparar los datos del desglose para la inserción masiva posterior
                $saleItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->sale_price, // Guardamos el precio histórico exacto del momento
                ];
            }

            // RF04 - Trazabilidad y Auditoría: Registramos la venta amarrada al usuario en turno (o ID 1 por defecto)
            $sale = Sale::create([
                'user_id' => Auth::id() ?? 1, 
                'ticket_number' => $ticketNumber,
                'total' => $total,
                'payment_method' => $request->payment_method,
            ]);

            // Guardar el desglose de artículos asociados a la venta recién creada
            foreach ($saleItemsData as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);
            }

            // Si llegamos aquí sin errores, confirmamos de forma permanente todos los cambios en la DB
            DB::commit();

            // RF03 - Retornar la respuesta con la estructura completa del Ticket Digital
            return response()->json([
                'success' => true,
                'message' => 'Transacción completada exitosamente.',
                'ticket' => [
                    'folio' => $sale->ticket_number,
                    'fecha' => $sale->created_at->toDateTimeString(),
                    'cajero' => $sale->user ? $sale->user->name : 'Cajero General',
                    'items' => $sale->items()->with('product:id,name')->get(),
                    'total' => $sale->total,
                    'metodo_pago' => $sale->payment_method
                ]
            ], 201);

        } catch (\Exception $e) {
            // RNF01 - Si algo falló en el camino, se deshace todo automáticamente (no se descuenta stock ni se guarda la venta)
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}