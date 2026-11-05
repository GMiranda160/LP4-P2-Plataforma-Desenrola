<?php
/**
 * Camada: Controlador. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: receber os pedidos de insumos, chamar InsumoServico e escolher a tela.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
class InsumoControlador
{
    /** TODO(Arthur): ler a busca de $_GET e mostrar insumos/lista com o layout gerente. */
    public function listar(): void
    {
        throw new RuntimeException('Não implementado: InsumoControlador::listar');
    }

    /** TODO(Arthur): carregar o registro por id, se houver, recuperar velhos e mostrar insumos/form. */
    public function form(): void
    {
        throw new RuntimeException('Não implementado: InsumoControlador::form');
    }

    /** TODO(Arthur): chamar o serviço. Com erro, usar flash e guardarVelhos e voltar ao formulário. Sem erro, usar flash e ir para /gerente/insumos. */
    public function salvar(): void
    {
        throw new RuntimeException('Não implementado: InsumoControlador::salvar');
    }

    /** TODO(Arthur): excluir pelo id do $_POST, usar flash e ir para /gerente/insumos. */
    public function excluir(): void
    {
        throw new RuntimeException('Não implementado: InsumoControlador::excluir');
    }
}
