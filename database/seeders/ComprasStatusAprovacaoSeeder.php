<?php

namespace Database\Seeders;

use App\Models\ComprasStatusAprovacao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprasStatusAprovacaoSeeder extends Seeder
{
    public function run()
    {
        ComprasStatusAprovacao::truncate();

        ComprasStatusAprovacao::insert([
            ['descricao' => 'Pendente'],
            ['descricao' => 'Aprovado'],
            ['descricao' => 'Rejeitado'],
        ]);
    }
}
