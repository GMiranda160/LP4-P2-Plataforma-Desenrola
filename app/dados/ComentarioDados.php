<?php
/**
 * Camada: Dados. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: SQL da tabela comentarios. Sem validação e sem HTML.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioDados
{
    /** Os comentários do bordado, do mais antigo ao mais novo, com o nome do autor pelo JOIN em usuarios. Sem $incluirOcultos, filtra AND NOT c.oculto. */
    public function listar(int $bordadoId, bool $incluirOcultos = false): array
    {
        $sql = '
            SELECT
                c.id,
                c.bordado_id,
                c.usuario_id,
                u.nome AS autor,
                c.texto,
                c.oculto,
                c.criado_em
            FROM comentarios c
            JOIN usuarios u ON u.id = c.usuario_id
            WHERE c.bordado_id = :bordado_id
        ';

        if (!$incluirOcultos) {
            $sql .= ' AND NOT c.oculto';
        }

        $sql .= ' ORDER BY c.criado_em ASC, c.id ASC';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['bordado_id' => $bordadoId]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Os últimos $limite comentários de cada bordado da lista, numa consulta só, para o feed.
     * Devolve [bordado_id => [comentários do mais antigo ao mais novo]]. Sem $incluirOcultos, filtra os ocultos.
     */
    public function ultimosPorBordados(array $bordadoIds, int $limite, bool $incluirOcultos = false): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $bordadoIds), static fn (int $id): bool => $id > 0)));

        if ($ids === []) {
            return [];
        }

        $marcas = implode(', ', array_fill(0, count($ids), '?'));
        $sql = '
            SELECT id, bordado_id, usuario_id, autor, texto, oculto, criado_em
            FROM (
                SELECT
                    c.id,
                    c.bordado_id,
                    c.usuario_id,
                    u.nome AS autor,
                    c.texto,
                    c.oculto,
                    c.criado_em,
                    ROW_NUMBER() OVER (PARTITION BY c.bordado_id ORDER BY c.criado_em DESC, c.id DESC) AS posicao
                FROM comentarios c
                JOIN usuarios u ON u.id = c.usuario_id
                WHERE c.bordado_id IN (' . $marcas . ')'
            . ($incluirOcultos ? '' : ' AND NOT c.oculto') . '
            ) ultimos
            WHERE posicao <= ?
            ORDER BY criado_em ASC, id ASC
        ';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute([...$ids, max(1, $limite)]);

        $porBordado = [];

        foreach ($consulta->fetchAll(PDO::FETCH_ASSOC) as $comentario) {
            $porBordado[(int) $comentario['bordado_id']][] = $comentario;
        }

        return $porBordado;
    }

    /** O comentário com este id, ou null se não existir. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare(
            'SELECT id, bordado_id, usuario_id, texto, oculto, criado_em
            FROM comentarios
            WHERE id = :id
            LIMIT 1'
        );

        $consulta->execute(['id' => $id]);

        $comentario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $comentario === false ? null : $comentario;
    }

    /** Grava o comentário. */
    public function inserir(int $bordadoId, int $usuarioId, string $texto): void
    {
        $consulta = Conexao::pdo()->prepare(
            'INSERT INTO comentarios (bordado_id, usuario_id, texto)
            VALUES (:bordado_id, :usuario_id, :texto)'
        );

        $consulta->execute([
            'bordado_id' => $bordadoId,
            'usuario_id' => $usuarioId,
            'texto' => $texto,
        ]);
    }

    /** Apaga o comentário pelo id. */
    public function excluir(int $id): void
    {
        $consulta = Conexao::pdo()->prepare(
            'DELETE FROM comentarios
            WHERE id = :id'
        );

        $consulta->execute(['id' => $id]);
    }

    /** Inverte o campo oculto do comentário. */
    public function alternarOculto(int $id): void
    {
        $consulta = Conexao::pdo()->prepare(
            'UPDATE comentarios
            SET oculto = NOT oculto
            WHERE id = :id'
        );

        $consulta->execute(['id' => $id]);
    }

    /** true se o bordado existe e não está oculto. */
    public function bordadoVisivel(int $bordadoId): bool
    {
        $consulta = Conexao::pdo()->prepare(
            'SELECT 1
            FROM bordados
            WHERE id = :id
            AND NOT oculto'
        );

        $consulta->execute(['id' => $bordadoId]);

        return $consulta->fetchColumn() !== false;
    }

    /** O total de comentários, usado no painel. */
    public function contar(): int
    {
        $consulta = Conexao::pdo()->query(
            'SELECT COUNT(*) FROM comentarios'
        );

        return (int) $consulta->fetchColumn();
    }
}
