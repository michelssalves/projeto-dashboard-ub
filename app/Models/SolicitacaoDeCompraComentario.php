<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoDeCompraComentario extends Model
{
    use HasFactory;

    protected $table = 'compras_solicitacao_de_compra_comentarios';

    protected $fillable = [
        'solicitacao_id',
        'user_id',
        'comentario',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(SolicitacaoDeCompra::class, 'solicitacao_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
