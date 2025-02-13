<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras_compradores', function (Blueprint $table) {
            $table->id();
            $table->string('cod_comprador')->unique();
            $table->string('descricao');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras_compradores');
    }
};
