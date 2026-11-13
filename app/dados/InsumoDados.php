<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela insumos. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */

class InsumoDados
{
    /** Insumos com o nome parecido com a busca (ILIKE), ordenados por tipo e nome. */
    public function listar(string $busca = ''): array
    {
        $sql = 'SELECT * FROM insumos WHERE nome ILIKE :busca ORDER BY tipo, nome';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['busca' => "%{$busca}%"]);
        return $consulta->fetchAll();
    }

    /** Um insumo pelo id, ou null. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM insumos WHERE id = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    /** Verdadeiro se já existe outro insumo do mesmo tipo com este nome. $ignorarId é o próprio registro na edição. */
    public function existeNome(string $tipo, string $nome, int $ignorarId = 0): bool
    {
        $sql = 'SELECT 1 FROM insumos WHERE tipo = :tipo AND lower(nome) = lower(:nome) AND id <> :id';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['tipo' => $tipo, 'nome' => $nome, 'id' => $ignorarId]);
        return (bool) $consulta->fetchColumn();
    }

    /** Grava um insumo novo. $d tem as mesmas chaves das colunas. */
    public function inserir(array $d): void
    {
        $sql = 'INSERT INTO insumos
                (tipo, nome, cor_hex, quantidade, unidade, custo_unitario, fornecedor, estoque_minimo)
                VALUES
                (:tipo, :nome, :cor_hex, :quantidade, :unidade, :custo_unitario, :fornecedor, :estoque_minimo)';
        Conexao::pdo()->prepare($sql)->execute($d);
    }

    /** Atualiza todas as colunas editáveis do insumo. */
    public function atualizar(int $id, array $d): void
    {
        $sql = 'UPDATE insumos SET tipo = :tipo, nome = :nome, cor_hex = :cor_hex,
                quantidade = :quantidade, unidade = :unidade, custo_unitario = :custo_unitario,
                fornecedor = :fornecedor, estoque_minimo = :estoque_minimo
                WHERE id = :id';
        Conexao::pdo()->prepare($sql)->execute($d + ['id' => $id]);
    }

    /** Exclui o insumo. */
    public function excluir(int $id): void
    {
        Conexao::pdo()->prepare('DELETE FROM insumos WHERE id = :id')->execute(['id' => $id]);
    }

    /** Total de insumos, usado no painel. */
    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM insumos')->fetchColumn();
    }
}