<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmpresaSeeder extends Seeder
{
    public function run()
    {
        // Verifica se já existe para não duplicar ou dar erro
        $empresa = Empresa::firstOrCreate(
            ['cnpj' => '12.345.678/0001-90'],
            [
                'razao_social' => 'LICITIX TECNOLOGIA LTDA',
                'nome_fantasia' => 'Licitix',
                'plano' => 'enterprise',
                'status' => 'ativa'
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@licitix.local'],
            [
                'empresa_id' => $empresa->id,
                'nome' => 'Administrador',
                'password' => Hash::make('123456'),
                'role' => 'admin_empresa',
                'ativo' => true
            ]
        );
    }
}
