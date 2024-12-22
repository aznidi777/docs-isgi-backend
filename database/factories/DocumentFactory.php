<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DocumentFactory extends Factory
{
    protected $model = \App\Models\Document::class;

    public function definition()
    {
        // Générer un chemin pour le fichier PDF dans storage/public/documents
        $filePath = 'documents/' . Str::random(10) . '.pdf';

        // Créer un fichier fictif dans le dossier public
        Storage::disk('public')->put($filePath, $this->faker->text(200));

        return [
            'code' => strtoupper(Str::random(5)),
            'nom' => $this->faker->words(3, true),
            'libelle' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'chemin' => $filePath,  // Stocker le chemin relatif dans la base de données
            'module_id' => \App\Models\Module::factory(),
            'downloads' => $this->faker->numberBetween(0, 100),
        ];
    }
}
