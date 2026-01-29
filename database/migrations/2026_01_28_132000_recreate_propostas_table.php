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

        Schema::dropIfExists('propostas');

        Schema::create('propostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('licitacao_id')->constrained()->cascadeOnDelete();
            $table->string('codigo')->unique();
            $table->enum('status', ['rascunho', 'enviada', 'ganha', 'perdida'])->default('rascunho');
            $table->decimal('valor_total', 14, 2)->default(0);
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
        Schema::dropIfExists('propostas');
        Schema::enableForeignKeyConstraints();
    }
};
