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
        Schema::create('proposta_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('propostas')->onDelete('cascade');
            $table->string('tipo'); // habilitacao, tecnica, etc.
            $table->string('caminho_arquivo');
            $table->string('nome_original');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposta_documentos');
    }
};
