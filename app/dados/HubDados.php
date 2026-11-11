<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL do Hub de bordados. Sem validação e sem HTML.
 */
class HubDados
{
    /**
     * Os mais curtidos entre os posts públicos do Hub. Para visitante, :usuario = 0.
     * Busca $limite registros, ordenados por curtidas (desempate por data).
     */
    public function maisCurtidos(int $limite = 6, int $usuario = 0): array
    {
        $sql = 'SELECT b.id, b.titulo, b.cor_peca, b.pos_x, b.pos_y, b.escala, b.oculto, b.criado_em,
                       u.nome AS autor, c.nome AS categoria, c.mockup, m.svg,
                       (SELECT count(*) FROM curtidas x WHERE x.bordado_id = b.id) AS curtidas,
                       (SELECT count(*) FROM comentarios x
                         WHERE x.bordado_id = b.id AND NOT x.oculto) AS comentarios,
                       EXISTS (
                         SELECT 1 FROM curtidas x
                         WHERE x.bordado_id = b.id AND x.usuario_id = :usuario
                       ) AS curtiu
                FROM bordados b
                JOIN usuarios u ON u.id = b.autor_id
                JOIN categorias c ON c.id = b.categoria_id
                JOIN matrizes m ON m.id = b.matriz_id
                WHERE b.tipo = \'hub\' AND NOT b.oculto
                ORDER BY curtidas DESC, b.criado_em DESC
                LIMIT :limite';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll();
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
