<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela insumos. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
class InsumoDados
{
    /** TODO(Arthur): buscar por nome com ILIKE, ordenado por tipo e nome. */
    public function listar(string $busca = ''): array
    {
        throw new RuntimeException('Não implementado: InsumoDados::listar');
    }

    /** TODO(Arthur): devolver o registro, ou null. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: InsumoDados::buscar');
    }

    /** TODO(Arthur): informar se já existe o nome neste tipo, ignorando o próprio id. */
    public function existeNome(string $tipo, string $nome, int $ignorarId = 0): bool
    {
        throw new RuntimeException('Não implementado: InsumoDados::existeNome');
    }

    /** TODO(Arthur): executar o INSERT. */
    public function inserir(array $d): void
    {
        throw new RuntimeException('Não implementado: InsumoDados::inserir');
    }

    /** TODO(Arthur): executar o UPDATE. */
    public function atualizar(int $id, array $d): void
    {
        throw new RuntimeException('Não implementado: InsumoDados::atualizar');
    }

    /** TODO(Arthur): executar o DELETE. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: InsumoDados::excluir');
    }

    /** TODO(Arthur): devolver o total, usado no painel. */
    public function contar(): int
    {
        throw new RuntimeException('Não implementado: InsumoDados::contar');
    }
}
