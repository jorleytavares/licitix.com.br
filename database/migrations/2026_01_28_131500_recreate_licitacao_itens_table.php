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

        Schema::dropIfExists('licitacao_itens');

        Schema::create('licitacao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacao_id')->constrained('licitacoes')->cascadeOnDelete();
            $table->string('descricao');
            $table->string('codigo_catser')->nullable();
            $table->decimal('quantidade', 10, 2);
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
        Schema::dropIfExists('licitacao_itens');
        Schema::enableForeignKeyConstraints();
    }
};
