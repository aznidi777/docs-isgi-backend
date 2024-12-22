<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérez tous les modules existants
        $modules = Module::all();

        // Pour chaque module, créez 5 documents fictifs
        foreach ($modules as $module) {
            Document::factory(5)->create([
                'module_id' => $module->id, // Associez les documents à un module
            ]);
        }
    }
}
