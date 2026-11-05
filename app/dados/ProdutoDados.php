<?php
/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL de produtos, categorias e combinações.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class ProdutoDados
{
    /** TODO(Guilherme): listar todos, com o nome da categoria. */
    public function listar(): array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::listar');
    }

    /** TODO(Guilherme): usar a consulta completa com bordados, categorias e matrizes. */
    public function listarAtivos(?string $categoriaSlug = null): array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::listarAtivos');
    }

    /** TODO(Guilherme): filtrar destaque e ativo. */
    public function destaques(int $limite = 6): array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::destaques');
    }

    /** TODO(Guilherme): devolver o produto. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::buscar');
    }

    /** TODO(Guilherme): usar a consulta completa, com um único produto. */
    public function buscarAtivo(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::buscarAtivo');
    }

    /** TODO(Guilherme): executar SELECT * FROM categorias ORDER BY nome. */
    public function listarCategorias(): array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::listarCategorias');
    }

    /** TODO(Guilherme): listar id e titulo dos bordados do tipo produto. */
    public function listarCombinacoes(): array
    {
        throw new RuntimeException('Não implementado: ProdutoDados::listarCombinacoes');
    }

    /** TODO(Guilherme): informar se a combinação já está em outro produto. */
    public function combinacaoEmUso(int $bordadoId, int $ignorarId = 0): bool
    {
        throw new RuntimeException('Não implementado: ProdutoDados::combinacaoEmUso');
    }

    /** TODO(Guilherme): executar o INSERT. */
    public function inserir(array $d): void
    {
        throw new RuntimeException('Não implementado: ProdutoDados::inserir');
    }

    /** TODO(Guilherme): executar o UPDATE. */
    public function atualizar(int $id, array $d): void
    {
        throw new RuntimeException('Não implementado: ProdutoDados::atualizar');
    }

    /** TODO(Guilherme): executar o DELETE. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: ProdutoDados::excluir');
    }

    /** TODO(Guilherme): inverter ativo. */
    public function alternarAtivo(int $id): void
    {
        throw new RuntimeException('Não implementado: ProdutoDados::alternarAtivo');
    }

    /** TODO(Guilherme): devolver o total, usado no painel. */
    public function contar(): int
    {
        throw new RuntimeException('Não implementado: ProdutoDados::contar');
    }
}
