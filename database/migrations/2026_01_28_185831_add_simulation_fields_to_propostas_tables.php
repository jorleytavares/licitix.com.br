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
        Schema::table('propostas', function (Blueprint $table) {
            $table->decimal('impostos_percentual', 5, 2)->default(15.00)->after('valor_total');
            $table->decimal('frete_percentual', 5, 2)->default(5.00)->after('impostos_percentual');
            $table->decimal('taxas_extras_percentual', 5, 2)->default(2.00)->after('frete_percentual');
            $table->decimal('custos_extras_valor', 10, 2)->default(0.00)->after('taxas_extras_percentual');
        });

        Schema::table('proposta_itens', function (Blueprint $table) {
            $table->decimal('custo_unitario', 10, 2)->nullable()->after('valor_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            $table->dropColumn(['impostos_percentual', 'frete_percentual', 'taxas_extras_percentual', 'custos_extras_valor']);
        });

        Schema::table('proposta_itens', function (Blueprint $table) {
            $table->dropColumn('custo_unitario');
        });
    }
};
