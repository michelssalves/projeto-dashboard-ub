<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras_solicitacao_de_compra_comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitacao_id')->constrained('compras_solicitacao_de_compras')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('config_users')->onDelete('cascade');
            $table->text('comentario');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras_solicitacao_de_compra_comentarios');
    }
};
