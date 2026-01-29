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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('cnpj');
            $table->string('email_contato')->nullable()->after('logo_path');
            $table->string('telefone_contato')->nullable()->after('email_contato');
            $table->string('endereco')->nullable()->after('telefone_contato');
            $table->string('website')->nullable()->after('endereco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'email_contato', 'telefone_contato', 'endereco', 'website']);
        });
    }
};
