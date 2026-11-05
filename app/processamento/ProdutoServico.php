<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras de produtos e catálogo.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class ProdutoServico
{
    /** TODO(Guilherme): devolver todos os produtos para a gerente. */
    public function listar(): array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::listar');
    }

    /** TODO(Guilherme): devolver os produtos ativos, com os dados da peça. */
    public function listarAtivos(?string $categoriaSlug = null): array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::listarAtivos');
    }

    /** TODO(Guilherme): devolver os produtos em destaque. */
    public function destaques(int $limite = 6): array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::destaques');
    }

    /** TODO(Guilherme): devolver um produto para edição. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::buscar');
    }

    /** TODO(Guilherme): devolver um produto ativo, com os dados da peça. */
    public function buscarAtivo(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::buscarAtivo');
    }

    /** TODO(Guilherme): devolver as categorias. */
    public function listarCategorias(): array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::listarCategorias');
    }

    /** TODO(Guilherme): devolver os bordados do tipo produto. */
    public function listarCombinacoes(): array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::listarCombinacoes');
    }

    /** TODO(Guilherme): validar preço > 0 e combinação única. Enviar booleanos como t ou f. Devolver a lista de erros. */
    public function salvar(int $id, array $campos): array
    {
        throw new RuntimeException('Não implementado: ProdutoServico::salvar');
    }

    /** TODO(Guilherme): excluir o produto. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: ProdutoServico::excluir');
    }

    /** TODO(Guilherme): inverter o campo ativo. */
    public function alternarAtivo(int $id): void
    {
        throw new RuntimeException('Não implementado: ProdutoServico::alternarAtivo');
    }
}
