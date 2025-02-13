<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function unidades()
    {
        $unidades = UnidadeMedida::all(['id', 'nome']); // Ajuste conforme sua tabela e colunas
        return response()->json($unidades);
    }
}
