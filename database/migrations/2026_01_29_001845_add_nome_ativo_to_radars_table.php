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
        Schema::table('radars', function (Blueprint $table) {
            $table->string('nome')->after('empresa_id');
            $table->boolean('ativo')->default(true)->after('filtros');
            $table->string('codigo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radars', function (Blueprint $table) {
            $table->dropColumn(['nome', 'ativo']);
            $table->string('codigo')->nullable(false)->change();
        });
    }
};
