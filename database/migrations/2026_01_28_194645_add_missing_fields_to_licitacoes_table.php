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
            $table->string('numero_edital')->nullable()->after('orgao');
            $table->decimal('valor_estimado', 15, 2)->nullable()->after('objeto');
            $table->string('status')->default('aberta')->after('valor_estimado');
            $table->date('data_encerramento')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->dropColumn(['numero_edital', 'valor_estimado', 'status']);
            // We cannot easily revert nullable change without knowing previous state exactly, but we can assume it was not nullable
            $table->date('data_encerramento')->nullable(false)->change();
        });
    }
};
