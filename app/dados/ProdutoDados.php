<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL dos produtos do mostruário. Sem validação e sem HTML.
 */
class ProdutoDados
{
    /**
     * Consulta usada no catálogo, na home e na página do produto.
     * Filtra por produtos ativos ($apenasAtivos = true); a gerente passa false para ver tudo.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listar(?string $categoria = null, bool $apenasAtivos = true): array
    {
        $sql = 'SELECT p.*, c.nome AS categoria, c.slug, c.mockup,
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, b.rotacao, COALESCE(b.foto_path, pc.imagem_path) AS foto_peca, (b.foto_path IS NULL) AS foto_tingir, COALESCE(m.svg_path, m.svg) AS svg, m.largura, m.altura, p.foto_path AS foto_produto
                FROM produtos p
                JOIN bordados b ON b.id = p.bordado_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                LEFT JOIN pecas pc ON pc.id = b.peca_id
                WHERE'
            . ($apenasAtivos ? ' p.ativo' : ' 1=1')
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
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, b.rotacao, COALESCE(b.foto_path, pc.imagem_path) AS foto_peca, (b.foto_path IS NULL) AS foto_tingir, COALESCE(m.svg_path, m.svg) AS svg, m.largura, m.altura, p.foto_path AS foto_produto
                FROM produtos p
                JOIN bordados b ON b.id = p.bordado_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                LEFT JOIN pecas pc ON pc.id = b.peca_id
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
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, b.rotacao, COALESCE(b.foto_path, pc.imagem_path) AS foto_peca, (b.foto_path IS NULL) AS foto_tingir, COALESCE(m.svg_path, m.svg) AS svg, m.largura, m.altura, p.foto_path AS foto_produto, b.descricao AS bordado_descricao
                FROM produtos p
                JOIN bordados b ON b.id = p.bordado_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                LEFT JOIN pecas pc ON pc.id = b.peca_id
                WHERE p.ativo AND p.id = :id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $produto = $consulta->fetch();

        return $produto === false ? null : $produto;
    }

    /** Um produto pelo id, para a tela de edição da gerente. Null se não existir. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM produtos WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $produto = $consulta->fetch();

        return $produto === false ? null : $produto;
    }

    /**
     * Combinações de bordado disponíveis para virar produto (tipo='produto' sem produto vinculado),
     * mais a combinação já vinculada a um produto (edição), se $atualId > 0.
     */
    public function combinacoes(int $atualId = 0): array
    {
        $sql = "SELECT b.id, b.titulo, b.descricao
                FROM bordados b
                WHERE b.tipo = 'produto'
                  AND (NOT EXISTS (SELECT 1 FROM produtos p WHERE p.bordado_id = b.id)
                       OR b.id = :atual)
                ORDER BY b.id DESC";

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':atual', $atualId, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /** Grava um produto novo e devolve o id. Bordado_id duplicado estoura SQLSTATE 23505 (tratado no serviço). */
    public function inserir(array $d): int
    {
        $sql = 'INSERT INTO produtos (bordado_id, nome, descricao, preco_base, destaque, ativo, foto_path)
                VALUES (:bordado_id, :nome, :descricao, :preco_base, :destaque, :ativo, :foto)
                RETURNING id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':bordado_id', (int) $d['bordado_id'], PDO::PARAM_INT);
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':descricao', $d['descricao'] ?? null);
        $consulta->bindValue(':preco_base', $d['preco_base']);
        $consulta->bindValue(':destaque', !empty($d['destaque']) ? 't' : 'f');
        $consulta->bindValue(':ativo', !empty($d['ativo']) ? 't' : 'f');
        $consulta->bindValue(':foto', $d['foto_path'] ?? null);
        $consulta->execute();

        return (int) $consulta->fetchColumn();
    }

    /** Atualiza um produto existente. */
    public function atualizar(int $id, array $d): void
    {
        $sql = 'UPDATE produtos
                SET bordado_id = :bordado_id, nome = :nome, descricao = :descricao,
                    preco_base = :preco_base, destaque = :destaque, ativo = :ativo, foto_path = :foto
                WHERE id = :id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':bordado_id', (int) $d['bordado_id'], PDO::PARAM_INT);
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':descricao', $d['descricao'] ?? null);
        $consulta->bindValue(':preco_base', $d['preco_base']);
        $consulta->bindValue(':destaque', !empty($d['destaque']) ? 't' : 'f');
        $consulta->bindValue(':ativo', !empty($d['ativo']) ? 't' : 'f');
        $consulta->bindValue(':foto', $d['foto_path'] ?? null);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Exclui o produto e devolve o foto_path gravado (null se não havia), para o serviço apagar o arquivo. O bordado permanece (vira combinação disponível de novo). */
    public function excluir(int $id): ?string
    {
        $consulta = Conexao::pdo()->prepare('DELETE FROM produtos WHERE id = :id RETURNING foto_path');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $caminho = $consulta->fetchColumn();

        return $caminho === false || $caminho === null ? null : (string) $caminho;
    }

    /** Inverte o ativo. Devolve o novo estado (true/false), ou null se não existe. */
    public function alternarAtivo(int $id): ?bool
    {
        $consulta = Conexao::pdo()->prepare('UPDATE produtos SET ativo = NOT ativo WHERE id = :id RETURNING ativo');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $linha = $consulta->fetch();

        return $linha === false ? null : filter_var($linha['ativo'], FILTER_VALIDATE_BOOLEAN);
    }

    /** Categorias para o CRUD (id + slug + nome), usadas no montador para derivar a peça. */
    public function categoriasIds(): array
    {
        return Conexao::pdo()->query('SELECT id, slug, nome FROM categorias ORDER BY nome')->fetchAll();
    }

    /** Todas as categorias, para o filtro do catálogo e os atalhos da home. */
    public function categorias(): array
    {
        $consulta = Conexao::pdo()->query('SELECT id, nome, slug, mockup FROM categorias ORDER BY nome');

        return $consulta === false ? [] : $consulta->fetchAll();
    }

    /** Verdadeiro se a categoria existe. Usada pelo montador antes de gravar o bordado. */
    public function existe(int $id): bool
    {
        $consulta = Conexao::pdo()->prepare('SELECT 1 FROM categorias WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return (bool) $consulta->fetchColumn();
    }

    /** True se a combinação existe, é tipo='produto' e não está em outro produto (o de edição não conta). */
    public function combinacaoDisponivel(int $bordadoId, int $produtoId): bool
    {
        $consulta = Conexao::pdo()->prepare(
            "SELECT 1
             FROM bordados b
             WHERE b.id = :id AND b.tipo = 'produto'
               AND NOT EXISTS (SELECT 1 FROM produtos p
                               WHERE p.bordado_id = b.id AND p.id <> :atual)
             LIMIT 1"
        );
        $consulta->bindValue(':id', $bordadoId, PDO::PARAM_INT);
        $consulta->bindValue(':atual', $produtoId, PDO::PARAM_INT);
        $consulta->execute();

        return (bool) $consulta->fetchColumn();
    }

    /** Total de produtos, usado no painel. */
    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM produtos')->fetchColumn();
    }
}
