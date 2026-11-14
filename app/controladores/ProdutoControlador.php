<?php
/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: gestão de produtos (mostruário). As telas são de Arthur.
 * Toda rota é só da gerente. Produto nasce de uma combinação do montador
 * (modo produto) e pode ser anunciado/desligado pelo toggle de ativo.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class ProdutoControlador
{
    public function __construct(private ProdutoServico $servico = new ProdutoServico()) {}

    /** GET /gerente/produtos: lista tudo, inclusive inativos. */
    public function listar(): void
    {
        Visao::render('produtos/lista', [
            'titulo' => 'Produtos',
            'produtos' => $this->servico->todos(),
        ], 'gerente');
    }

    /** GET /gerente/produtos/form: novo produto (com ?bordado= a combinação já vem marcada), ou edição de ?id=. */
    public function form(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $produto = $id > 0 ? $this->servico->buscar($id) : null;

        if ($id > 0 && $produto === null) {
            Sessao::flash('erro', 'Produto não encontrado.');
            Resposta::redirecionar('/gerente/produtos');
        }

        $velhos = Sessao::tirarVelhos();
        $combinacoes = $this->servico->combinacoes($id);

        Visao::render('produtos/form', [
            'titulo' => $produto ? 'Editar produto' : 'Novo produto',
            // Vindo do montador, a combinação recém-criada chega marcada.
            'produto' => $velhos ? $velhos + ['id' => $id] : ($produto ?? ['bordado_id' => (int) ($_GET['bordado'] ?? 0), 'ativo' => true]),
            'combinacoes' => $combinacoes,
        ], 'gerente');
    }

    /** POST /gerente/produtos/salvar: cria ou atualiza. */
    public function salvar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $resultado = $this->servico->salvar($_POST);

        if ($resultado['erros']) {
            Sessao::flash('erro', implode(' ', $resultado['erros']));
            Sessao::guardarVelhos($_POST);
            Resposta::redirecionar('/gerente/produtos/form' . ($id > 0 ? '?id=' . $id : ''));
        }

        Sessao::flash('sucesso', $id > 0 ? 'Produto atualizado.' : 'Produto cadastrado.');
        Resposta::redirecionar('/gerente/produtos');
    }

    /** POST /gerente/produtos/excluir: exclui pelo id do POST. */
    public function excluir(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->servico->excluir($id);
            Sessao::flash('sucesso', 'Produto excluído.');
        } else {
            Sessao::flash('erro', 'Produto não encontrado.');
        }

        Resposta::redirecionar('/gerente/produtos');
    }

    /** POST /gerente/produtos/ativar: inverte ativo (toggle de visibilidade). */
    public function ativar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $ativo = $id > 0 ? $this->servico->ativar($id) : null;

        if ($ativo === null) {
            Sessao::flash('erro', 'Produto não encontrado.');
        } else {
            Sessao::flash('sucesso', $ativo ? 'Produto visível no catálogo.' : 'Produto ocultado do catálogo.');
        }

        Resposta::redirecionar('/gerente/produtos');
    }
}
