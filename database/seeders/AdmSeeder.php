<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Adicionando esta linha

class AdmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        DB::table('users')->insert([
            'name' => 'AdmUsuario',
            'email' => 'pedro.soares3@estudante.ifto.edu.br',
            'password' => Hash::make('AdmSenha'),
            'adm' => '1',
        ]);

        DB::table('users_adm')->insert([
            'user_id' => '1', // substitua por um ID de usuário válido
            'status' => 'approved',
            'reason' => 'Admnistrador principal.',
        ]);
    }
}
