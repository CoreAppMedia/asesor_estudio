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

        // 2. Cargar catálogos si la materia principal no existe
        if (!Materia::where('nombre', 'Matemáticas CCH')->exists()) {
            $this->call([
                CatalogSeeder::class,
            ]);
            $this->command->info('Database catalog seeded successfully.');
        } else {
            $this->command->info('Database catalog already seeded. Skipping.');
        }

        // 3. Cargar preguntas si la tabla de preguntas está vacía
        if (\App\Models\Pregunta::count() === 0) {
            $this->call([
                PreguntaSeeder::class,
            ]);
            $this->command->info('Questions seeded successfully.');
        } else {
            $this->command->info('Questions already exist in the database. Skipping.');
        }

        // 4. Asegurar que los archivos JSON de contenido estén generados si no existen
        if (!file_exists(storage_path('content/matematicas_1_u1.json'))) {
            $this->call([
                ContentJsonSeeder::class,
            ]);
            $this->command->info('JSON files generated successfully.');
        } else {
            $this->command->info('JSON files already exist. Skipping generation to preserve Git content.');
        }

        // 5. Invalidar la caché de la lista de cursos para que la página de inicio cargue los nuevos datos de inmediato
        \Illuminate\Support\Facades\Cache::forget('cursos_lista_completa');
    }
}
