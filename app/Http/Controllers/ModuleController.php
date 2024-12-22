<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Module", description="Gestion des modules de formation")
 */
class ModuleController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/modules",
     *     tags={"Module"},
     *     summary="Liste tous les modules",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des modules récupérée avec succès",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Module"))
     *     )
     * )
     */
    public function index()
    {
        $modules = Module::all();
        return response()->json($modules, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/modules/{id}",
     *     tags={"Module"},
     *     summary="Récupère un module par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du module",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du module récupérés avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Module")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Module non trouvé"
     *     )
     * )
     */
    public function show($id)
    {
        $module = Module::with('documents')->find($id);

        if (!$module) {
            return response()->json(['message' => 'Module non trouvé'], 404);
        }

        return response()->json($module, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/modules",
     *     tags={"Module"},
     *     summary="Ajoute un nouveau module",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="MATH101"),
     *             @OA\Property(property="nom", type="string", example="Mathématiques 1"),
     *             @OA\Property(property="description", type="string", example="Introduction aux mathématiques"),
     *             @OA\Property(property="annee", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Module ajouté avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Module")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données non valides"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:modules,code',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'annee' => 'required|in:1,2',
        ]);

        $module = Module::create($validatedData);

        return response()->json(['message' => 'Module ajouté avec succès', 'module' => $module], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/modules/{id}",
     *     tags={"Module"},
     *     summary="Modifie un module existant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du module à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="MATH101"),
     *             @OA\Property(property="nom", type="string", example="Mathématiques avancées"),
     *             @OA\Property(property="description", type="string", example="Cours avancé de mathématiques"),
     *             @OA\Property(property="annee", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Module modifié avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Module")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Module non trouvé"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10',
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'annee' => 'required|in:1,2',
        ]);

        $module = Module::findOrFail($id);
        $module->update($validatedData);

        return response()->json([
            'message' => 'Module modifié avec succès',
            'module' => $module,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/modules/{id}",
     *     tags={"Module"},
     *     summary="Supprime un module",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du module à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Module supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Module non trouvé"
     *     )
     * )
     */
    public function destroy($id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['message' => 'Module non trouvé'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Module supprimé avec succès']);
    }
}
