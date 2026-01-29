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
        Schema::table('propostas', function (Blueprint $table) {
            // Alterando a coluna status para string para aceitar qualquer valor definido no model
            // Isso é mais flexível do que ENUM e evita problemas futuros com novos status
            $table->string('status')->default('identificada')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            // Revertendo para o ENUM original (cuidado: dados incompatíveis podem causar erro)
            // Para segurança, vamos manter como string mas com o default antigo se possível,
            // ou idealmente não reverter se houver dados novos.
            // Aqui vamos tentar voltar ao ENUM original apenas se necessário.
            $table->enum('status', ['rascunho', 'enviada', 'ganha', 'perdida'])->default('rascunho')->change();
        });
    }
};
