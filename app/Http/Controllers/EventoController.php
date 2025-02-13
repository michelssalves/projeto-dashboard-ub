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

            // Definir as datas inicial e final com base no request ou nos padrões
            $dataFinal = $request->input('data_final', Carbon::now()->toDateString());
            $dataInicio = $request->input('data_inicio', '2019-01-01'); // Início fixo desde 2019

            // Buscar lista de situações do evento para preencher o multiselect
            $situacoesEvento = [];
            try {
                $situacaoResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Accept' => 'application/json',
                ])->get("https://api.hinova.com.br/api/sga/v2/situacao-evento/listar/ativo");

                if ($situacaoResponse->successful()) {
                    $situacoesEvento = collect($situacaoResponse->json())
                        ->map(fn($situacao) => [
                            'codigo' => (int) $situacao['codigo_eventosituacao'],
                            'descricao' => $situacao['descricao'],
                        ])
                        ->sortBy('descricao')
                        ->toArray();
                }
            } catch (\Exception $e) {
                Log::error("Erro ao buscar situações do evento: " . $e->getMessage());
            }

            // Capturar os valores selecionados no multiselect
            $eventoMotivoSelecionado = $request->input('evento_motivo', []);
            if (!is_array($eventoMotivoSelecionado)) {
                $eventoMotivoSelecionado = [$eventoMotivoSelecionado];
            }
            $eventoMotivoSelecionado = array_map('intval', $eventoMotivoSelecionado);

            if (empty($eventoMotivoSelecionado)) {
                $eventoMotivoSelecionado = [2, 5, 6, 10, 11, 14, 15, 23, 24, 26, 27, 29, 33, 38, 40, 42, 45, 50, 51, 52, 54, 55, 56, 64, 65, 66, 67, 68, 70, 71, 72, 73, 74, 75, 77, 78];
            }
            set_time_limit(0);
            // Criar coleção para armazenar todos os eventos
            $eventos = collect([]);

            $dataInicioLoop = Carbon::createFromFormat('Y-m-d', $dataInicio);
            $dataFinalLoop = Carbon::createFromFormat('Y-m-d', $dataFinal);

            while ($dataInicioLoop->lte($dataFinalLoop)) {
                // Converter para DD/MM/YYYY apenas na saída
                $intervaloInicio = $dataInicioLoop->format('d/m/Y');
                $intervaloFim = $dataInicioLoop->copy()->addDays(29)->format('d/m/Y');

                // Garantir que o intervalo não ultrapasse a data final
                if (Carbon::createFromFormat('d/m/Y', $intervaloFim)->gt($dataFinalLoop)) {
                    $intervaloFim = $dataFinalLoop->format('d/m/Y');
                }

                $body = [
                    "data_cadastro" => $intervaloInicio,
                    "data_cadastro_final" => $intervaloFim,
                    "evento_situacao" => $eventoMotivoSelecionado
                ];

                Log::info("Consultando API com intervalo: $intervaloInicio - $intervaloFim");

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, $body);

                if ($response->successful()) {
                    $dados = collect($response->json());
                    $eventos = $eventos->merge($dados);
                } else {
                    Log::error("Erro ao buscar eventos: {$response->status()} - {$response->body()}");
                }

                // Avançar para o próximo bloco de 30 dias
                $dataInicioLoop->addDays(30);
            }

            // Aplicar Filtro por Data (caso necessário)
            $eventos = $eventos->filter(
                fn($evento) => isset($evento['data_cadastro']) &&
                    Carbon::parse($evento['data_cadastro'])->between($dataInicio, $dataFinal)
            )->values();

            // Calcular tempo médio de comunicação
            $totalDias = 0;
            $totalEventos = 0;
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

            $tempoMedioComunicacao = $totalEventos > 0 ? round($totalDias / $totalEventos, 2) : 0;

            // Agrupar eventos por estado
            $eventosPorEstado = $eventos->groupBy('estado')
                ->map(fn($eventos, $estado) => ['estado' => $estado, 'quantidade' => count($eventos)])
                ->values()->toArray();

            // Agrupar eventos por cidade
            $eventosPorCidade = $eventos->groupBy('cidade')
                ->map(fn($eventos, $cidade) => ['cidade' => $cidade, 'quantidade' => count($eventos)])
                ->values()->toArray();

            // Agrupar eventos por situação
            $eventosPorSituacao = $eventos->groupBy('situacao_evento')
                ->map(fn($eventos, $situacao) => ['situacao_evento' => $situacao, 'quantidade' => count($eventos)])
                ->values()->toArray();
        } catch (\Exception $e) {
            Log::error("Erro inesperado: " . $e->getMessage());
            $eventos = collect([]);
            $eventosPorEstado = [];
            $eventosPorCidade = [];
            $eventosPorSituacao = [];
            $tempoMedioComunicacao = 0;
        }

        return view('eventos.index', compact('eventos', 'eventosPorEstado', 'eventosPorCidade', 'tempoMedioComunicacao', 'dataInicio', 'dataFinal', 'situacoesEvento', 'eventoMotivoSelecionado', 'eventosPorSituacao'));
    }
}
