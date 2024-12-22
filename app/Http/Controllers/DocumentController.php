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
    /**
     * @OA\Get(
     *     path="/api/documents",
     *     tags={"Document"},
     *     summary="Liste tous les documents",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des documents récupérée avec succès",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Document"))
     *     )
     * )
     */
    public function index()
    {
        $documents = Document::all();
        return response()->json($documents, 200);
    }


    /**
     * @OA\Get(
     *     path="/api/documents/{id}",
     *     tags={"Document"},
     *     summary="Récupère un document par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du document",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du document récupérés avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Document")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document non trouvé"
     *     )
     * )
     */
    public function show($id)
    {
        $document = Document::with('module')->find($id);

        if (!$document) {
            return response()->json(['message' => 'Document non trouvé'], 404);
        }

        return response()->json($document);
    }

    /**
     * @OA\Post(
     *     path="/api/documents",
     *     tags={"Document"},
     *     summary="Ajoute un nouveau document",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="DOC101"),
     *             @OA\Property(property="nom", type="string", example="Document de Mathématiques 1"),
     *             @OA\Property(property="libelle", type="string", example="Introduction aux mathématiques"),
     *             @OA\Property(property="description", type="string", example="Document sur les bases des mathématiques"),
     *             @OA\Property(property="chemin", type="string", example="documents/math101.pdf"),
     *             @OA\Property(property="module_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Document ajouté avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Document")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données non valides"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validation des données du fichier
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',  // 10 MB max
            'module_id' => 'required|exists:modules,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Stocker le fichier dans storage/public/documents
        $filePath = $request->file('file')->store('documents', 'public');

        // Créer un nouvel enregistrement pour le document
        $document = Document::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'chemin' => $filePath,  // Enregistrer le chemin relatif du fichier
            'module_id' => $validated['module_id'],
            'downloads' => 0,
        ]);

        return response()->json(['document' => $document], 201);
    }



    /**
     * @OA\Put(
     *     path="/api/documents/{id}",
     *     tags={"Document"},
     *     summary="Modifie un document existant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du document à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="DOC101"),
     *             @OA\Property(property="nom", type="string", example="Document de Mathématiques avancées"),
     *             @OA\Property(property="libelle", type="string", example="Introduction aux mathématiques avancées"),
     *             @OA\Property(property="description", type="string", example="Document mis à jour pour le cours de mathématiques avancées"),
     *             @OA\Property(property="chemin", type="string", example="documents/math101-updated.pdf"),
     *             @OA\Property(property="module_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document modifié avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Document")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document non trouvé"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/documents/{id}",
     *     tags={"Document"},
     *     summary="Supprime un document",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du document à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document non trouvé"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/documents/{id}/download",
     *     tags={"Document"},
     *     summary="Télécharge un document",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du document à télécharger",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Téléchargement du document réussi"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document non trouvé"
     *     )
     * )
     */
    public function download($id)
    {
        // Récupérer le document par ID
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document non trouvé avec ce id'], 404);
        }

        // Vérifier si le fichier existe dans storage/app/public/documents
        $filePath = storage_path('app/public/' . $document->chemin);

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'Fichier non trouvé'], 404);
        }

        // Retourner le fichier pour téléchargement
        return response()->download($filePath, $document->nom . '.' . pathinfo($document->chemin, PATHINFO_EXTENSION));
    }



    /**
     * @OA\Get(
     *     path="/api/documents/search",
     *     tags={"Document"},
     *     summary="Rechercher des documents",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         description="Terme de recherche pour le nom ou la description du document",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Documents trouvés",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Document"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Aucun terme de recherche fourni"
     *     )
     * )
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['message' => 'Aucun terme de recherche fourni'], 400);
        }

        // Rechercher les documents qui contiennent le terme dans le nom ou la description
        $documents = Document::where('nom', 'LIKE', "%$query%")
                             ->orWhere('description', 'LIKE', "%$query%")
                             ->with('module')
                             ->paginate(10); // 10 résultats par page

        return response()->json($documents);
    }
}
