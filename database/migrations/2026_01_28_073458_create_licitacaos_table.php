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
        Schema::create('licitacoes', function (Blueprint $table) {
            $table->id();
            $table->string('orgao');
            $table->string('numero_edital')->nullable();
            $table->text('objeto');
            $table->string('modalidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cidade')->nullable();
            $table->decimal('valor_estimado', 15, 2)->nullable();
            $table->dateTime('data_abertura')->nullable();
            $table->string('link_edital')->nullable();
            $table->string('status')->default('aberta'); // aberta, suspensa, finalizada
            $table->timestamps();

            $table->index(['uf', 'status']);
            $table->fullText('objeto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacoes');
    }
};
