<?php

namespace Database\Seeders;

use App\Models\ComprasComprador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprasCompradoresSeeder extends Seeder
{
    public function run()
    {
        ComprasComprador::truncate();
        
        ComprasComprador::insert([
            [
                'cod_comprador' => 'C001',
                'descricao' => 'João Silva',
                'cpf' => '12345678901',
                'email' => 'joao.silva@example.com',
            ],
            [
                'cod_comprador' => 'C002',
                'descricao' => 'Maria Oliveira',
                'cpf' => '98765432100',
                'email' => 'maria.oliveira@example.com',
            ],
            [
                'cod_comprador' => 'C003',
                'descricao' => 'Carlos Santos',
                'cpf' => '19283746500',
                'email' => 'carlos.santos@example.com',
            ],
        ]);
    }
}
