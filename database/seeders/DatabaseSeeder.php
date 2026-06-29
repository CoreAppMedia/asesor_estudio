<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Materia;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear usuario administrador inicial si no existe
        if (!User::where('email', 'admin@cch.unam.mx')->exists()) {
            User::create([
                'name' => 'Profesor Administrador',
                'email' => 'admin@cch.unam.mx',
                'password' => bcrypt('password'),
            ]);
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists. Skipping.');
        }

        // 2. Cargar catálogos y preguntas solo si la materia principal no existe
        if (!Materia::where('nombre', 'Matemáticas CCH')->exists()) {
            $this->call([
                CatalogSeeder::class,
                PreguntaSeeder::class,
                ContentJsonSeeder::class,
            ]);
            $this->command->info('Database catalog and questions seeded successfully.');
        } else {
            $this->command->info('Database catalog already seeded. Skipping.');
        }
    }
}
