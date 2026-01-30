<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductionUserSeeder extends Seeder
{
    public function run()
    {
        // 1. Criar Empresa
        $empresaId = DB::table('empresas')->insertGetId([
            'razao_social' => 'Licitix Matriz',
            'nome_fantasia' => 'Licitix',
            'cnpj' => '00000000000191',
            'status' => 'ativa',
            'plano' => 'basico',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Criar Usuário Admin
        DB::table('users')->insert([
            'empresa_id' => $empresaId,
            'nome' => 'Administrador',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_empresa',
            'ativo' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
