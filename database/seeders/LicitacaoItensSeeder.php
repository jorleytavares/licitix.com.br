<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LicitacaoItem;

class LicitacaoItensSeeder extends Seeder
{
    public function run()
    {
        LicitacaoItem::create([
            'licitacao_id' => 1,
            'descricao' => 'Luvas cirúrgicas descartáveis',
            'codigo_catser' => '123456',
            'quantidade' => 10000
        ]);
    }
}
