<?php

declare(strict_types=1);

/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: feed, detalhe, meus bordados, curtidas, exclusão e ocultação de bordados.
 * As telas são de Eduardo. O roteador já cuida de login, perfil de gerente e CSRF.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class HubControlador
{
    public function __construct(private HubServico $servico = new HubServico()) {}

    /** GET /hub: feed com filtro de categoria, ordenação e paginação. */
    public function feed(): void
    {
        $categoria = $this->servico->categoria($_GET['categoria'] ?? null);
        $ordem = $this->servico->ordem($_GET['ordem'] ?? null);
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));

        $resultado = $this->servico->feed(Auth::id() ?? 0, $categoria, $ordem, $pagina);

        Visao::render('hub/feed', [
            'titulo' => 'Hub de bordados',
            'bordados' => $resultado['bordados'],
            'categorias' => (new ProdutoServico())->categorias(),
            'categoriaAtual' => $categoria,
            'ordem' => $ordem,
            'pagina' => $pagina,
            'temMais' => $resultado['temMais'],
        ]);
    }

    /** GET /hub/bordado?id=: página do bordado. Post oculto só abre para a gerente. */
    public function detalhe(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $eGerente = Auth::eGerente();
        $bordado = $this->servico->detalhe($id, Auth::id() ?? 0, $eGerente);

        if ($bordado === null) {
            Sessao::flash('erro', 'Bordado não encontrado.');
            Resposta::redirecionar('/hub');
        }

        Visao::render('hub/detalhe', [
            'titulo' => $bordado['titulo'],
            'bordado' => $bordado,
            'comentarios' => $this->comentarios($id, $eGerente),
            'podeExcluir' => $this->servico->podeExcluir($bordado, Auth::id(), $eGerente),
        ]);
    }

    /** GET /meus-bordados: bordados do usuário logado, inclusive os ocultos. */
    public function meus(): void
    {
        Visao::render('hub/meus', [
            'titulo' => 'Meus bordados',
            'bordados' => $this->servico->doAutor((int) Auth::id()),
        ]);
    }

    /** POST /hub/curtir: alterna a curtida e responde JSON com curtiu e curtidas. */
    public function curtir(): void
    {
        $resultado = $this->servico->curtir((int) Auth::id(), (int) ($_POST['id'] ?? 0));

        if ($resultado === null) {
            Resposta::json(['erro' => 'Bordado não encontrado.'], 404);
        }

        Resposta::json($resultado);
    }

    /** POST /bordado/excluir: só o autor ou a gerente. */
    public function excluirBordado(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $erro = $this->servico->excluir($id, (int) Auth::id(), Auth::eGerente());

        if ($erro !== null) {
            Sessao::flash('erro', $erro);
            Resposta::redirecionar($id > 0 ? '/hub/bordado?id=' . $id : '/hub');
        }

        Sessao::flash('sucesso', 'Bordado excluído com sucesso!');
        Resposta::redirecionar(Auth::eGerente() ? '/hub' : '/meus-bordados');
    }

    /** POST /gerente/hub/ocultar: inverte bordados.oculto e volta para o bordado. */
    public function ocultar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->servico->alternarOculto($id)) {
            Sessao::flash('erro', 'Bordado não encontrado.');
            Resposta::redirecionar('/hub');
        }

        Sessao::flash('sucesso', 'Visibilidade do bordado atualizada.');
        Resposta::redirecionar('/hub/bordado?id=' . $id);
    }

    /**
     * Comentários do bordado, pela lógica de Eduardo (ocultos só para a gerente).
     * TODO(Guilherme): remover o try/catch quando ComentarioServico::listar estiver pronto.
     * Até lá, a página do bordado abre sem comentários em vez de dar erro 500.
     */
    private function comentarios(int $bordadoId, bool $eGerente): array
    {
        try {
            return (new ComentarioServico())->listar($bordadoId, $eGerente);
        } catch (RuntimeException $e) {
            if (str_starts_with($e->getMessage(), 'Não implementado')) {
                return [];
            }
            throw $e;
        }
    }
}
