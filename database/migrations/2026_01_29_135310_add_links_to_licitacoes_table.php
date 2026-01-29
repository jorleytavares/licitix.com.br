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
            $table->text('link_sistema_origem')->nullable()->after('origem_dado');
            $table->text('link_original')->nullable()->after('link_sistema_origem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->dropColumn(['link_sistema_origem', 'link_original']);
        });
    }
};
