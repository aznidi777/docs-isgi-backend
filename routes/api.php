<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\DocumentController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

/**
 * @OA\PathItem(path="/auth")
 */
Route::prefix('auth')->group(function () {
    // Route pour l'inscription
    Route::post('register', [AuthController::class, 'register']);

    // Route pour la connexion
    Route::post('login', [AuthController::class, 'login']);

    // Route pour obtenir les informations de l'utilisateur connecté
    Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'user']);

    // Route pour la déconnexion (révocation du token)
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});




/*
|--------------------------------------------------------------------------
| Modules
|--------------------------------------------------------------------------
*/

/**
 * @OA\PathItem(path="/modules")
 */
Route::prefix('modules')->group(function () {
    Route::get('/', [ModuleController::class, 'index']); // Liste des modules
    Route::get('/{id}', [ModuleController::class, 'show']); // Détails d'un module
    Route::post('/', [ModuleController::class, 'store']); // Ajouter un module
    Route::put('/{id}', [ModuleController::class, 'update']); // Modifier un module
    Route::delete('/{id}', [ModuleController::class, 'destroy']); // Supprimer un module
});





/*
|--------------------------------------------------------------------------
| Documents
|--------------------------------------------------------------------------
*/


/**
 * @OA\PathItem(path="/documents")
 */
Route::prefix('documents')->group(function () {
    Route::post('/', [DocumentController::class, 'store']); // Ajouter un document
    Route::get('/{id}/download', [DocumentController::class, 'download']); // Télécharger un document
    Route::get('/search', [DocumentController::class, 'search']); // Rechercher des documents
    Route::get('/', [DocumentController::class, 'index']); // Liste paginée des documents
    Route::get('/{id}', [DocumentController::class, 'show']); // Voir un document spécifique
    Route::put('/{id}', [DocumentController::class, 'update']); // Modifier un document
    Route::delete('/{id}', [DocumentController::class, 'destroy']); // Supprimer un document
});

