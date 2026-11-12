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
 *
 * A vida da sessão varia pelo perfil guardado na sessão:
 * - visitante e público: 1 dia;
 * - usuário logado: 1 semana;
 * - gerente: 30 dias.
 * A contagem é deslizante: cada escrita renova a expiração a partir de agora.
 */
class SessaoBanco implements SessionHandlerInterface
{
    /** Intervalos aceitos pelo PostgreSQL, usados como literais (nunca vêm do usuário). */
    private const VIDA_PUBLICO = '1 day';
    private const VIDA_USUARIO = '1 week';
    private const VIDA_GERENTE = '30 days';

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

    /**
     * Segundos restantes de vida da sessão, ou null se não existir ou expirou.
     * Usado para o cookie durar exatamente o que a sessão no banco dura.
     */
    public function restante(string $id): ?int
    {
        $consulta = Conexao::pdo()->prepare(
            'SELECT EXTRACT(EPOCH FROM (expira_em - now()))::int AS restante
             FROM sessoes WHERE id = :id AND expira_em > now()'
        );
        $consulta->execute(['id' => $id]);
        $restante = $consulta->fetchColumn();

        return $restante === false ? null : max(0, (int) $restante);
    }

    /** Grava ou atualiza a sessão, com a expiração do perfil a cada escrita. */
    public function write(string $id, string $dados): bool
    {
        $intervalo = self::intervaloPorPerfil();

        $consulta = Conexao::pdo()->prepare(
            "INSERT INTO sessoes (id, dados, expira_em)
             VALUES (:id, :dados, now() + interval '" . $intervalo . "')
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

    /** Remove sessões expiradas. Chamado pelo PHP em session_gc_probability ou pela limpeza probabilística. */
    public function gc(int $duracao): int|false
    {
        return Conexao::pdo()->exec('DELETE FROM sessoes WHERE expira_em < now()');
    }

    /**
     * Escolhe o intervalo de expiração pelo perfil guardado na sessão.
     * O valor devolvido é sempre um literal nosso, nunca algo vindo do cliente.
     */
    private static function intervaloPorPerfil(): string
    {
        $perfil = $_SESSION['usuario']['perfil'] ?? null;

        return match ($perfil) {
            'gerente' => self::VIDA_GERENTE,
            'usuario' => self::VIDA_USUARIO,
            default => self::VIDA_PUBLICO,
        };
    }
}
