<?php

declare(strict_types=1);

/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: receber os pedidos de matrizes, chamar MatrizServico e escolher a tela
 * do painel (layout gerente). As telas são de Arthur. Todas as rotas são só da gerente.
 * Matriz nova nasce no montador (/montador?modo=matriz); aqui só se edita e exclui.
 * Matrizes padrão podem ser editadas e excluídas; as criadas por usuários, só excluídas (moderação).
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class MatrizControlador
{
    public function __construct(private MatrizServico $servico = new MatrizServico()) {}

    /** Quantas matrizes de usuários a lista mostra, das mais recentes. */
    private const LIMITE_USUARIOS = 50;

    /** GET /gerente/matrizes: lista as matrizes em dois grupos, padrão e criadas por usuários. */
    public function listar(): void
    {
        Visao::render('matrizes/lista', [
            'titulo' => 'Matrizes',
            'matrizes' => $this->servico->listarPadrao(),
            'matrizesUsuarios' => $this->servico->listarDeUsuarios(self::LIMITE_USUARIOS),
            'limiteUsuarios' => self::LIMITE_USUARIOS,
        ], 'gerente');
    }

    /** GET /gerente/matrizes/form?id=: edita os dados de uma matriz existente. */
    public function form(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $matriz = $this->servico->buscar($id);

        if ($matriz === null) {
            Sessao::flash('erro', 'Matriz não encontrada. Para criar uma nova, use o montador.');
            Resposta::redirecionar('/gerente/matrizes');
        }

        if (!$matriz['padrao']) {
            Sessao::flash('erro', 'Matrizes criadas por usuários não podem ser editadas, apenas excluídas.');
            Resposta::redirecionar('/gerente/matrizes');
        }

        // Depois de um erro de validação, reabre o form com o que a gerente digitou.
        $velhos = Sessao::tirarVelhos();

        Visao::render('matrizes/form', [
            'titulo' => 'Editar matriz',
            'matriz' => $velhos ? $velhos + ['id' => $id] : $matriz,
        ], 'gerente');
    }

    /** POST /gerente/matrizes/salvar: atualiza nome, descrição, categoria e tempo médio. */
    public function salvar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $erros = $this->servico->salvarDados($id, $_POST);

        if ($erros) {
            Sessao::flash('erro', implode(' ', $erros));
            Sessao::guardarVelhos($_POST);
            Resposta::redirecionar('/gerente/matrizes/form?id=' . $id);
        }

        Sessao::flash('sucesso', 'Matriz atualizada com sucesso!');
        Resposta::redirecionar('/gerente/matrizes');
    }

    /** POST /gerente/matrizes/excluir: bloqueia a exclusão de matriz em uso. */
    public function excluir(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($this->servico->buscar($id) === null) {
            Sessao::flash('erro', 'Matriz não encontrada.');
        } elseif ($this->servico->excluir($id)) {
            Sessao::flash('sucesso', 'Matriz excluída com sucesso!');
        } else {
            Sessao::flash('erro', 'Esta matriz está em uso. Não é possível excluir.');
        }

        Resposta::redirecionar('/gerente/matrizes');
    }
}
