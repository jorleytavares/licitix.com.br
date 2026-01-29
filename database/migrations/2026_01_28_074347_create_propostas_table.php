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
        Schema::create('propostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('licitacao_id')->constrained('licitacoes')->onDelete('cascade');
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->decimal('margem_lucro', 5, 2)->nullable(); // Porcentagem
            $table->date('validade_proposta')->nullable();
            $table->string('status')->default('rascunho'); // rascunho, enviada, ganha, perdida
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propostas');
    }
};
