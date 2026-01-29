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
        Schema::create('faturamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('propostas')->onDelete('cascade');
            $table->string('numero_nf')->nullable();
            $table->decimal('valor', 15, 2);
            $table->date('data_emissao'); // Data do faturamento
            $table->date('data_vencimento')->nullable();
            $table->date('data_pagamento')->nullable(); // Recebimento
            $table->string('comprovante_url')->nullable();
            $table->string('status')->default('pendente'); // pendente, pago, atrasado, cancelado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamentos');
    }
};
