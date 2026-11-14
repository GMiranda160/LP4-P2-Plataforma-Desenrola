<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL da tabela matrizes. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class MatrizDados
{
    /**
     * Colunas usadas nas listas. O campo svg leva o caminho do arquivo no Storage
     * (ou o SVG cru das matrizes antigas); svg_data_uri() transforma em src de img.
     */
    private const COLUNAS_LISTA = 'id, nome, descricao, categoria, COALESCE(svg_path, svg) AS svg, largura, altura,
                                   total_pontos, tempo_medio_min, padrao, autor_id, criado_em';

    /**
     * Grava uma matriz nova e devolve o id.
     * Recebe: nome, descricao, categoria, svg_path e grade_path (caminhos no Storage),
     * paleta (JSON em texto), largura, altura, total_pontos, tempo_medio_min, padrao (bool)
     * e autor_id (int ou null). Os arquivos já devem estar no Storage.
     * Chamado pelo MontadorServico, dentro da transação do bordado.
     */
    public function inserir(array $d): int
    {
        $sql = 'INSERT INTO matrizes
                (nome, descricao, categoria, svg_path, grade_path, paleta, largura, altura,
                 total_pontos, tempo_medio_min, padrao, autor_id)
                VALUES
                (:nome, :descricao, :categoria, :svg_path, :grade_path, CAST(:paleta AS jsonb), :largura, :altura,
                 :total_pontos, :tempo_medio_min, :padrao, :autor_id)
                RETURNING id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':descricao', $d['descricao'] ?? null);
        $consulta->bindValue(':categoria', $d['categoria'] ?? 'Geral');
        $consulta->bindValue(':svg_path', $d['svg_path']);
        $consulta->bindValue(':grade_path', $d['grade_path']);
        $consulta->bindValue(':paleta', $d['paleta']);
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

    /** Devolve a matriz (com caminhos dos arquivos e paleta), ou null se não existir. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT ' . self::COLUNAS_LISTA . ', svg_path, grade_path, paleta
                                             FROM matrizes WHERE id = :id');
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

    /** Todas as matrizes (visibilidade geral no montador). Matrizes não têm alternância de visibilidade. */
    public function listarTodas(): array
    {
        $sql = 'SELECT ' . self::COLUNAS_LISTA . ' FROM matrizes ORDER BY categoria, nome';

        return Conexao::pdo()->query($sql)->fetchAll();
    }

    /**
     * Matrizes criadas por usuários (não padrão), das mais novas para as mais antigas, com o nome
     * do autor. Usada na gestão da gerente. Autor excluído aparece como null (ON DELETE SET NULL).
     */
    public function listarDeUsuarios(int $limite = 50): array
    {
        $sql = 'SELECT m.id, m.nome, m.descricao, m.categoria, COALESCE(m.svg_path, m.svg) AS svg, m.largura, m.altura,
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

    /**
     * Exclui a matriz e devolve os caminhos dos arquivos dela no Storage, para o serviço apagar.
     * O banco recusa (ON DELETE RESTRICT) se ela estiver em uso.
     */
    public function excluir(int $id): array
    {
        $consulta = Conexao::pdo()->prepare('DELETE FROM matrizes WHERE id = :id RETURNING svg_path, grade_path');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return array_values(array_filter($consulta->fetch() ?: []));
    }

    /** Total de matrizes, usado no painel. */
    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM matrizes')->fetchColumn();
    }
}
