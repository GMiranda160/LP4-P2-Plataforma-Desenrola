<?php
/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL da tabela matrizes.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class MatrizDados
{
    /** TODO(Guilherme): gravar com CAST(:grade AS jsonb) e devolver o id com RETURNING id. */
    public function inserir(array $d): int
    {
        throw new RuntimeException('Não implementado: MatrizDados::inserir');
    }

    /** TODO(Guilherme): devolver a matriz. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: MatrizDados::buscar');
    }

    /** TODO(Guilherme): listar as matrizes com padrao verdadeiro. */
    public function listarPadrao(): array
    {
        throw new RuntimeException('Não implementado: MatrizDados::listarPadrao');
    }

    /** TODO(Guilherme): listar as matrizes do usuário. */
    public function listarDoAutor(int $autorId): array
    {
        throw new RuntimeException('Não implementado: MatrizDados::listarDoAutor');
    }

    /** TODO(Guilherme): atualizar nome, descrição, categoria e tempo médio. */
    public function atualizarDados(int $id, array $d): void
    {
        throw new RuntimeException('Não implementado: MatrizDados::atualizarDados');
    }

    /** TODO(Guilherme): executar SELECT 1 FROM bordados WHERE matriz_id = :id LIMIT 1. */
    public function emUso(int $id): bool
    {
        throw new RuntimeException('Não implementado: MatrizDados::emUso');
    }

    /** TODO(Guilherme): executar o DELETE. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: MatrizDados::excluir');
    }

    /** TODO(Guilherme): devolver o total, usado no painel. */
    public function contar(): int
    {
        throw new RuntimeException('Não implementado: MatrizDados::contar');
    }
}
