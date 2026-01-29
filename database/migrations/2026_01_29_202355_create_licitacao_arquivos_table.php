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
        Schema::create('licitacao_arquivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacao_id')->constrained('licitacoes')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('url');
            $table->string('tamanho')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacao_arquivos');
    }
};
