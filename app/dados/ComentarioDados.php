<?php
/**
 * Camada: Dados. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: SQL da tabela comentarios. Sem validação e sem HTML.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioDados
{
    /** TODO(Eduardo): buscar com JOIN em usuarios. Sem ocultos, filtrar AND NOT c.oculto. */
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

    /** TODO(Eduardo): devolver o comentário, ou null. */
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

    /** TODO(Eduardo): executar o INSERT. */
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

    /** TODO(Eduardo): executar o DELETE. */
    public function excluir(int $id): void
    {
        $consulta = Conexao::pdo()->prepare(
            'DELETE FROM comentarios
            WHERE id = :id'
        );

        $consulta->execute(['id' => $id]);
    }

    /** TODO(Eduardo): inverter oculto. */
    public function alternarOculto(int $id): void
    {
        $consulta = Conexao::pdo()->prepare(
            'UPDATE comentarios
            SET oculto = NOT oculto
            WHERE id = :id'
        );

        $consulta->execute(['id' => $id]);
    }

    /** TODO(Eduardo): executar SELECT 1 FROM bordados WHERE id = :id AND NOT oculto. */
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

    /** TODO(Eduardo): devolver o total, usado no painel. */
    public function contar(): int
    {
        $consulta = Conexao::pdo()->query(
            'SELECT COUNT(*) FROM comentarios'
        );

        return (int) $consulta->fetchColumn();
    }
}
