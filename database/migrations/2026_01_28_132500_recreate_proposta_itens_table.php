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

        Schema::dropIfExists('proposta_itens');

        Schema::create('proposta_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained()->cascadeOnDelete();
            $table->string('descricao');
            $table->decimal('quantidade', 10, 2);
            $table->decimal('valor_unitario', 14, 2);
            $table->decimal('valor_total', 14, 2);
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
        Schema::dropIfExists('proposta_itens');
        Schema::enableForeignKeyConstraints();
    }
};
