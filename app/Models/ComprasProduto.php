<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasProduto extends Model
{
    use HasFactory;

    protected $table = 'compras_produtos';

    protected $fillable = ['cod_produto', 'descricao', 'marca', 'modelo', 'unidade_de_medida_id'];

    // Relacionamento com ComprasUnidadeDeMedida
    public function unidadeDeMedida()
    {
        return $this->belongsTo(ComprasUnidadeMedida::class, 'unidade_de_medida_id');
    }
}
