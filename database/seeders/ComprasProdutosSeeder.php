<?php

namespace Database\Seeders;

use App\Models\ComprasProduto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprasProdutosSeeder extends Seeder
{
    public function run()
    {
        ComprasProduto::truncate();

        ComprasProduto::insert([
            [
                'cod_produto' => 'P001',
                'descricao' => 'Monitor LED 24"',
                'un_medida' => 'Unidade',
                'marca' => 'Dell',
                'modelo' => 'E2422HS',
            ],
            [
                'cod_produto' => 'P002',
                'descricao' => 'Notebook Core i7',
                'un_medida' => 'Unidade',
                'marca' => 'HP',
                'modelo' => 'Pavilion 15',
            ],
            [
                'cod_produto' => 'P003',
                'descricao' => 'Mouse Sem Fio',
                'un_medida' => 'Unidade',
                'marca' => 'Logitech',
                'modelo' => 'M185',
            ],
            [
                'cod_produto' => 'P004',
                'descricao' => 'Teclado Mecânico',
                'un_medida' => 'Unidade',
                'marca' => 'Razer',
                'modelo' => 'BlackWidow V3',
            ],
            [
                'cod_produto' => 'P005',
                'descricao' => 'Cadeira Gamer',
                'un_medida' => 'Unidade',
                'marca' => 'DXRacer',
                'modelo' => 'Formula Series',
            ],
        ]);
    }
}
