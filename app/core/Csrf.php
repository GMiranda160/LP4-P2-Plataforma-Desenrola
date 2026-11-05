<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: proteger formulários contra CSRF.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Csrf
{
    /** TODO(Guilherme): criar ou devolver o token da sessão. */
    public static function token(): string
    {
        throw new RuntimeException('Não implementado: Csrf::token');
    }

    /** TODO(Guilherme): devolver o input hidden chamado _csrf. */
    public static function campo(): string
    {
        throw new RuntimeException('Não implementado: Csrf::campo');
    }

    /** TODO(Guilherme): comparar com hash_equals. */
    public static function valido(?string $token): bool
    {
        throw new RuntimeException('Não implementado: Csrf::valido');
    }
}
