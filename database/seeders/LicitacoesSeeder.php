<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Licitacao;
use Carbon\Carbon;

class LicitacoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar tabela antes de popular
        Schema::disableForeignKeyConstraints();
        DB::table('licitacoes')->truncate();
        Schema::enableForeignKeyConstraints();

        // Licitacao de exemplo específica
        Licitacao::create([
            'codigo_radar' => 'RAD-2026-00001',
            'orgao' => 'Prefeitura Municipal de São Paulo',
            'modalidade' => 'Pregão Eletrônico',
            'estado' => 'SP',
            'municipio' => 'São Paulo',
            'data_abertura' => now()->addDays(10),
            'data_encerramento' => now()->addDays(15),
            'objeto' => 'Aquisição de materiais hospitalares',
            'origem_dado' => 'simulado',
        ]);
    }
}
