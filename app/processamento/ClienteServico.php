<?php
/**
 * Camada: Processamento. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: validar clientes e chamar ClienteDados. Sem $_POST, sem $_SESSION e sem HTML.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
class ClienteServico
{
    /** TODO(Arthur): devolver clientes pela busca. */
    public function listar(string $busca = ''): array
    {
        throw new RuntimeException('Não implementado: ClienteServico::listar');
    }

    /** TODO(Arthur): devolver um cliente, ou null. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ClienteServico::buscar');
    }

    /** TODO(Arthur): validar nome, CPF com 11 ou CNPJ com 14 dígitos, e-mail, telefone e documento único. Gravar só os números. Devolver a lista de erros. */
    public function salvar(int $id, array $campos): array
    {
        throw new RuntimeException('Não implementado: ClienteServico::salvar');
    }

    /** TODO(Arthur): excluir o cliente. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: ClienteServico::excluir');
    }
}
