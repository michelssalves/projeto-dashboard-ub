<?php

namespace App\Http\Controllers;

use App\Models\ComprasComprador;
use App\Models\ComprasProduto;
use App\Models\ComprasUnidadeMedida;
use App\Models\ContabilidadeCentroDeCusto;
use App\Models\SolicitacaoDeCompra;
use App\Models\SolicitacaoDeCompraAnexo;
use App\Models\SolicitacaoDeCompraComentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitacaoDeCompraController extends Controller
{

    public function index(Request $request)
    {
        // Carregar opções para os filtros
        $produtos = ComprasProduto::all();
        $unidadesMedida = ComprasUnidadeMedida::select('id', 'nome')->get();
        $centrosDeCusto = ContabilidadeCentroDeCusto::all();
        $compradores = ComprasComprador::all();

        // Query inicial com os relacionamentos
        $query = SolicitacaoDeCompra::with(
            'produto',
            'centroDeCusto',
            'comprador',
            'primeiraUnidadeDeMedida',
            'segundaUnidadeDeMedida'
        );

        // Filtros
        if ($request->filled('produto')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->produto . '%');
            });
        }

        if ($request->filled('cod_produto')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('cod_produto', $request->cod_produto);
            });
        }

        if ($request->filled('data_criacao')) {
            $query->whereDate('created_at', $request->data_criacao);
        }

        if ($request->filled('cod_centro_custo')) {
            $query->whereHas('centroDeCusto', function ($q) use ($request) {
                $q->where('cod_centro_custo', $request->cod_centro_custo);
            });
        }

        if ($request->filled('centro_custo')) {
            $query->whereHas('centroDeCusto', function ($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->centro_custo . '%');
            });
        }

        // Paginação
        $solicitacoes = $query->paginate(30);

        // Retornar os dados e filtros aplicados
        return view('solicitacao', compact('solicitacoes', 'produtos', 'centrosDeCusto', 'compradores', 'unidadesMedida'))
            ->with('filters', $request->all());
    }






    public function create()
    {
        $produtos = ComprasProduto::all();
        $centrosDeCusto = ContabilidadeCentroDeCusto::all();
        $compradores = ComprasComprador::all();

        return view('solicitacoes.create', compact('produtos', 'centrosDeCusto', 'compradores'));
    }

    public function store(Request $request)
    {
        try {

            $request->merge(['comentarios' => json_decode($request->comentarios, true)]);

            $validated = $request->validate([
                'cod_produto' => 'required|array',
                'quantidade' => 'required|array',
                'cod_centro_custo' => 'required|array',
                'cod_comprador' => 'required|string',
                'comentarios' => 'nullable|array',
                'comentarios.*' => 'string|max:500',
                'anexos' => 'nullable|array',
                'anexos.*' => 'file|max:2048',
                'primeira_un_medida' => 'required|exists:compras_unidade_de_medida,id',
                'segunda_un_medida' => 'nullable|exists:compras_unidade_de_medida,id',
            ]);

            $comprador = ComprasComprador::where('cod_comprador', $validated['cod_comprador'])->first();
            if (!$comprador) {
                return response()->json([
                    'success' => false,
                    'message' => 'O comprador informado não foi encontrado.',
                    'errors' => ['cod_comprador' => 'O comprador informado não foi encontrado.'],
                ], 422);
            }

            $numeroPedido = DB::transaction(function () {
                $config = DB::table('config_controle_numeracao')->where('nome_tabela', 'compras_solicitacao_de_compra')->lockForUpdate()->first();

                if (!$config) {
                    DB::table('config_controle_numeracao')->insert([
                        'nome_tabela' => 'compras_solicitacao_de_compra',
                        'numeracao' => 1,
                    ]);
                    return 1;
                }

                DB::table('config_controle_numeracao')->where('id', $config->id)->update([
                    'numeracao' => $config->numeracao + 1,
                ]);

                return $config->numeracao;
            });

            foreach ($validated['cod_produto'] as $index => $produtoDescricao) {
                $produto = ComprasProduto::where('cod_produto', $produtoDescricao)->first();
                $centroCusto = ContabilidadeCentroDeCusto::where('cod_centro_custo', $validated['cod_centro_custo'][$index])->first();

                if (!$produto || !$centroCusto) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao validar produto ou centro de custo.',
                        'errors' => [
                            'cod_produto' => !$produto ? "O produto '$produtoDescricao' não foi encontrado." : null,
                            'cod_centro_custo' => !$centroCusto ? "O centro de custo '{$validated['cod_centro_custo'][$index]}' não foi encontrado." : null,
                        ],
                    ], 422);
                }

                $solicitacao = SolicitacaoDeCompra::create([
                    'item_solicitacao' => ($index + 1),
                    'numero_pedido' => $numeroPedido,
                    'cod_produto' => $produto->id,
                    'quantidade' => $validated['quantidade'][$index],
                    'primeira_un_medida' => $validated['primeira_un_medida'] ?? '1',
                    'segunda_un_medida' => $validated['segunda_un_medida'] ?? '1',
                    'cod_centro_custo' => $centroCusto->id,
                    'cod_comprador' => $comprador->id,
                    'cod_solicitante' => auth()->user()->id,
                    'cod_status' => '1',
                ]);

                if (!empty($validated['comentarios'])) {
                    foreach ($validated['comentarios'] as $comentario) {
                        SolicitacaoDeCompraComentario::create([
                            'solicitacao_id' => $solicitacao->id,
                            'user_id' => auth()->user()->id,
                            'comentario' => $comentario,
                        ]);
                    }
                }

                if ($request->hasFile('anexos')) {
                    foreach ($request->file('anexos') as $file) {
                        $path = $file->store('anexos_solicitacao', 'public');
                        SolicitacaoDeCompraAnexo::create([
                            'solicitacao_id' => $solicitacao->id,
                            'user_id' => auth()->user()->id,
                            'nome_arquivo' => $file->getClientOriginalName(),
                            'caminho_arquivo' => $path,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Solicitação criada com sucesso!',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar solicitação: ', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
    public function getSolicitacao($numero_pedido)
    {
        $solicitacao = SolicitacaoDeCompra::with([
            'compras_solicitacao_de_compra_comentarios',
            'compras_solicitacao_de_compra_anexos'
        ])
            ->where('numero_pedido', $numero_pedido)
            ->firstOrFail();

        return response()->json($solicitacao);
    }



    public function validateCodes(Request $request)
    {

        $compradorValido = \App\Models\ComprasComprador::where('cod_comprador', $request->compradorId)->exists();
        $produtoValido = \App\Models\ComprasProduto::where('cod_produto', $request->produtoId)->exists();
        $centroCustoValido = \App\Models\ContabilidadeCentroDeCusto::where('cod_centro_custo', $request->centroCustoId)->exists();

        return response()->json([
            'compradorValido' => $compradorValido,
            'produtoValido' => $produtoValido,
            'centroCustoValido' => $centroCustoValido,
        ]);
    }
    public function buscar(Request $request)
    {
        $codigo = $request->query('codigo');
        $busca = $request->query('busca');

        $item = null;

        if ($busca == 'comprador') {
            $item = ComprasComprador::where('cod_comprador', $codigo)->first();
        } elseif ($busca == 'produto') {
            $item = ComprasProduto::where('cod_produto', $codigo)->first();
        } elseif ($busca == 'centro_custo') {
            $item = ContabilidadeCentroDeCusto::where('cod_centro_custo', $codigo)->first();
        }

        if (!$item) {
            return response()->json(['error' => 'Item não encontrado.'], 404);
        }

        return response()->json(['nome' => $item->descricao], 200);
    }
}
