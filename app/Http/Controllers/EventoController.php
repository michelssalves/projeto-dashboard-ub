<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $apiUrl = env('HINOVA_API_URL');
            $apiToken = env('HINOVA_API_TOKEN');

            // Se as datas não forem fornecidas, assume:
            $dataFinal = $request->input('data_final', Carbon::now()->toDateString());
            $dataInicio = $request->input('data_inicio', Carbon::now()->subDays(30)->toDateString());

            // Converter para DD/MM/YYYY apenas para envio no body
            $dataInicioFormatada = Carbon::parse($dataInicio)->format('d/m/Y');
            $dataFinalFormatada = Carbon::parse($dataFinal)->format('d/m/Y');

            // Buscar lista de situações do evento para preencher o multiselect
            $situacoesEvento = [];
            try {
                $situacaoResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Accept' => 'application/json',
                ])->get("https://api.hinova.com.br/api/sga/v2/situacao-evento/listar/ativo");

                if ($situacaoResponse->successful()) {
                    $situacoesEvento = collect($situacaoResponse->json())
                        ->map(function ($situacao) {
                            return [
                                'codigo' => (int) $situacao['codigo_eventosituacao'], // Garantir que seja número
                                'descricao' => $situacao['descricao'],
                            ];
                        })
                        ->sortBy('descricao') // Ordenar pela descrição
                        ->toArray();
                }
            } catch (\Exception $e) {
                Log::error("Erro ao buscar situações do evento: " . $e->getMessage());
            }

            // Capturar os valores selecionados no multiselect e garantir que sejam números inteiros
            $eventoMotivoSelecionado = $request->input('evento_motivo', []);
            if (!is_array($eventoMotivoSelecionado)) {
                $eventoMotivoSelecionado = [$eventoMotivoSelecionado]; // Se for string única, transformar em array
            }

            // Converter todos os valores para inteiros
            $eventoMotivoSelecionado = array_map('intval', $eventoMotivoSelecionado);

            // Se não houver motivos selecionados, enviar um array vazio
            if (empty($eventoMotivoSelecionado)) {
                $eventoMotivoSelecionado = [
                    2,
                    3,
                    5,
                    6,
                    10,
                    11,
                    14,
                    15,
                    23,
                    24,
                    26,
                    27,
                    29,
                    30,
                    32,
                    33,
                    38,
                    40,
                    42,
                    45,
                    50,
                    51,
                    52,
                    54,
                    55,
                    56,
                    64,
                    65,
                    66,
                    67,
                    68,
                    70,
                    71,
                    72,
                    73,
                    74,
                    75,
                    76,
                    77,
                    78
                ];
            }

            // Body da requisição para a API (JSON formatado corretamente)
            $body = [
                "data_cadastro" => $dataInicioFormatada,
                "data_cadastro_final" => $dataFinalFormatada,
                "evento_situacao" =>  $eventoMotivoSelecionado
            ];

            Log::info("Body enviado para API: " . json_encode($body)); // Log para debug

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json', // Garante que o formato seja correto
            ])->post($apiUrl, $body);

            if ($response->successful()) {
                $eventos = collect($response->json());

                // Aplicar Filtro por Data (considerando que a API retorna YYYY-MM-DD)
                $eventos = $eventos->filter(function ($evento) use ($dataInicio, $dataFinal) {
                    $dataCadastro = $evento['data_cadastro'] ?? null;
                    return $dataCadastro && Carbon::parse($dataCadastro)->between($dataInicio, $dataFinal);
                })->values();

                // Inicializa variáveis para cálculo de tempo médio
                $totalDias = 0;
                $totalEventos = 0;

                // Garantir que todos os eventos tenham a chave 'dias_para_comunicacao'
                $eventos = $eventos->map(function ($evento) use (&$totalDias, &$totalEventos) {
                    $dataComunicado = $evento['data_comunicado_evento'] ?? null;
                    $dataCadastro = $evento['data_evento'] ?? null;

                    if ($dataComunicado && $dataCadastro) {
                        $dias = Carbon::parse($dataCadastro)->diffInDays(Carbon::parse($dataComunicado));
                        $evento['dias_para_comunicacao'] = $dias;
                        $totalDias += $dias;
                        $totalEventos++;
                    } else {
                        $evento['dias_para_comunicacao'] = 'N/A';
                    }

                    return $evento;
                });

                // Calcular tempo médio de comunicação
                $tempoMedioComunicacao = $totalEventos > 0 ? round($totalDias / $totalEventos, 2) : 0;

                // Agrupar eventos por estado
                $eventosPorEstado = $eventos
                    ->groupBy('estado')
                    ->map(fn($eventos, $estado) => ['estado' => $estado, 'quantidade' => count($eventos)])
                    ->values()->toArray();

                // Agrupar eventos por cidade
                $eventosPorCidade = $eventos
                    ->groupBy('cidade')
                    ->map(fn($eventos, $cidade) => ['cidade' => $cidade, 'quantidade' => count($eventos)])
                    ->values()->toArray();
                $eventosPorSitaucao = $eventos
                    ->groupBy('situacao_evento')
                    ->map(fn($eventos, $situacao) => ['situacao_evento' => $situacao, 'quantidade' => count($eventos)])
                    ->values()->toArray();
            } else {
                Log::error("Erro ao buscar eventos: {$response->status()} - {$response->body()}");
                $eventos = collect([]);
                $eventosPorEstado = [];
                $eventosPorCidade = [];
                $eventosPorSitaucao = [];
                $tempoMedioComunicacao = 0;
            }
        } catch (\Exception $e) {
            Log::error("Erro inesperado: " . $e->getMessage());
            $eventos = collect([]);
            $eventosPorEstado = [];
            $eventosPorSitaucao = [];
            $eventosPorCidade = [];
            $tempoMedioComunicacao = 0;
        }

        return view('eventos.index', compact('eventos', 'eventosPorEstado', 'eventosPorCidade', 'tempoMedioComunicacao', 'dataInicio', 'dataFinal', 'situacoesEvento', 'eventoMotivoSelecionado', 'eventosPorSitaucao'));
    }
}
