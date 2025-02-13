<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras_produtos', function (Blueprint $table) {
            $table->id();
            $table->string('cod_produto')->unique();
            $table->string('descricao');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras_produtos');
    }
};
