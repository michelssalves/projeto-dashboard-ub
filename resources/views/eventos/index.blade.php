@include('header')

<div class="container-fluid py-4">
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

            <!-- Se os eventos ainda estiverem carregando -->
            @if (!Cache::has('eventos_resultado'))
                <div class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-3">Buscando eventos, por favor aguarde...</p>
                </div>
            @elseif (empty(Cache::get('eventos_resultado')))
                <div class="text-center my-5">
                    <p class="text-danger">Nenhum evento encontrado para o filtro aplicado.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                {{-- <th>Status</th> --}}
                                <th>Associado</th>
                                <th>Dt. Nascimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (Cache::get('eventos_resultado') as $evento)
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
                                    <td>{{ $evento['dias_para_comunicacao'] ?? 'N/A' }}</td>
                                    <td>{{ $evento['motivo'] ?? 'N/A' }}</td>
                                    <td>{{ $evento['evento_tipo'] ?? 'N/A' }}</td>
                                    <td>{{ $evento['participacao'] ?? 'N/A' }}</td>
                                    <td>R$ {{ number_format($evento['valor_reparo'] ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ $evento['situacao_evento'] ?? 'N/A' }}</td>
                                    {{-- <td>{{ $evento['situacao_evento'] == '2.8 - DESISTêNCIA' ? 'Finalizado' : '' }} --}}
                                    </td>
                                    <td>{{ data_get($evento, 'associado.nome', data_get($evento, 'terceiro.nome')) }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse(data_get($evento, 'associado.data_nascimento', ''))->format('d-m-Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center">Nenhum evento encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

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
                        {{-- <div class="mb-3">
                            <label for="evento_motivo" class="form-label">Motivo do Evento</label>
                            <select id="evento_motivo" name="evento_motivo[]" class="form-control" multiple>
                                @foreach ($situacoesEvento as $situacao)
                                    <option value="{{ $situacao['codigo'] }}"
                                        {{ in_array($situacao['codigo'], old('evento_motivo', $eventoMotivoSelecionado ?? [])) ? 'selected' : '' }}>
                                        {{ $situacao['descricao'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
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
</div>
