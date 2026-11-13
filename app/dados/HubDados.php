<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL do Hub de bordados. Sem validação e sem HTML.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class HubDados
{
    /**
     * Campos do card do Hub, iguais ao contrato de hub/_card.php.
     * Usa :usuario para calcular "curtiu"; visitante passa 0.
     */
    private const CAMPOS_CARD = 'b.id, b.titulo, b.cor_peca, b.pos_x, b.pos_y, b.escala, b.oculto, b.criado_em,
                       u.nome AS autor, c.nome AS categoria, c.mockup, m.svg,
                       (SELECT count(*) FROM curtidas x WHERE x.bordado_id = b.id) AS curtidas,
                       (SELECT count(*) FROM comentarios x
                         WHERE x.bordado_id = b.id AND NOT x.oculto) AS comentarios,
                       EXISTS (
                         SELECT 1 FROM curtidas x
                         WHERE x.bordado_id = b.id AND x.usuario_id = :usuario
                       ) AS curtiu';

    private const JUNCOES = 'FROM bordados b
                JOIN usuarios u ON u.id = b.autor_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id';

    /**
     * Uma página do feed: posts públicos do Hub, não ocultos.
     * $ordemSql vem de uma lista fixa do HubServico, nunca da URL.
     * $categoria é o slug, ou null para todas.
     */
    public function feed(int $usuario, ?string $categoria, string $ordemSql, int $limite, int $inicio): array
    {
        $sql = 'SELECT ' . self::CAMPOS_CARD . ' ' . self::JUNCOES . "
                WHERE b.tipo = 'hub' AND NOT b.oculto"
            . ($categoria !== null ? ' AND c.slug = :categoria' : '')
            . ' ORDER BY ' . $ordemSql . '
                LIMIT :limite OFFSET :inicio';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_INT);
        if ($categoria !== null) {
            $consulta->bindValue(':categoria', $categoria);
        }
        $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
        $consulta->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /** Os mais curtidos entre os posts públicos do Hub, para a home. Visitante usa :usuario = 0. */
    public function maisCurtidos(int $limite = 6, int $usuario = 0): array
    {
        return $this->feed($usuario, null, 'curtidas DESC, b.criado_em DESC', $limite, 0);
    }

    /** Bordados do Hub de um autor, inclusive os ocultos, para "Meus bordados". */
    public function doAutor(int $autorId): array
    {
        $sql = 'SELECT ' . self::CAMPOS_CARD . ' ' . self::JUNCOES . "
                WHERE b.tipo = 'hub' AND b.autor_id = :autor
                ORDER BY b.criado_em DESC";

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':usuario', $autorId, PDO::PARAM_INT);
        $consulta->bindValue(':autor', $autorId, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /**
     * Um bordado com os campos do card, mais os da página de detalhe.
     * Devolve oculto e tipo para o serviço decidir quem pode ver. Null se não existir.
     */
    public function detalhe(int $id, int $usuario): ?array
    {
        $sql = 'SELECT ' . self::CAMPOS_CARD . ',
                       b.descricao, b.autor_id, b.tipo, b.tutorial_ia,
                       m.largura, m.altura, m.total_pontos, m.grade
                ' . self::JUNCOES . '
                WHERE b.id = :id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch() ?: null;
    }

    /** Dados mínimos para checar permissão: autor_id, tipo e oculto. Null se não existir. */
    public function buscarDono(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT id, autor_id, tipo, oculto FROM bordados WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch() ?: null;
    }

    /** Tenta curtir. Devolve true se gravou e false se a curtida já existia. */
    public function inserirCurtida(int $usuarioId, int $bordadoId): bool
    {
        $consulta = Conexao::pdo()->prepare(
            'INSERT INTO curtidas (usuario_id, bordado_id) VALUES (:usuario, :bordado)
             ON CONFLICT DO NOTHING'
        );
        $consulta->bindValue(':usuario', $usuarioId, PDO::PARAM_INT);
        $consulta->bindValue(':bordado', $bordadoId, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount() > 0;
    }

    /** Remove a curtida do usuário. */
    public function removerCurtida(int $usuarioId, int $bordadoId): void
    {
        $consulta = Conexao::pdo()->prepare(
            'DELETE FROM curtidas WHERE usuario_id = :usuario AND bordado_id = :bordado'
        );
        $consulta->bindValue(':usuario', $usuarioId, PDO::PARAM_INT);
        $consulta->bindValue(':bordado', $bordadoId, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Total de curtidas de um bordado. */
    public function curtidasDo(int $bordadoId): int
    {
        $consulta = Conexao::pdo()->prepare('SELECT count(*) FROM curtidas WHERE bordado_id = :bordado');
        $consulta->bindValue(':bordado', $bordadoId, PDO::PARAM_INT);
        $consulta->execute();

        return (int) $consulta->fetchColumn();
    }

    /** Exclui o bordado. Curtidas e comentários saem junto (ON DELETE CASCADE). */
    public function excluir(int $id): void
    {
        $consulta = Conexao::pdo()->prepare('DELETE FROM bordados WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Inverte bordados.oculto. */
    public function alternarOculto(int $id): void
    {
        $consulta = Conexao::pdo()->prepare('UPDATE bordados SET oculto = NOT oculto WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Total de posts públicos do Hub, para o painel. */
    public function contarPublicados(): int
    {
        return (int) Conexao::pdo()->query(
            "SELECT count(*) FROM bordados WHERE tipo = 'hub' AND NOT oculto"
        )->fetchColumn();
    }

    /** Total de curtidas do Hub, para o painel. */
    public function contarCurtidas(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM curtidas')->fetchColumn();
    }
}
