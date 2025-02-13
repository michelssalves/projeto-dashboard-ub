<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoDeCompraAnexo extends Model
{
    use HasFactory;

    protected $table = 'compras_solicitacao_de_compra_anexos';

    protected $fillable = [
        'solicitacao_id',
        'user_id',
        'nome_arquivo',
        'caminho_arquivo',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(SolicitacaoDeCompra::class, 'solicitacao_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCaminhoArquivoUrlAttribute()
    {
        return asset('storage/' . $this->caminho_arquivo);
    }
}
