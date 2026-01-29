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
        Schema::table('item_catalogos', function (Blueprint $table) {
            $table->string('codigo_catmat')->nullable()->after('codigo');
            $table->string('codigo_catser')->nullable()->after('codigo_catmat');
            $table->string('ncm')->nullable()->after('codigo_catser');
            $table->string('marca')->nullable()->after('nome');
            $table->string('modelo')->nullable()->after('marca');
            $table->string('fornecedor_padrao')->nullable()->after('modelo');
            $table->string('codigo_barras')->nullable()->after('codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_catalogos', function (Blueprint $table) {
            $table->dropColumn([
                'codigo_catmat',
                'codigo_catser',
                'ncm',
                'marca',
                'modelo',
                'fornecedor_padrao',
                'codigo_barras'
            ]);
        });
    }
};
