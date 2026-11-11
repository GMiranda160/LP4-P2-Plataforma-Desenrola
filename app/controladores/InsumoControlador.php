<?php
/*
 * InsumoControlador.php
 * Controlador dos insumos.
 */

class InsumoControlador
{
    public function __construct(private InsumoServico $servico = new InsumoServico()) {}

    public function listar(): void
    {
        $busca = trim($_GET['busca'] ?? '');
        Visao::render('insumos/lista', [
            'titulo' => 'Insumos',
            'busca' => $busca,
            'insumos' => $this->servico->listar($busca),
        ], 'gerente');
    }

    public function form(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $insumo = $id ? $this->servico->buscar($id) : [];

        if ($id && !$insumo) {
            Sessao::flash('erro', 'Insumo não encontrado.');
            Resposta::redirecionar('/gerente/insumos');
        }

        Visao::render('insumos/form', [
            'titulo' => $id ? 'Editar insumo' : 'Novo insumo',
            'insumo' => Sessao::tirarVelhos() ?: $insumo,
        ], 'gerente');
    }

    public function salvar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $erros = $this->servico->salvar($id, $_POST);

        if ($erros) {
            Sessao::flash('erro', implode(' ', $erros));
            Sessao::guardarVelhos($_POST);
            Resposta::redirecionar('/gerente/insumos/form' . ($id ? "?id={$id}" : ''));
        }

        Sessao::flash('sucesso', $id ? 'Insumo atualizado com sucesso!' : 'Insumo cadastrado com sucesso!');
        Resposta::redirecionar('/gerente/insumos');
    }

    public function excluir(): void
    {
        $this->servico->excluir((int) ($_POST['id'] ?? 0));
        Sessao::flash('sucesso', 'Insumo excluído com sucesso!');
        Resposta::redirecionar('/gerente/insumos');
    }
}