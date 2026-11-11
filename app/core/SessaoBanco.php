<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: handler de sessão do PHP que grava na tabela sessoes.
 *
 * Por que existe: o disco do servidor não é durável. No Railway e no Render,
 * a cada deploy ou reinício os arquivos de sessão somem e o visitante é
 * deslogado. A tabela sessoes no Supabase resolve isso.
 *
 * A coluna dados é text, nunca jsonb: a serialização da sessão tem caracteres
 * que quebrariam em JSON.
 */
class SessaoBanco implements SessionHandlerInterface
{
    public function open(string $caminho, string $nome): bool
    {
        // A conexão é criada sob demanda por Conexao::pdo(), nada a preparar.
        return true;
    }

    public function close(): bool
    {
        // A conexão fica viva para o resto da requisição, nada a fechar aqui.
        return true;
    }

    /** Lê os dados da sessão pela id. Devolve string vazia se não existir ou expirou. */
    public function read(string $id): string|false
    {
        $consulta = Conexao::pdo()->prepare(
            'SELECT dados FROM sessoes WHERE id = :id AND expira_em > now()'
        );
        $consulta->execute(['id' => $id]);

        return (string) $consulta->fetchColumn();
    }

    /** Grava ou atualiza a sessão, com expiração de 1 dia a cada escrita. */
    public function write(string $id, string $dados): bool
    {
        $consulta = Conexao::pdo()->prepare(
            "INSERT INTO sessoes (id, dados, expira_em)
             VALUES (:id, :dados, now() + interval '1 day')
             ON CONFLICT (id) DO UPDATE
             SET dados = EXCLUDED.dados, expira_em = EXCLUDED.expira_em"
        );

        return $consulta->execute(['id' => $id, 'dados' => $dados]);
    }

    /** Apaga a sessão (usado no logout). */
    public function destroy(string $id): bool
    {
        return Conexao::pdo()->prepare('DELETE FROM sessoes WHERE id = :id')->execute(['id' => $id]);
    }

    /** Remove sessões expiradas. Chamado pelo PHP em session_gc_probability. */
    public function gc(int $duracao): int|false
    {
        return Conexao::pdo()->exec('DELETE FROM sessoes WHERE expira_em < now()');
    }
}
