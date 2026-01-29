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
        Schema::table('proposta_itens', function (Blueprint $table) {
            $table->string('unidade', 10)->nullable()->after('quantidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposta_itens', function (Blueprint $table) {
            $table->dropColumn('unidade');
        });
    }
};
