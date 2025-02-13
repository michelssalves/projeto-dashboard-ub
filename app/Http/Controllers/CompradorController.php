<?php

namespace App\Http\Controllers;

use App\Models\ComprasComprador;
use Illuminate\Http\Request;

class CompradorController extends Controller
{
    public function buscar(Request $request)
    {
        $codigo = $request->query('codigo');

        $comprador = ComprasComprador::where('cod_comprador', $codigo)->first();

        if (!$comprador) {
            return response()->json(['error' => 'Comprador não encontrado.'], 404);
        }

        return response()->json(['nome' => $comprador->descricao], 200);
    }
}
