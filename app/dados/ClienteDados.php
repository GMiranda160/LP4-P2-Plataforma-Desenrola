<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela clientes. Sem validação e sem HTML. Todo valor vai em parâmetro.
 */

class ClienteDados
{
    public function listar(string $busca = ''): array
    {
        $busca = trim($busca);
        $digitos = preg_replace('/\D/', '', $busca);

        $sql = 'SELECT * FROM clientes WHERE nome ILIKE :busca';
        $parametros = ['busca' => "%{$busca}%"];

        if ($digitos !== '') {
            $sql .= ' OR cpf_cnpj ILIKE :cpf';
            $parametros['cpf'] = "%{$digitos}%";
        }

        $sql .= ' ORDER BY nome';

        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute($parametros);
        return $consulta->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM clientes WHERE id = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    public function existeCpfCnpj(string $cpfCnpj, int $ignorarId = 0): bool
    {
        $sql = 'SELECT 1 FROM clientes WHERE cpf_cnpj = :cpf_cnpj AND id <> :id';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['cpf_cnpj' => $cpfCnpj, 'id' => $ignorarId]);
        return (bool) $consulta->fetchColumn();
    }

    public function inserir(array $d): void
    {
        $sql = 'INSERT INTO clientes (nome, cpf_cnpj, email, telefone, endereco, cep)
                VALUES (:nome, :cpf_cnpj, :email, :telefone, :endereco, :cep)';
        Conexao::pdo()->prepare($sql)->execute($d);
    }

    public function atualizar(int $id, array $d): void
    {
        $sql = 'UPDATE clientes SET nome = :nome, cpf_cnpj = :cpf_cnpj, email = :email,
                telefone = :telefone, endereco = :endereco, cep = :cep
                WHERE id = :id';
        Conexao::pdo()->prepare($sql)->execute($d + ['id' => $id]);
    }

    public function excluir(int $id): void
    {
        Conexao::pdo()->prepare('DELETE FROM clientes WHERE id = :id')->execute(['id' => $id]);
    }

    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM clientes')->fetchColumn();
    }
}