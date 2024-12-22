<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(name="Auth", description="Gestion de l'authentification utilisateur")
 */
class AuthController extends Controller
{

        /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Crée un nouveau compte utilisateur.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }


        /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Connecte un utilisateur existant en utilisant un email et un mot de passe.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User logged in successfully", @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="User logged in successfully"),
     *         @OA\Property(property="token", type="string", example="your_token_here")
     *     )),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Création du token d'accès pour l'utilisateur
        $token = $user->createToken('ISGIDocs')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token
        ]);
    }

        /**
     * @OA\Get(
     *     path="/api/auth/user",
     *     tags={"Auth"},
     *     summary="Get authenticated user details",
     *     description="Retourne les informations de l'utilisateur connecté.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="User details retrieved successfully", @OA\JsonContent(
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="name", type="string", example="John Doe"),
     *         @OA\Property(property="email", type="string", example="johndoe@example.com")
     *     )),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

        /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *     description="Déconnecte l'utilisateur en supprimant tous ses tokens.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="User logged out successfully", @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="User logged out successfully")
     *     )),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        // Supprimer tous les tokens de l'utilisateur
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
