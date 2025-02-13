<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContabilidadeCentroDeCusto extends Model
{
    use HasFactory;

    protected $table = 'contabilidade_centro_de_custo';

    protected $fillable = [
        'cod_centro_custo',
        'descricao',
    ];
}
