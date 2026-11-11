<?php
/*
 * ClienteControlador.php
 * Controlador dos clientes.
 */

class ClienteControlador
{
    public function __construct(private ClienteServico $servico = new ClienteServico()) {}

    public function listar(): void
    {
        $busca = trim($_GET['busca'] ?? '');
        Visao::render('clientes/lista', [
            'titulo' => 'Clientes',
            'busca' => $busca,
            'clientes' => $this->servico->listar($busca),
        ], 'gerente');
    }

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

    public function excluir(): void
    {
        $this->servico->excluir((int) ($_POST['id'] ?? 0));
        Sessao::flash('sucesso', 'Cliente excluído com sucesso!');
        Resposta::redirecionar('/gerente/clientes');
    }
}