<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar Empresa
        $empresa = Empresa::create([
            'razao_social' => 'Licitix Soluções LTDA',
            'nome_fantasia' => 'Licitix',
            'cnpj' => '00.000.000/0001-00',
            'plano' => 'enterprise',
            'status' => 'ativa',
        ]);

        // Criar Usuário Admin
        User::create([
            'nome' => 'Administrador',
            'email' => 'admin@licitix.com.br',
            'password' => Hash::make('password'),
            'empresa_id' => $empresa->id,
            'role' => 'admin_empresa',
            'ativo' => true,
        ]);

        // Criar Usuário Teste (para o usuário logar se preferir)
        User::create([
            'nome' => 'Usuário Teste',
            'email' => 'contato@hostamazonas.com.br',
            'password' => Hash::make('password'),
            'empresa_id' => $empresa->id,
            'role' => 'admin_empresa',
            'ativo' => true,
        ]);
    }
}
