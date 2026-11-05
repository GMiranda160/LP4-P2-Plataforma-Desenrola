<?php
/**
 * Camada: Controlador. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: receber os pedidos de clientes, chamar ClienteServico e escolher a tela.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
class ClienteControlador
{
    /** TODO(Arthur): ler a busca de $_GET e mostrar clientes/lista com o layout gerente. */
    public function listar(): void
    {
        throw new RuntimeException('Não implementado: ClienteControlador::listar');
    }

    /** TODO(Arthur): carregar o registro por id, se houver, recuperar velhos e mostrar clientes/form. */
    public function form(): void
    {
        throw new RuntimeException('Não implementado: ClienteControlador::form');
    }

    /** TODO(Arthur): chamar o serviço. Com erro, usar flash e guardarVelhos e voltar ao formulário. Sem erro, usar flash e ir para /gerente/clientes. */
    public function salvar(): void
    {
        throw new RuntimeException('Não implementado: ClienteControlador::salvar');
    }

    /** TODO(Arthur): excluir pelo id do $_POST, usar flash e ir para /gerente/clientes. */
    public function excluir(): void
    {
        throw new RuntimeException('Não implementado: ClienteControlador::excluir');
    }
}
