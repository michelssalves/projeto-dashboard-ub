<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoDeCompra extends Model
{
    use HasFactory;

    protected $table = 'compras_solicitacao_de_compra';

    protected $fillable = [
        'item_solicitacao',
        'numero_pedido',
        'cod_produto',
        'quantidade',
        'cod_centro_custo',
        'cod_comprador',
        'cod_solicitante',
        'cod_status',
        'primeira_un_medida',
        'segunda_un_medida'
    ];

    public function produto()
    {
        return $this->belongsTo(ComprasProduto::class, 'cod_produto');
    }

    public function centroDeCusto()
    {
        return $this->belongsTo(ContabilidadeCentroDeCusto::class, 'cod_centro_custo');
    }

    public function comprador()
    {
        return $this->belongsTo(ComprasComprador::class, 'cod_comprador');
    }

    // Relacionamento para a primeira unidade de medida
    public function primeiraUnidadeDeMedida()
    {
        return $this->belongsTo(ComprasUnidadeMedida::class, 'primeira_un_medida');
    }

    // Relacionamento para a segunda unidade de medida
    public function segundaUnidadeDeMedida()
    {
        return $this->belongsTo(ComprasUnidadeMedida::class, 'segunda_un_medida');
    }
    public function status()
    {
        return $this->belongsTo(ComprasStatusAprovacao::class, 'cod_status');
    }
    public function comentarios()
    {
        return $this->hasMany(SolicitacaoDeCompraComentario::class, 'solicitacao_id');
    }

    public function anexos()
    {
        return $this->hasMany(SolicitacaoDeCompraAnexo::class, 'solicitacao_id');
    }
}
