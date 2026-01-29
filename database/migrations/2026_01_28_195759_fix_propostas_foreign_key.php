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
            // Drop the incorrect foreign key referencing 'licitacaos'
            $table->dropForeign('propostas_licitacao_id_foreign');
            
            // Add the correct foreign key referencing 'licitacoes'
            $table->foreign('licitacao_id', 'propostas_licitacao_id_foreign')
                  ->references('id')
                  ->on('licitacoes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            $table->dropForeign('propostas_licitacao_id_foreign');
        });
    }
};
