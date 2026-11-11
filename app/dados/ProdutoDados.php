<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL dos produtos do mostruário. Sem validação e sem HTML.
 */
class ProdutoDados
{
    /**
     * Consulta completa usada no catálogo, na home e na página do produto.
     * Filtra só produtos ativos. Passe $categoria (slug) para filtrar, ou null para todos.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listar(?string $categoria = null): array
    {
        $sql = 'SELECT p.*, c.nome AS categoria, c.slug, c.mockup,
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, m.svg
                FROM produtos p
                JOIN bordados b ON b.id = p.bordado_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                WHERE p.ativo'
            . ($categoria !== null ? ' AND c.slug = :categoria' : '')
            . ' ORDER BY p.criado_em DESC';

        $consulta = Conexao::pdo()->prepare($sql);

        if ($categoria !== null) {
            $consulta->bindValue(':categoria', $categoria);
        }

        $consulta->execute();

        return $consulta->fetchAll();
    }

    /** Produtos em destaque, para a home. */
    public function destaques(int $limite = 6): array
    {
        $sql = 'SELECT p.*, c.nome AS categoria, c.slug, c.mockup,
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, m.svg
                FROM produtos p
                JOIN bordados b ON b.id = p.bordado_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                WHERE p.ativo AND p.destaque
                ORDER BY p.criado_em DESC
                LIMIT :limite';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /** Um produto ativo, para a página do produto. Null se não existir. */
    public function buscarAtivo(int $id): ?array
    {
        $sql = 'SELECT p.*, c.nome AS categoria, c.slug, c.mockup,
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, m.svg, b.descricao AS bordado_descricao
                FROM produtos p
                JOIN bordados b ON b.id = p.bordado_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                WHERE p.ativo AND p.id = :id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $produto = $consulta->fetch();

        return $produto === false ? null : $produto;
    }

    /** Todas as categorias, para o filtro do catálogo e os atalhos da home. */
    public function categorias(): array
    {
        $consulta = Conexao::pdo()->query('SELECT id, nome, slug, mockup FROM categorias ORDER BY nome');

        return $consulta === false ? [] : $consulta->fetchAll();
    }

    /** Total de produtos, usado no painel. */
    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM produtos')->fetchColumn();
    }
}
