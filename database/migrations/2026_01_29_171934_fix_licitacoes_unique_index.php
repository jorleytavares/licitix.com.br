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
            // Drop the existing unique index on codigo_radar
            $table->dropUnique(['codigo_radar']);
            
            // Add new composite unique index
            $table->unique(['codigo_radar', 'empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->dropUnique(['codigo_radar', 'empresa_id']);
            $table->unique(['codigo_radar']);
        });
    }
};
