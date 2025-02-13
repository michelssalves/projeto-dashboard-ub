<?php

namespace Database\Seeders;

use App\Models\ComprasUnidadeMedida;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprasUnidadeDeMedidaSeeder extends Seeder
{
    public function run()
    {
        $unidades = [
            ['nome' => 'UN'],
            ['nome' => 'KG'],
            ['nome' => 'LT'],
            ['nome' => 'CX'],
            ['nome' => 'M']
        ];

        foreach ($unidades as $unidade) {
            ComprasUnidadeMedida::create($unidade);
        }
    }
}
