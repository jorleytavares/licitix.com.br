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
            $table->text('informacao_complementar')->nullable()->after('objeto');
            // Alterar origem_dado para string para aceitar 'pncp', 'comprasnet', etc.
            // Como alterar enum é complexo em migrations simples, vamos usar DB::statement ou modificar a coluna se possível
            // Vamos apenas adicionar a coluna se ela não existir ou modificá-la
            $table->string('origem_dado', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->dropColumn('informacao_complementar');
            // Reverter para enum seria o ideal, mas pode dar erro se tiver dados incompatíveis
            // $table->enum('origem_dado', ['real', 'simulado'])->change();
        });
    }
};
