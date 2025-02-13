<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_solicitacao_de_compra', function (Blueprint $table) {
            $table->id();
            $table->string('item_solicitacao');
            $table->unsignedBigInteger('cod_produto');
            $table->integer('quantidade');
            $table->unsignedBigInteger('cod_centro_custo');
            $table->unsignedBigInteger('cod_comprador');
            $table->unsignedBigInteger('cod_solicitante');
            $table->unsignedBigInteger('cod_status');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('cod_produto')->references('id')->on('compras_produtos');
            $table->foreign('cod_centro_custo')->references('id')->on('contabilidade_centro_de_custo');
            $table->foreign('cod_comprador')->references('id')->on('compras_compradores');
            $table->foreign('cod_solicitante')->references('id')->on('users');
            $table->foreign('cod_status')->references('id')->on('compras_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras_solicitacao_de_compra');
    }
};
