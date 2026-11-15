<?php

declare(strict_types=1);

/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: abrir o montador e salvar bordados.
 * Veja INSTRUCOES_GUILHERME.md, seção 7.
 */
class MontadorControlador
{
    public function __construct(private MontadorServico $servico = new MontadorServico()) {}

    /** Modos do montador. hub é o padrão; matriz e produto são só da gerente. */
    private const MODOS = ['hub', 'matriz', 'produto'];

    /** GET /montador: abre o montador com matrizes, categorias e modo. */
    public function index(): void
    {
        $modo = in_array($_GET['modo'] ?? 'hub', self::MODOS, true) ? $_GET['modo'] ?? 'hub' : 'hub';

        if ($modo !== 'hub' && !Auth::eGerente()) {
            Sessao::flash('erro', 'Somente a gerente pode criar matrizes e combinações de produto.');
            Resposta::redirecionar('/montador');
        }

        // Visibilidade geral: o usuário vê todas as matrizes, sem alternância de visibilidade.
        $matrizes = (new MatrizDados())->listarTodas();

        $titulos = ['hub' => 'Montar bordado', 'matriz' => 'Nova matriz', 'produto' => 'Nova combinação'];

        Visao::render('montador/index', [
            'titulo' => $titulos[$modo],
            'modo' => $modo,
            'matrizes' => $matrizes,
            'categorias' => (new ProdutoServico())->categorias(),
            // Peças base ativas, cadastradas pela gerente em /gerente/pecas.
            'pecas' => (new PecaServico())->ativas(),
            // ?matriz=: matriz já marcada na etapa 1 (vem logo depois de cadastrar uma matriz).
            'matrizInicial' => max(0, (int) ($_GET['matriz'] ?? 0)),
        ]);
    }

    /**
     * GET /montador/imagem?url=: baixa a imagem de um link colado no enquadramento da foto
     * e devolve os bytes, do mesmo site, para o navegador poder recortar no canvas.
     * Erro volta em JSON com status 422 e a mensagem amigável.
     */
    public function imagem(): void
    {
        try {
            $imagem = (new ImagemRemotaServico())->baixar((string) ($_GET['url'] ?? ''));
        } catch (InvalidArgumentException $erro) {
            Resposta::json(['erro' => $erro->getMessage()], 422);
        }

        header('Content-Type: ' . $imagem['tipo']);
        header('Content-Length: ' . strlen($imagem['conteudo']));
        header('X-Content-Type-Options: nosniff');
        header('Content-Security-Policy: default-src \'none\'');
        header('Cache-Control: private, no-store');
        echo $imagem['conteudo'];
        exit;
    }

    /**
     * POST /montador/salvar: valida e grava pelo MontadorServico.
     * Erro de validação volta para /montador com os valores antigos no formulário.
     * Sucesso segue o modo: hub vai para /hub/bordado?id=, matriz vai para o modo produto com a matriz marcada
     * e produto vai para /gerente/produtos/form?bordado= com a combinação marcada.
     */
    public function salvar(): void
    {
        $usuario = Auth::usuario();

        if ($usuario === null) {
            // O roteador já barra visitante; a conferência aqui é defesa em profundidade.
            Resposta::redirecionar('/login');
        }

        $modo = in_array($_POST['modo'] ?? 'hub', self::MODOS, true) ? (string) $_POST['modo'] : 'hub';
        $resultado = $this->servico->salvar($_POST, $usuario);

        if ($resultado['erros']) {
            Sessao::flash('erro', implode(' ', $resultado['erros']));
            // Só os textos voltam ao formulário: a grade e a foto chegam a 1 MB e iriam para a sessão no banco.
            Sessao::guardarVelhos(array_intersect_key($_POST, array_flip(['titulo', 'descricao', 'nome_matriz', 'descricao_matriz'])));
            Resposta::redirecionar('/montador' . ($modo !== 'hub' ? '?modo=' . $modo : ''));
        }

        Sessao::flash('sucesso', $modo === 'matriz'
            ? 'Matriz cadastrada! Agora escolha a peça e a posição para criar a combinação do produto.'
            : 'Bordado salvo com sucesso!');
        Resposta::redirecionar((string) $resultado['destino']);
    }
}
