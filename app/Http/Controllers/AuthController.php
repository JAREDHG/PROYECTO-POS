<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", description: "Documentación interactiva de la API", title: "API POS Seguro")]
#[OA\Server(url: L5_SWAGGER_CONST_HOST, description: "Servidor API Principal")]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    name: "Token de Acceso",
    in: "header",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class AuthController extends Controller
{
    #[OA\Post(
        path: "/login",
        summary: "Iniciar sesión",
        description: "Autentica al usuario y devuelve un token de Sanctum",
        tags: ["Autenticación"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["email", "password"],
            properties: [
                new OA\Property(property: "email", type: "string", format: "email", example: "admin@pos.com"),
                new OA\Property(property: "password", type: "string", format: "password", example: "password123")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Login exitoso",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "message", type: "string", example: "Login exitoso"),
                new OA\Property(property: "access_token", type: "string", example: "1|abc123def456ghi789..."),
                new OA\Property(property: "token_type", type: "string", example: "Bearer")
            ]
        )
    )]
    #[OA\Response(response: 422, description: "Credenciales incorrectas")]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('pos_auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    #[OA\Post(
        path: "/logout",
        summary: "Cerrar sesión",
        security: [["sanctum" => []]],
        tags: ["Autenticación"]
    )]
    #[OA\Response(response: 200, description: "Sesión cerrada correctamente")]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}