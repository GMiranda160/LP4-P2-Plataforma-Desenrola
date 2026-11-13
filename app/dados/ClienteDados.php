<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela clientes. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */

class ClienteDados
{
    /** Clientes com o nome parecido com a busca, ou com o CPF/CNPJ contendo os dígitos digitados. */
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

    /** Um cliente pelo id, ou null. */
    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM clientes WHERE id = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    /** Verdadeiro se já existe outro cliente com este CPF/CNPJ. $ignorarId é o próprio registro na edição. */
    public function existeCpfCnpj(string $cpfCnpj, int $ignorarId = 0): bool
    {
        $sql = 'SELECT 1 FROM clientes WHERE cpf_cnpj = :cpf_cnpj AND id <> :id';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['cpf_cnpj' => $cpfCnpj, 'id' => $ignorarId]);
        return (bool) $consulta->fetchColumn();
    }

    /** Grava um cliente novo. $d tem as mesmas chaves das colunas. */
    public function inserir(array $d): void
    {
        $sql = 'INSERT INTO clientes (nome, cpf_cnpj, email, telefone, endereco, cep)
                VALUES (:nome, :cpf_cnpj, :email, :telefone, :endereco, :cep)';
        Conexao::pdo()->prepare($sql)->execute($d);
    }

    /** Atualiza todas as colunas editáveis do cliente. */
    public function atualizar(int $id, array $d): void
    {
        $sql = 'UPDATE clientes SET nome = :nome, cpf_cnpj = :cpf_cnpj, email = :email,
                telefone = :telefone, endereco = :endereco, cep = :cep
                WHERE id = :id';
        Conexao::pdo()->prepare($sql)->execute($d + ['id' => $id]);
    }

    /** Exclui o cliente. */
    public function excluir(int $id): void
    {
        Conexao::pdo()->prepare('DELETE FROM clientes WHERE id = :id')->execute(['id' => $id]);
    }

    /** Total de clientes, usado no painel. */
    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM clientes')->fetchColumn();
    }
}