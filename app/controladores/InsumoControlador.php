<?php
/**
 * Camada: Controlador. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: receber os pedidos de insumos, chamar InsumoServico e escolher a tela do painel (layout gerente).
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */

class InsumoControlador
{
    public function __construct(private InsumoServico $servico = new InsumoServico()) {}

    /** GET /gerente/insumos: lista os insumos, com busca por nome em ?busca=. */
    public function listar(): void
    {
        $busca = trim($_GET['busca'] ?? '');
        Visao::render('insumos/lista', [
            'titulo' => 'Insumos',
            'busca' => $busca,
            'insumos' => $this->servico->listar($busca),
        ], 'gerente');
    }

    /** GET /gerente/insumos/form: abre o formulário vazio ou, com ?id=, para edição. Id inexistente volta para a lista. */
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

    /** POST /gerente/insumos/salvar: cadastra ou atualiza. Com erro, volta ao formulário com o que foi digitado. */
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

    /** POST /gerente/insumos/excluir: exclui pelo id do $_POST e volta para a lista. */
    public function excluir(): void
    {
        $this->servico->excluir((int) ($_POST['id'] ?? 0));
        Sessao::flash('sucesso', 'Insumo excluído com sucesso!');
        Resposta::redirecionar('/gerente/insumos');
    }
}