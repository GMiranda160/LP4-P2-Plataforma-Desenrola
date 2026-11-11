<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras dos produtos do mostruário. Sem SQL e sem HTML.
 */
class ProdutoServico
{
    /** Todos os produtos para a gerente. */
    public function todos(): array
    {
        return (new ProdutoDados())->listar();
    }

    /** Produtos ativos para o catálogo. Passe o slug da categoria para filtrar. */
    public function catalogo(?string $categoria = null): array
    {
        if ($categoria !== null) {
            $categoria = strtolower(trim($categoria));

            if (!preg_match('/^[a-z0-9-]{1,80}$/', $categoria)) {
                $categoria = null;
            }
        }

        return (new ProdutoDados())->listar($categoria);
    }

    /** Produtos em destaque, para a home. */
    public function destaques(int $limite = 6): array
    {
        return (new ProdutoDados())->destaques($limite);
    }

    /** Um produto ativo para a página pública, ou null. */
    public function mostrar(int $id): ?array
    {
        return (new ProdutoDados())->buscarAtivo($id);
    }

    /** Categorias para navegação e filtros. */
    public function categorias(): array
    {
        return (new ProdutoDados())->categorias();
    }
}
