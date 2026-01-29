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

        Schema::create('financeiro_licitacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proposta_id')->constrained()->cascadeOnDelete();
            $table->decimal('valor_contrato', 14, 2);
            $table->decimal('valor_faturado', 14, 2)->default(0);
            $table->decimal('valor_recebido', 14, 2)->default(0);
            $table->decimal('saldo', 14, 2)->default(0);
            $table->enum('status_pagamento', ['pendente', 'parcial', 'recebido'])->default('pendente');
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
        Schema::dropIfExists('financeiro_licitacoes');
        Schema::enableForeignKeyConstraints();
    }
};
