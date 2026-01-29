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
        Schema::table('licitacao_itens', function (Blueprint $table) {
            $table->string('numero_item')->nullable()->after('licitacao_id');
            $table->string('unidade')->nullable()->after('quantidade');
            $table->decimal('valor_referencia', 15, 2)->default(0)->after('unidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacao_itens', function (Blueprint $table) {
            $table->dropColumn(['numero_item', 'unidade', 'valor_referencia']);
        });
    }
};
