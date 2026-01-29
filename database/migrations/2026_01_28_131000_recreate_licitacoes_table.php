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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('licitacoes');

        Schema::create('licitacoes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_radar')->unique();
            $table->string('orgao');
            $table->string('modalidade');
            $table->string('estado', 2);
            $table->string('municipio');
            $table->date('data_abertura');
            $table->date('data_encerramento');
            $table->text('objeto');
            $table->enum('origem_dado', ['real', 'simulado'])->default('real');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('licitacoes');
        Schema::enableForeignKeyConstraints();
    }
};
