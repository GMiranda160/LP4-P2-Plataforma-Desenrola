<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: login e estado do usuário na sessão.
 *
 * Na sessão fica apenas o necessário para a interface: id, nome, e-mail e
 * perfil. Senha e hash nunca entram na sessão nem chegam às telas.
 */
class Auth
{
    /**
     * Registra o usuário na sessão e regenera o id da sessão.
     * A regeneração impede o ataque de fixation (id da sessão roubado antes do login).
     */
    public static function entrar(array $usuario): void
    {
        session_regenerate_id(true);

        Sessao::definir('usuario', [
            'id' => (int) $usuario['id'],
            'nome' => (string) $usuario['nome'],
            'email' => (string) $usuario['email'],
            'perfil' => (string) ($usuario['perfil'] ?? 'usuario'),
        ]);
    }

    /** Encerra a sessão do usuário e descarta os cookies de sessão. */
    public static function sair(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $parametros['path'], $parametros['domain'], $parametros['secure'], $parametros['httponly']);
        }

        session_destroy();
    }

    /** Devolve o usuário logado (id, nome, email, perfil), ou null para visitante. */
    public static function usuario(): ?array
    {
        $usuario = Sessao::obter('usuario');

        return is_array($usuario) ? $usuario : null;
    }

    /** Devolve o id do usuário logado, ou null. */
    public static function id(): ?int
    {
        $usuario = self::usuario();

        return $usuario !== null ? (int) $usuario['id'] : null;
    }

    /** Diz se o usuário logado é gerente. */
    public static function eGerente(): bool
    {
        $usuario = self::usuario();

        return $usuario !== null && ($usuario['perfil'] ?? '') === 'gerente';
    }
}
