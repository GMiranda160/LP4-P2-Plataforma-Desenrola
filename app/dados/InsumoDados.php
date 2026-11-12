<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela insumos. Sem validação e sem HTML. Todo valor vai em parâmetro.
 */

class InsumoDados
{
    public function listar(string $busca = ''): array
    {
        $sql = 'SELECT * FROM insumos WHERE nome ILIKE :busca ORDER BY tipo, nome';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['busca' => "%{$busca}%"]);
        return $consulta->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM insumos WHERE id = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    public function existeNome(string $tipo, string $nome, int $ignorarId = 0): bool
    {
        $sql = 'SELECT 1 FROM insumos WHERE tipo = :tipo AND lower(nome) = lower(:nome) AND id <> :id';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['tipo' => $tipo, 'nome' => $nome, 'id' => $ignorarId]);
        return (bool) $consulta->fetchColumn();
    }

    public function inserir(array $d): void
    {
        $sql = 'INSERT INTO insumos
                (tipo, nome, cor_hex, quantidade, unidade, custo_unitario, fornecedor, estoque_minimo)
                VALUES
                (:tipo, :nome, :cor_hex, :quantidade, :unidade, :custo_unitario, :fornecedor, :estoque_minimo)';
        Conexao::pdo()->prepare($sql)->execute($d);
    }

    public function atualizar(int $id, array $d): void
    {
        $sql = 'UPDATE insumos SET tipo = :tipo, nome = :nome, cor_hex = :cor_hex,
                quantidade = :quantidade, unidade = :unidade, custo_unitario = :custo_unitario,
                fornecedor = :fornecedor, estoque_minimo = :estoque_minimo
                WHERE id = :id';
        Conexao::pdo()->prepare($sql)->execute($d + ['id' => $id]);
    }

    public function excluir(int $id): void
    {
        Conexao::pdo()->prepare('DELETE FROM insumos WHERE id = :id')->execute(['id' => $id]);
    }

    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM insumos')->fetchColumn();
    }
}