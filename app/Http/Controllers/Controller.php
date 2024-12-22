<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="ISGIDocs API",
 *      description="ISGIDocs est une API conçue pour gérer les ressources pédagogiques et les formations de l'Institut Supérieur de Génie Informatique. Cette API permet aux utilisateurs de gérer les modules, les documents pédagogiques, et l'authentification des utilisateurs pour une expérience d'auto-formation fluide et organisée.",
 *      @OA\Contact(
 *          email="salahaznidi09@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/",
 *     description="Home",
 *     @OA\Response(response="default", description="Welcome page")
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="bearerAuth",
 *     bearerFormat="JWT",
 *     description="Entrer un token d'accès après avoir été authentifié."
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
