<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

/*
|-----------------------------------------------------------------------
| API Routes
|-----------------------------------------------------------------------
*/

/*
|-----------------------------------------------------------------------
| Authentification
|-----------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'user']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

/*
|-----------------------------------------------------------------------
| Modules - Accès pour utilisateurs connectés / Admins
|-----------------------------------------------------------------------
*/
Route::prefix('modules')->middleware('auth:sanctum')->group(function () {
    // Lecture accessible à tous les utilisateurs connectés
    Route::get('/', [ModuleController::class, 'index']); // Voir tous les modules
    Route::get('/{id}', [ModuleController::class, 'show']); // Voir un module spécifique

    // Modification réservée aux admins
    Route::middleware('admin')->group(function () {
        Route::post('/', [ModuleController::class, 'store']); // Ajouter un module
        Route::put('/{id}', [ModuleController::class, 'update']); // Modifier un module
        Route::delete('/{id}', [ModuleController::class, 'destroy']); // Supprimer un module
    });
});

/*
|-----------------------------------------------------------------------
| Documents - Accès pour utilisateurs connectés / Admins
|-----------------------------------------------------------------------
*/
Route::prefix('documents')->middleware('auth:sanctum')->group(function () {
    // Lecture accessible à tous les utilisateurs connectés
    Route::get('/', [DocumentController::class, 'index']); // Liste des documents
    Route::get('/{id}', [DocumentController::class, 'show']); // Voir un document
    Route::get('/{id}/download', [DocumentController::class, 'download']); // Télécharger un document
    Route::get('/search', [DocumentController::class, 'search']); // Rechercher des documents

    // Modification réservée aux admins
    Route::middleware('admin')->group(function () {
        Route::post('/', [DocumentController::class, 'store']); // Ajouter un document
        Route::put('/{id}', [DocumentController::class, 'update']); // Modifier un document
        Route::delete('/{id}', [DocumentController::class, 'destroy']); // Supprimer un document
    });
});
