<?php
/**
 * Camada: Dados. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: SQL da tabela usuarios. Sem validação e sem HTML.
 * Outras partes usam estes métodos. Não mude as assinaturas.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class UsuarioDados
{
    /** O usuário com este e-mail, com os hashes, ou null. Os hashes nunca saem do serviço. */
    public function buscarPorEmail(string $email): ?array
    {
        $pdo = Conexao::pdo();
        $consulta = $pdo->prepare(
        'SELECT id, nome, email, senha_hash, perfil, pergunta_seguranca,
            resposta_hash, ativo, criado_em
        FROM usuarios
        WHERE email = :email
        LIMIT 1'
        );
        $consulta->execute(['email' => $email]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $usuario === false ? null : $usuario;
    }

    /** O usuário com este id, com os hashes, ou null. */
    public function buscarPorId(int $id): ?array
    {
        $pdo = Conexao::pdo();
        $consulta = $pdo->prepare(
        'SELECT id, nome, email, senha_hash, perfil, pergunta_seguranca,
            resposta_hash, ativo, criado_em
        FROM usuarios
        WHERE id = :id
        LIMIT 1'
        );
        $consulta->execute(['id' => $id]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $usuario === false ? null : $usuario;
    }

    /** Grava a conta nova como ativa e devolve o id com RETURNING id. */
    public function inserir(array $d): int
    {
        $pdo = Conexao::pdo();
        $consulta = $pdo->prepare(
            'INSERT INTO usuarios
                (nome, email, senha_hash, perfil, pergunta_seguranca,
                resposta_hash, ativo)
            VALUES
                (:nome, :email, :senha_hash, :perfil, :pergunta_seguranca,
                :resposta_hash, :ativo)
            RETURNING id'
        );

        $consulta->execute([
            'nome' => $d['nome'],
            'email' => $d['email'],
            'senha_hash' => $d['senha_hash'],
            'perfil' => $d['perfil'],
            'pergunta_seguranca' => $d['pergunta_seguranca'],
            'resposta_hash' => $d['resposta_hash'],
            'ativo' => 't',
        ]);

        return (int) $consulta->fetchColumn();
    }

    /** Troca o hash da senha. */
    public function atualizarSenha(int $id, string $hash): void
    {
        $consulta = Conexao::pdo()->prepare(
            'UPDATE usuarios
            SET senha_hash = :hash
            WHERE id = :id'
        );

        $consulta->execute([
            'hash' => $hash,
            'id' => $id,
        ]);
    }

    /** Lista para a gerente: id, nome, email, perfil, criado_em e total_bordados, sem nenhum hash. */
    public function listar(string $busca = ''): array
    {
        $pdo = Conexao::pdo();

        $sql = '
            SELECT
                u.id,
                u.nome,
                u.email,
                u.perfil,
                u.criado_em,
                (
                    SELECT COUNT(*)
                    FROM bordados b
                    WHERE b.autor_id = u.id
                ) AS total_bordados
            FROM usuarios u
        ';

        $parametros = [];

        if ($busca !== '') {
            $sql .= '
                WHERE u.nome ILIKE :busca
                OR u.email ILIKE :busca
            ';
            $parametros['busca'] = '%' . $busca . '%';
        }

        $sql .= ' ORDER BY u.nome';

        $consulta = $pdo->prepare($sql);
        $consulta->execute($parametros);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Total de usuários, usado no painel. */
    public function contar(): int
    {
        $consulta = Conexao::pdo()->query('SELECT COUNT(*) FROM usuarios');

        return (int) $consulta->fetchColumn();
    }
}
