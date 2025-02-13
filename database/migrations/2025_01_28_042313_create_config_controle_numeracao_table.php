<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('config_controle_numeracao', function (Blueprint $table) {
            $table->id();
            $table->string('nome_tabela')->unique(); // Nome da tabela sendo controlada
            $table->integer('numeracao')->default(1); // Controle da numeração
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_controle_numeracao');
    }
};
