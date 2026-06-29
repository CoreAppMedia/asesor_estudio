<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador inicial
        User::create([
            'name' => 'Profesor Administrador',
            'email' => 'admin@cch.unam.mx',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            CatalogSeeder::class,
            PreguntaSeeder::class,
            ContentJsonSeeder::class,
        ]);
    }
}
