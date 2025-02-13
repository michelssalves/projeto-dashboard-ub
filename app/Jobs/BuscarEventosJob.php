<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class BuscarEventosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataInicio;
    protected $dataFinal;
    protected $apiUrl;
    protected $apiToken;
    protected $eventoMotivoSelecionado;

    public function __construct($dataInicio, $dataFinal, $apiUrl, $apiToken, $eventoMotivoSelecionado)
    {
        $this->dataInicio = $dataInicio;
        $this->dataFinal = $dataFinal;
        $this->apiUrl = $apiUrl;
        $this->apiToken = $apiToken;
        $this->eventoMotivoSelecionado = $eventoMotivoSelecionado;
    }

    public function handle()
    {
        $dataInicioLoop = Carbon::createFromFormat('Y-m-d', $this->dataInicio);
        $dataFinalLoop = Carbon::createFromFormat('Y-m-d', $this->dataFinal);
        $eventos = collect([]);

        while ($dataInicioLoop->lte($dataFinalLoop)) {
            $intervaloInicio = $dataInicioLoop->format('d/m/Y');
            $intervaloFim = $dataInicioLoop->copy()->addDays(29)->format('d/m/Y');

            if (Carbon::createFromFormat('d/m/Y', $intervaloFim)->gt($dataFinalLoop)) {
                $intervaloFim = $dataFinalLoop->format('d/m/Y');
            }

            $body = [
                "data_cadastro" => $intervaloInicio,
                "data_cadastro_final" => $intervaloFim,
                "evento_situacao" => $this->eventoMotivoSelecionado
            ];

            Log::info("Consultando API com intervalo: $intervaloInicio - $intervaloFim");

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $body);

            if ($response->successful()) {
                if ($response->successful()) {
                    $dados = collect($response->json());
                    Log::info("📩 Resposta da API:", $dados->toArray());

                    $eventos = $eventos->merge($dados);
                }
            } else {
                Log::error("Erro ao buscar eventos: {$response->status()} - {$response->body()}");
            }

            $dataInicioLoop->addDays(30);
        }

        Log::info("Total de eventos coletados: " . $eventos->count());
        \Cache::put('eventos_resultado', $eventos, now()->addMinutes(30)); // Salvar no cache
    }
}
