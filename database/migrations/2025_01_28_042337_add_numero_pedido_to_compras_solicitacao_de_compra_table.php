<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras_solicitacao_de_compra', function (Blueprint $table) {
            $table->string('numero_pedido')->after('item_solicitacao')->nullable(); // Adiciona o campo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras_solicitacao_de_compra', function (Blueprint $table) {
            $table->dropColumn('numero_pedido'); // Remove o campo
        });
    }
};
