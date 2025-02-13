@include('header')

<div class="container-fluid py-4">
    <!-- Tabela Principal -->
    <div class="card shadow-lg" style="background-color: #FFFFFF; border-radius: 8px;">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h2 class="text-center text-md-start">Lista de Eventos</h2>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <button class="btn" style="background-color: #7F3FC9; color: #FFFFFF;" data-bs-toggle="modal"
                        data-bs-target="#modalFilter">
                        🔍 Filtros
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" style="background-color: #FFFFFF; border-radius: 8px;">
                    <thead style="background-color: #7F3FC9; color: #FFFFFF;">
                        <tr>
                            <th>Dt Evento</th>
                            <th>Cód. Evento</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>Dt. Comunicado</th>
                            <th>Dt. Cad.</th>
                            <th>Dias</th>
                            <th>Motivo</th>
                            <th>Ev. Tipo</th>
                            <th>Participação</th>
                            <th>Valor</th>
                            <th>Sit. Evento</th>
                            <th>Status</th>
                            <th>Associado</th>
                            <th>Dt.Nascimento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eventos as $evento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($evento['data_evento'] ?? '')->format('d-m-Y') ?? 'N/A' }}
                                </td>
                                <td>{{ $evento['codigo_evento'] ?? 'N/A' }}</td>
                                <td>{{ $evento['cidade'] ?? 'N/A' }}</td>
                                <td>{{ $evento['estado'] ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($evento['data_comunicado_evento'] ?? '')->format('d-m-Y') ?? 'N/A' }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($evento['data_cadastro'] ?? '')->format('d-m-Y') ?? 'N/A' }}
                                </td>
                                <td>{{ $evento['dias_para_comunicacao'] }}</td>
                                <td>{{ $evento['motivo'] ?? 'N/A' }}</td>
                                <td>{{ $evento['evento_tipo'] ?? 'N/A' }}</td>
                                <td>{{ $evento['participacao'] ?? 'N/A' }}</td>
                                <td>R$ {{ number_format($evento['valor_reparo'] ?? 0, 2, ',', '.') }}</td>
                                <td>{{ $evento['situacao_evento'] ?? 'N/A' }}</td>
                                <td>{{ $evento['situacao_evento'] == '2.8 - DESISTêNCIA' ? 'Finalizado' : '' }}</td>
                                <td>{{ data_get($evento, 'associado.nome', data_get($evento, 'terceiro.nome')) }}</td>
                                <td>{{ \Carbon\Carbon::parse(data_get($evento, 'associado.data_nascimento', ''))->format('d-m-Y') ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Nenhum evento encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        .table {
            font-size: 0.9rem;
            /* Diminui o tamanho do texto na tabela */

            word-wrap: break-word;
            /* Força quebra de linha apenas se necessário */
        }

        .table th,
        .table td {
            text-align: center;
            /* Centraliza o conteúdo */
            vertical-align: middle;
            /* Centraliza verticalmente */
            white-space: nowrap;
            /* Evita quebra de texto */
            overflow: hidden;
            /* Esconde o texto excedente */
            text-overflow: ellipsis;
            /* Adiciona "..." ao texto longo */
        }

        .table-responsive-scroll::-webkit-scrollbar-thumb:hover {
            background: #9E59D1;
        }
    </style>

    </style>
    <!-- Indicadores abaixo da tabela -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-center">Eventos por Estado</h5>
                    <table class="table table-bordered" style="background-color: #FFFFFF; color: #333;">
                        <thead>
                            <tr style="background-color:   #7F3FC9;color: #FFFFFF;">
                                <th>Estado</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventosPorEstado as $estado)
                                <tr>
                                    <td>{{ $estado['estado'] ?? 'N/A' }}</td>
                                    <td>{{ $estado['quantidade'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reaplicando o mesmo estilo para os outros cards -->
        <div class="col-md-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-center">Eventos por Cidade</h5>
                    <table class="table table-bordered" style="background-color: #FFFFFF; color: #333;">
                        <thead>
                            <tr style="background-color:   #7F3FC9;color: #FFFFFF;">
                                <th>Cidade</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventosPorCidade as $cidade)
                                <tr>
                                    <td>{{ $cidade['cidade'] ?? 'N/A' }}</td>
                                    <td>{{ $cidade['quantidade'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Eventos por Cidade -->
        <div class="col-md-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-center">Eventos por situacao</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color:   #7F3FC9;color: #FFFFFF;">
                                <th>Situação</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventosPorSituacao as $situacao)
                                <tr>
                                    <td>{{ $situacao['situacao_evento'] ?? 'N/A' }}</td>
                                    <td>{{ $situacao['quantidade'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tempo Médio de Comunicação -->
        <div class="col-md-3">
            <div class="card shadow-lg text-center">
                <div class="card-body">
                    <h5 class="card-title">Tempo Médio para Comunicação</h5>
                    <h2 class="text-primary">{{ $tempoMedioComunicacao }} dias</h2>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Modal para Filtros -->
<!-- Modal para Filtros -->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFilterLabel">Filtros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('eventos.index') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter-start-date" class="form-label">Data Início</label>
                        <input type="date" id="filter-start-date" name="data_inicio" class="form-control"
                            value="{{ old('data_inicio', $dataInicio ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="filter-end-date" class="form-label">Data Final</label>
                        <input type="date" id="filter-end-date" name="data_final" class="form-control"
                            value="{{ old('data_final', $dataFinal ?? '') }}">
                    </div>

                    <!-- Multiselect de Evento Motivo -->
                    <div class="mb-3">
                        <label for="evento_motivo" class="form-label">Motivo do Evento</label>
                        <select id="evento_motivo" name="evento_motivo[]" class="form-control">
                            @foreach ($situacoesEvento as $situacao)
                                <option value="{{ $situacao['codigo'] }}"
                                    {{ in_array($situacao['codigo'], old('evento_motivo', $eventoMotivoSelecionado ?? [])) ? 'selected' : '' }}>
                                    {{ $situacao['descricao'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Remover Filtros</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
