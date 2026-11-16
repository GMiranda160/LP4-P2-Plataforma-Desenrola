<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL de gravação e leitura de bordados para o montador e o tutorial.
 * Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_GUILHERME.md, seções 7 e 8.
 */
class BordadoDados
{
    /**
     * Insere o bordado e devolve o id.
     * Recebe: titulo, descricao, autor_id, matriz_id, categoria_id, cor_peca,
     * pos_x, pos_y, escala, rotacao (graus, -180 a 180), peca_id e foto_path (opcionais)
     * e tipo (hub ou produto).
     */
    public function inserir(array $d): int
    {
        $sql = 'INSERT INTO bordados
                    (titulo, descricao, autor_id, matriz_id, categoria_id,
                     cor_peca, pos_x, pos_y, escala, rotacao, peca_id, foto_path, tipo)
                VALUES
                    (:titulo, :descricao, :autor, :matriz, :categoria,
                     :cor, :pos_x, :pos_y, :escala, :rotacao, :peca, :foto, :tipo)
                RETURNING id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':titulo', $d['titulo']);
        $consulta->bindValue(':descricao', $d['descricao'] ?? null);
        $consulta->bindValue(':autor', (int) $d['autor_id'], PDO::PARAM_INT);
        $consulta->bindValue(':matriz', (int) $d['matriz_id'], PDO::PARAM_INT);
        $consulta->bindValue(':categoria', (int) $d['categoria_id'], PDO::PARAM_INT);
        $consulta->bindValue(':cor', $d['cor_peca']);
        $consulta->bindValue(':pos_x', (int) $d['pos_x'], PDO::PARAM_INT);
        $consulta->bindValue(':pos_y', (int) $d['pos_y'], PDO::PARAM_INT);
        $consulta->bindValue(':escala', (int) $d['escala'], PDO::PARAM_INT);
        $consulta->bindValue(':rotacao', (int) ($d['rotacao'] ?? 0), PDO::PARAM_INT);
        $peca = $d['peca_id'] ?? null;
        $consulta->bindValue(':peca', $peca, $peca === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $consulta->bindValue(':foto', $d['foto_path'] ?? null, ($d['foto_path'] ?? null) === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $d['tipo']);

        $consulta->execute();

        return (int) $consulta->fetchColumn();
    }

    /**
     * Devolve o bordado com a categoria e os dados da matriz, para o tutorial de IA.
     * Inclui paleta (decodificada de matrizes.paleta). Null se não existir.
     */
    public function buscarParaTutorial(int $id): ?array
    {
        $sql = 'SELECT b.id, b.titulo, b.descricao, b.autor_id, b.matriz_id, b.categoria_id,
                       b.cor_peca, b.pos_x, b.pos_y, b.escala, b.rotacao, b.tipo, b.tutorial_ia, b.criado_em,
                       m.largura, m.altura, m.total_pontos, m.paleta, COALESCE(m.svg_path, m.svg) AS svg,
                       c.nome AS categoria,
                       pr.nome AS produto_nome, pr.descricao AS produto_descricao,
                       pc.nome AS peca_nome
                FROM bordados b
                JOIN matrizes m ON m.id = b.matriz_id
                JOIN categorias c ON c.id = b.categoria_id
                LEFT JOIN produtos pr ON pr.bordado_id = b.id
                LEFT JOIN pecas pc ON pc.id = b.peca_id
                WHERE b.id = :id';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $linha = $consulta->fetch();

        if ($linha === false) {
            return null;
        }

        $paleta = json_decode((string) ($linha['paleta'] ?? '[]'), true);
        $linha['paleta'] = is_array($paleta) ? array_values($paleta) : [];

        return $linha;
    }

    /** Grava o texto do tutorial gerado pela IA no bordado. */
    public function salvarTutorial(int $id, string $texto): void
    {
        $consulta = Conexao::pdo()->prepare('UPDATE bordados SET tutorial_ia = :texto WHERE id = :id');
        $consulta->bindValue(':texto', $texto);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Conta os bordados do usuário na última hora. Usado para barrar abuso (máx. 20 por hora). */
    public function contarRecentes(int $usuarioId): int
    {
        $sql = "SELECT count(*) FROM bordados
                WHERE autor_id = :autor AND criado_em > now() - interval '1 hour'";

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->bindValue(':autor', $usuarioId, PDO::PARAM_INT);
        $consulta->execute();

        return (int) $consulta->fetchColumn();
    }
}
