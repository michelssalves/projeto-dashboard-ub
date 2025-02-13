<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contabilidade_centro_de_custo', function (Blueprint $table) {
            $table->id();
            $table->string('cod_centro_custo')->unique();
            $table->string('descricao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contabilidade_centro_de_custo');
    }
};
