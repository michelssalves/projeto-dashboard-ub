<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('compras_solicitacao_de_compra', function (Blueprint $table) {
            $table->unsignedBigInteger('primeira_un_medida')->after('quantidade');
            $table->unsignedBigInteger('segunda_un_medida')->nullable()->after('primeira_un_medida');

            // Chaves estrangeiras para as unidades de medida
            $table->foreign('primeira_un_medida')
                ->references('id')
                ->on('compras_unidades_de_medida')
                ->onDelete('cascade');

            $table->foreign('segunda_un_medida')
                ->references('id')
                ->on('compras_unidades_de_medida')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('compras_solicitacao_de_compra', function (Blueprint $table) {
            $table->dropForeign(['primeira_un_medida']);
            $table->dropForeign(['segunda_un_medida']);
            $table->dropColumn(['primeira_un_medida', 'segunda_un_medida']);
        });
    }
};
