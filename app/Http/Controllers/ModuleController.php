<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Module", description="Gestion des modules de formation")
 */
class ModuleController extends Controller
{

    public function index()
    {
        $modules = Module::all();
        return response()->json($modules, 200);
    }


    public function show($id)
    {
        $module = Module::with('documents')->find($id);

        if (!$module) {
            return response()->json(['message' => 'Module non trouvé'], 404);
        }

        return response()->json($module, 200);
    }


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
