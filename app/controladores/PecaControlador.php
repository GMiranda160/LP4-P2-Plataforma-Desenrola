<?php

declare(strict_types=1);

/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: receber os pedidos de peças base, chamar PecaServico e escolher a tela do
 * painel (layout gerente). Todas as rotas são só da gerente. As peças ativas aparecem no montador.
 */
class PecaControlador
{
    public function __construct(private PecaServico $servico = new PecaServico()) {}

    /** GET /gerente/pecas: lista as peças base com foto, categoria e situação. */
    public function listar(): void
    {
        Visao::render('pecas/lista', [
            'titulo' => 'Peças',
            'pecas' => $this->servico->listar(),
        ], 'gerente');
    }

    /** GET /gerente/pecas/form: formulário vazio ou, com ?id=, de edição. Id inexistente volta para a lista. */
    public function form(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $peca = $id > 0 ? $this->servico->buscar($id) : null;

        if ($id > 0 && $peca === null) {
            Sessao::flash('erro', 'Peça não encontrada.');
            Resposta::redirecionar('/gerente/pecas');
        }

        $velhos = Sessao::tirarVelhos();

        Visao::render('pecas/form', [
            'titulo' => $peca ? 'Editar peça' : 'Nova peça',
            // Depois de erro, mantém o que foi digitado, mas a foto atual continua a do banco.
            'peca' => $velhos ? $velhos + ($peca ?? []) : ($peca ?? ['ativo' => true]),
            'categorias' => (new ProdutoServico())->categorias(),
        ], 'gerente');
    }

    /** POST /gerente/pecas/salvar: cadastra ou atualiza, com a foto em $_FILES['foto']. */
    public function salvar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $erros = $this->servico->salvar($id, $_POST, $_FILES['foto'] ?? null);

        if ($erros) {
            Sessao::flash('erro', implode(' ', $erros));
            Sessao::guardarVelhos($_POST);
            Resposta::redirecionar('/gerente/pecas/form' . ($id > 0 ? '?id=' . $id : ''));
        }

        Sessao::flash('sucesso', $id > 0 ? 'Peça atualizada com sucesso!' : 'Peça cadastrada com sucesso!');
        Resposta::redirecionar('/gerente/pecas');
    }

    /** POST /gerente/pecas/excluir: exclui a peça e a foto dela, pelo id do POST. */
    public function excluir(): void
    {
        $excluiu = $this->servico->excluir((int) ($_POST['id'] ?? 0));
        Sessao::flash($excluiu ? 'sucesso' : 'erro', $excluiu ? 'Peça excluída com sucesso!' : 'Peça não encontrada.');
        Resposta::redirecionar('/gerente/pecas');
    }
}
