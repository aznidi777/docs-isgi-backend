<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Appeler les seeders dans l'ordre
        $this->call([
            UserSeeder::class,
            ModuleSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
