<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criação de um usuário admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('senha123'),
            'perfil' => 2, // 2 = Admin
        ]);

        // Lista de países
        $paises = [
            'Brasil',
            'Argentina',
            'Estados Unidos',
            'Canadá',
            'Alemanha',
            'França',
            'Japão',
            'China',
            'Austrália',
            'Portugal',
            'Espanha',
            'Itália',
            'México',
            'Reino Unido',
            'Índia'
        ];

        // Inserção dos países
        foreach ($paises as $pais) {
            DB::table('pais')->insert([
                'nome' => $pais,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
