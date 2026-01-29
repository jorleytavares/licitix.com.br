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
        Schema::create('proposta_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('propostas')->onDelete('cascade');
            $table->foreignId('licitacao_item_id')->constrained('licitacao_itens')->onDelete('cascade');
            $table->decimal('valor_unitario', 15, 2);
            $table->decimal('valor_total', 15, 2); // Calculado (qtd * unitario)
            $table->string('marca')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposta_itens');
    }
};
