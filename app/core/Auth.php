<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: guardar e consultar o usuário logado.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Auth
{
    /** TODO(Guilherme): gravar id, nome e perfil na sessão, nunca hashes, e usar session_regenerate_id(true). */
    public static function entrar(array $usuario): void
    {
        throw new RuntimeException('Não implementado: Auth::entrar');
    }

    /** TODO(Guilherme): encerrar a sessão. */
    public static function sair(): void
    {
        throw new RuntimeException('Não implementado: Auth::sair');
    }

    /** TODO(Guilherme): devolver o usuário logado ou null. */
    public static function usuario(): ?array
    {
        throw new RuntimeException('Não implementado: Auth::usuario');
    }

    /** TODO(Guilherme): devolver o id do usuário logado ou null. */
    public static function id(): ?int
    {
        throw new RuntimeException('Não implementado: Auth::id');
    }

    /** TODO(Guilherme): informar se o perfil é gerente. */
    public static function eGerente(): bool
    {
        throw new RuntimeException('Não implementado: Auth::eGerente');
    }
}
