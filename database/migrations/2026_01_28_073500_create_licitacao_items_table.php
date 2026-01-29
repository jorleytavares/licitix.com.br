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
        Schema::create('licitacao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacao_id')->constrained('licitacoes')->onDelete('cascade');
            $table->integer('numero_item')->nullable();
            $table->text('descricao');
            $table->string('unidade')->nullable(); // UN, KG, LITRO
            $table->decimal('quantidade', 12, 3);
            $table->decimal('valor_referencia', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacao_itens');
    }
};
