<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasUnidadeMedida extends Model
{
    use HasFactory;

    protected $table = 'compras_unidade_de_medida';

    protected $fillable = ['nome'];

    // Relacionamento com ComprasProduto
    public function produtos()
    {
        return $this->hasMany(ComprasProduto::class, 'unidade_de_medida_id');
    }
}
