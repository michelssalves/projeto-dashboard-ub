<?php

namespace App\Http\Controllers;

use App\Jobs\BuscarEventosJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EventoController extends Controller
{

    public function index(Request $request)
    {
        $apiUrl = env('HINOVA_API_URL');
        $apiToken = env('HINOVA_API_TOKEN');
    
        $dataFinal = $request->input('data_final', Carbon::now()->toDateString());
        $dataInicio = $request->input('data_inicio', '2019-01-01');
    
        $eventoMotivoSelecionado = $request->input('evento_motivo', []);
        if (!is_array($eventoMotivoSelecionado)) {
            $eventoMotivoSelecionado = [$eventoMotivoSelecionado];
        }
        $eventoMotivoSelecionado = array_map('intval', $eventoMotivoSelecionado);
    
        if (empty($eventoMotivoSelecionado)) {
            $eventoMotivoSelecionado = [2, 5, 6, 10, 11, 14, 15, 23, 24, 26, 27, 29, 33, 38, 40, 42, 45, 50, 51, 52, 54, 55, 56, 64, 65, 66, 67, 68, 70, 71, 72, 73, 74, 75, 77, 78];
        }
    
        // Verifica se os eventos já estão no cache
        $eventos = Cache::get('eventos_resultado', collect());
    
        return view('eventos.index', [
            'eventos' => $eventos,
            'dataInicio' => $dataInicio,
            'dataFinal' => $dataFinal
        ]);
    }
}
