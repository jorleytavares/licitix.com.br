<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->string('etapa_crm')->default('interesse')->after('status'); // interesse, em_analise, preparacao, proposta_enviada, resultado
            $table->integer('probabilidade_ganho')->default(0)->after('etapa_crm');
            $table->text('anotacoes_crm')->nullable()->after('probabilidade_ganho');
            $table->string('tarefa_atual')->nullable()->after('anotacoes_crm');
            $table->dateTime('data_vencimento_tarefa')->nullable()->after('tarefa_atual');
            $table->boolean('monitorada')->default(false)->after('etapa_crm'); // Para diferenciar do cache do radar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->dropColumn([
                'etapa_crm',
                'probabilidade_ganho',
                'anotacoes_crm',
                'tarefa_atual',
                'data_vencimento_tarefa',
                'monitorada'
            ]);
        });
    }
};
