<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API POS Seguro",
 *      description="Documentación interactiva de la API del Sistema de Punto de Venta"
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Servidor API Principal"
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Usa el access_token obtenido en el login",
 *     name="Token de Acceso",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 */
abstract class Controller
{
    //
}