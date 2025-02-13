<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasComprador extends Model
{
    use HasFactory;

    protected $table = 'compras_compradores';

    protected $fillable = [
        'cod_comprador',
        'descricao',
        'cpf',
        'email',
    ];
}
