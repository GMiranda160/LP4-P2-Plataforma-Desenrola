<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL da tabela matrizes. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class MatrizDados
{
    /** Colunas usadas nas listas. A grade (jsonb) fica de fora porque é pesada e a lista não usa. */
    private const COLUNAS_LISTA = 'id, nome, descricao, categoria, svg, largura, altura,
                                   total_pontos, tempo_medio_min, padrao, autor_id, criado_em';

    /**
     * Grava uma matriz nova e devolve o id.
     * Recebe: nome, descricao, categoria, grade (JSON em texto), svg, largura, altura,
     * total_pontos, tempo_medio_min, padrao (bool) e autor_id (int ou null).
     * Chamado pelo MontadorServico, dentro da transação do bordado.
     */
    public function inserir(array $d): int
    {
        $sql = 'INSERT INTO matrizes
                (nome, descricao, categoria, grade, svg, largura, altura,
                 total_pontos, tempo_medio_min, padrao, autor_id)
                VALUES
                (:nome, :descricao, :categoria, CAST(:grade AS jsonb), :svg, :largura, :altura,
                 :total_pontos, :tempo_medio_min, :padrao, :autor_id)
                RETURNING id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':descricao', $d['descricao'] ?? null);
        $consulta->bindValue(':categoria', $d['categoria'] ?? 'Geral');
        $consulta->bindValue(':grade', $d['grade']);
        $consulta->bindValue(':svg', $d['svg']);
        $consulta->bindValue(':largura', (int) $d['largura'], PDO::PARAM_INT);
        $consulta->bindValue(':altura', (int) $d['altura'], PDO::PARAM_INT);
        $consulta->bindValue(':total_pontos', (int) $d['total_pontos'], PDO::PARAM_INT);
        $consulta->bindValue(':tempo_medio_min', (int) ($d['tempo_medio_min'] ?? 30), PDO::PARAM_INT);
        // Prepares emulados: booleano vai como 't' ou 'f'.
        $consulta->bindValue(':padrao', !empty($d['padrao']) ? 't' : 'f');

        $autor = $d['autor_id'] ?? null;
        $consulta->bindValue(':autor_id', $autor, $autor === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $consulta->execute();

        return (int) $consulta->fetchColumn();
    }

    /** Devolve a matriz completa (com a grade), ou null se não existir. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM matrizes WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch() ?: null;
    }

    /** Matrizes padrão do ateliê: aparecem para todos no montador e na gestão da gerente. */
    public function listarPadrao(): array
    {
        $sql = 'SELECT ' . self::COLUNAS_LISTA . ' FROM matrizes WHERE padrao ORDER BY categoria, nome';

        return Conexao::pdo()->query($sql)->fetchAll();
    }

    /** Matrizes que o próprio usuário criou no montador, das mais novas para as mais antigas. */
    public function listarDoAutor(int $autorId): array
    {
        $sql = 'SELECT ' . self::COLUNAS_LISTA . ' FROM matrizes
                WHERE autor_id = :autor AND NOT padrao
                ORDER BY criado_em DESC';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':autor', $autorId, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /**
     * Matrizes criadas por usuários (não padrão), das mais novas para as mais antigas, com o nome
     * do autor. Usada na gestão da gerente. Autor excluído aparece como null (ON DELETE SET NULL).
     */
    public function listarDeUsuarios(int $limite = 50): array
    {
        $sql = 'SELECT m.id, m.nome, m.descricao, m.categoria, m.svg, m.largura, m.altura,
                       m.total_pontos, m.tempo_medio_min, m.autor_id, m.criado_em,
                       u.nome AS autor
                FROM matrizes m
                LEFT JOIN usuarios u ON u.id = m.autor_id
                WHERE NOT m.padrao
                ORDER BY m.criado_em DESC
                LIMIT :limite';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /** Atualiza só os dados descritivos. Grade, SVG e tamanho vêm do montador e não mudam aqui. */
    public function atualizarDados(int $id, array $d): void
    {
        $sql = 'UPDATE matrizes
                SET nome = :nome, descricao = :descricao, categoria = :categoria,
                    tempo_medio_min = :tempo_medio_min
                WHERE id = :id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':descricao', $d['descricao']);
        $consulta->bindValue(':categoria', $d['categoria']);
        $consulta->bindValue(':tempo_medio_min', (int) $d['tempo_medio_min'], PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Verdadeiro se algum bordado usa a matriz. Nesse caso ela não pode ser excluída. */
    public function emUso(int $id): bool
    {
        $consulta = Conexao::pdo()->prepare('SELECT 1 FROM bordados WHERE matriz_id = :id LIMIT 1');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return (bool) $consulta->fetchColumn();
    }

    /** Exclui a matriz. O banco recusa (ON DELETE RESTRICT) se ela estiver em uso. */
    public function excluir(int $id): void
    {
        $consulta = Conexao::pdo()->prepare('DELETE FROM matrizes WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Total de matrizes, usado no painel. */
    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM matrizes')->fetchColumn();
    }
}
