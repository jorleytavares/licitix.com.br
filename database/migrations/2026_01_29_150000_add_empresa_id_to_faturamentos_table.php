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
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->onDelete('cascade');
        });

        // Opcional: Popular dados existentes (se houver) baseados na proposta
        // DB::statement("UPDATE faturamentos f JOIN propostas p ON f.proposta_id = p.id SET f.empresa_id = p.empresa_id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
