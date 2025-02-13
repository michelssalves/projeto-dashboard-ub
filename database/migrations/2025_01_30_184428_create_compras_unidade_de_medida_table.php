<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras_unidade_de_medida', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50)->unique(); // Nome da unidade de medida, por exemplo: "Unidade", "Kg", "Litro"
            $table->timestamps();
        });

        // Adicionar a coluna de relacionamento na tabela compras_produtos
        Schema::table('compras_produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('unidade_de_medida_id')->nullable();

            // Chave estrangeira para a tabela compras_unidades_de_medida
            $table->foreign('unidade_de_medida_id')
                  ->references('id')
                  ->on('compras_unidade_de_medida')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        // Remover chave estrangeira e coluna
        Schema::table('compras_produtos', function (Blueprint $table) {
            $table->dropForeign(['unidade_de_medida_id']);
            $table->dropColumn('unidade_de_medida_id');
        });

        Schema::dropIfExists('compras_unidade_de_medida');
    }
};
