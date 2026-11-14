<?php

declare(strict_types=1);

/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL da tabela pecas (peças base com foto real usadas no montador).
 * A foto fica no Storage; aqui só o caminho. Sem validação e sem HTML. Todo valor vai em parâmetro.
 */
class PecaDados
{
    private const COLUNAS = 'p.id, p.nome, p.categoria_id, p.imagem_path, p.ativo, p.criado_em,
                             c.nome AS categoria, c.slug AS categoria_slug';

    /** Peças com o nome da categoria, na ordem de cadastro. Com $apenasAtivas, só as que aparecem no montador. */
    public function listar(bool $apenasAtivas = false): array
    {
        $sql = 'SELECT ' . self::COLUNAS . '
                FROM pecas p
                JOIN categorias c ON c.id = p.categoria_id'
            . ($apenasAtivas ? ' WHERE p.ativo' : '')
            . ' ORDER BY p.id';

        return Conexao::pdo()->query($sql)->fetchAll();
    }

    /** Uma peça, ou null se não existir. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT ' . self::COLUNAS . '
                                             FROM pecas p JOIN categorias c ON c.id = p.categoria_id
                                             WHERE p.id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch() ?: null;
    }

    /** Grava uma peça nova e devolve o id. Recebe nome, categoria_id, imagem_path e ativo (bool). */
    public function inserir(array $d): int
    {
        $consulta = Conexao::pdo()->prepare(
            'INSERT INTO pecas (nome, categoria_id, imagem_path, ativo)
             VALUES (:nome, :categoria, :imagem, :ativo) RETURNING id'
        );
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':categoria', (int) $d['categoria_id'], PDO::PARAM_INT);
        $consulta->bindValue(':imagem', $d['imagem_path']);
        $consulta->bindValue(':ativo', !empty($d['ativo']) ? 't' : 'f');
        $consulta->execute();

        return (int) $consulta->fetchColumn();
    }

    /** Atualiza nome, categoria, foto e ativo de uma peça existente. */
    public function atualizar(int $id, array $d): void
    {
        $consulta = Conexao::pdo()->prepare(
            'UPDATE pecas SET nome = :nome, categoria_id = :categoria, imagem_path = :imagem, ativo = :ativo
             WHERE id = :id'
        );
        $consulta->bindValue(':nome', $d['nome']);
        $consulta->bindValue(':categoria', (int) $d['categoria_id'], PDO::PARAM_INT);
        $consulta->bindValue(':imagem', $d['imagem_path']);
        $consulta->bindValue(':ativo', !empty($d['ativo']) ? 't' : 'f');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Exclui a peça e devolve o caminho da foto dela (ou null se a peça não existia). */
    public function excluir(int $id): ?string
    {
        $consulta = Conexao::pdo()->prepare('DELETE FROM pecas WHERE id = :id RETURNING imagem_path');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $caminho = $consulta->fetchColumn();

        return $caminho === false ? null : (string) $caminho;
    }
}
