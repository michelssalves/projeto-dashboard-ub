<?php

namespace Database\Seeders;

use App\Models\ContabilidadeCentroDeCusto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContabilidadeCentroDeCustoSeeder extends Seeder
{
    public function run()
    {
        ContabilidadeCentroDeCusto::truncate();

        ContabilidadeCentroDeCusto::insert([
            [
                'cod_centro_custo' => 'CC001',
                'descricao' => 'Marketing',
            ],
            [
                'cod_centro_custo' => 'CC002',
                'descricao' => 'Tecnologia da Informação',
            ],
            [
                'cod_centro_custo' => 'CC003',
                'descricao' => 'Recursos Humanos',
            ],
        ]);
    }
}
