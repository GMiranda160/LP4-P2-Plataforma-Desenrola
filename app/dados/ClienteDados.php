<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela clientes. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
class ClienteDados
{
    /** TODO(Arthur): buscar por nome ou CPF/CNPJ com ILIKE, ordenado por nome. */
    public function listar(string $busca = ''): array
    {
        throw new RuntimeException('Não implementado: ClienteDados::listar');
    }

    /** TODO(Arthur): devolver o registro, ou null. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ClienteDados::buscar');
    }

    /** TODO(Arthur): informar se o documento já existe, ignorando o próprio id. */
    public function existeDocumento(string $cpfCnpj, int $ignorarId = 0): bool
    {
        throw new RuntimeException('Não implementado: ClienteDados::existeDocumento');
    }

    /** TODO(Arthur): executar o INSERT. */
    public function inserir(array $d): void
    {
        throw new RuntimeException('Não implementado: ClienteDados::inserir');
    }

    /** TODO(Arthur): executar o UPDATE. */
    public function atualizar(int $id, array $d): void
    {
        throw new RuntimeException('Não implementado: ClienteDados::atualizar');
    }

    /** TODO(Arthur): executar o DELETE. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: ClienteDados::excluir');
    }

    /** TODO(Arthur): devolver o total, usado no painel. */
    public function contar(): int
    {
        throw new RuntimeException('Não implementado: ClienteDados::contar');
    }
}
