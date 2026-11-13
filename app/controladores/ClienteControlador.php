<?php
/**
 * Camada: Controlador. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: receber os pedidos de clientes, chamar ClienteServico e escolher a tela do painel (layout gerente).
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */

class ClienteControlador
{
    public function __construct(private ClienteServico $servico = new ClienteServico()) {}

    /** GET /gerente/clientes: lista os clientes, com busca por nome ou CPF/CNPJ em ?busca=. */
    public function listar(): void
    {
        $busca = trim($_GET['busca'] ?? '');
        Visao::render('clientes/lista', [
            'titulo' => 'Clientes',
            'busca' => $busca,
            'clientes' => $this->servico->listar($busca),
        ], 'gerente');
    }

    /** GET /gerente/clientes/form: abre o formulário vazio ou, com ?id=, para edição. Id inexistente volta para a lista. */
    public function form(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $cliente = $id ? $this->servico->buscar($id) : [];

        if ($id && !$cliente) {
            Sessao::flash('erro', 'Cliente não encontrado.');
            Resposta::redirecionar('/gerente/clientes');
        }

        Visao::render('clientes/form', [
            'titulo' => $id ? 'Editar cliente' : 'Novo cliente',
            'cliente' => Sessao::tirarVelhos() ?: $cliente,
        ], 'gerente');
    }

    /** POST /gerente/clientes/salvar: cadastra ou atualiza. Com erro, volta ao formulário com o que foi digitado. */
    public function salvar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $erros = $this->servico->salvar($id, $_POST);

        if ($erros) {
            Sessao::flash('erro', implode(' ', $erros));
            Sessao::guardarVelhos($_POST);
            Resposta::redirecionar('/gerente/clientes/form' . ($id ? "?id={$id}" : ''));
        }

        Sessao::flash('sucesso', $id ? 'Cliente atualizado com sucesso!' : 'Cliente cadastrado com sucesso!');
        Resposta::redirecionar('/gerente/clientes');
    }

    /** POST /gerente/clientes/excluir: exclui pelo id do $_POST e volta para a lista. */
    public function excluir(): void
    {
        $this->servico->excluir((int) ($_POST['id'] ?? 0));
        Sessao::flash('sucesso', 'Cliente excluído com sucesso!');
        Resposta::redirecionar('/gerente/clientes');
    }
}