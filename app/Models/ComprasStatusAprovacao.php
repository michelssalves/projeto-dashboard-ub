<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasStatusAprovacao extends Model
{
    use HasFactory;

    protected $table = 'compras_status_aprovacao';

    protected $fillable = [
        'descricao',
    ];
}
