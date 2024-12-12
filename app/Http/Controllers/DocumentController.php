<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(name="Document", description="Gestion des documents de formation")
 */
class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::with('module')->paginate(10); // 10 documents par page, avec leurs modules associés
        return response()->json($documents);
    }


    public function show($id)
    {
        $document = Document::with('module')->find($id);

        if (!$document) {
            return response()->json(['message' => 'Document non trouvé'], 404);
        }

        return response()->json($document);
    }


    public function update(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document non trouvé'], 404);
        }

        $validatedData = $request->validate([
            'code' => 'sometimes|string|max:10',
            'nom' => 'sometimes|string|max:255',
            'libelle' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'chemin' => 'sometimes|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'module_id' => 'sometimes|exists:modules,id',
        ]);

        // Mise à jour du fichier si fourni
        if ($request->hasFile('chemin')) {
            // Supprime l'ancien fichier
            Storage::disk('public')->delete($document->chemin);

            // Sauvegarde du nouveau fichier
            $file = $request->file('chemin');
            $path = $file->store('documents', 'public');
            $validatedData['chemin'] = $path;
        }

        // Met à jour le document
        $document->update($validatedData);

        return response()->json(['message' => 'Document mis à jour avec succès', 'document' => $document]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10',
            'nom' => 'required|string|max:255',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'chemin' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'module_id' => 'required|exists:modules,id', // Vérifie si le module existe
        ]);

        // Gérer le fichier
        if ($request->hasFile('chemin')) {
            $file = $request->file('chemin');
            $path = $file->store('documents', 'public'); // Stockage dans storage/app/public/documents
            $validatedData['chemin'] = $path; // Enregistrer le chemin
        }

        // Créer le document
        $document = Document::create($validatedData);

        return response()->json([
            'message' => 'Document ajouté avec succès',
            'document' => $document,
        ], 201);
    }

    
    public function destroy($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document non trouvé'], 404);
        }

        // Supprime le fichier
        Storage::disk('public')->delete($document->chemin);

        // Supprime le document
        $document->delete();

        return response()->json(['message' => 'Document supprimé'], 200);
    }
}
