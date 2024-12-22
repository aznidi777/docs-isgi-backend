<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    protected $model = \App\Models\Module::class;

    public function definition()
    {
        return [
            'code' => strtoupper($this->faker->lexify('????') . $this->faker->randomNumber(3, true)), // Exemple: MATH101
            'nom' => $this->faker->sentence(3), // Exemple: "Introduction à l'algèbre"
            'description' => $this->faker->paragraph(), // Description fictive
            'annee' => $this->faker->numberBetween(1, 2), // Année 1 ou 2
        ];
    }
}
