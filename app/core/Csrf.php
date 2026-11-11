<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: proteção contra CSRF em todos os formulários e fetches.
 *
 * Como funciona: o token fica na sessão e é embutido em um campo oculto
 * (ou no cabeçalho X-CSRF-Token, nos fetches do JS). O roteador confere
 * o token antes de qualquer POST tocar o controlador.
 */
class Csrf
{
    private const CHAVE = 'csrf_token';

    /** Devolve o token da sessão, criando um novo na primeira vez. */
    public static function token(): string
    {
        $token = Sessao::obter(self::CHAVE);

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Sessao::definir(self::CHAVE, $token);
        }

        return $token;
    }

    /** Devolve o <input type="hidden"> para colar dentro de todo <form method="post">. */
    public static function campo(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(self::token()) . '">';
    }

    /** Confere o token enviado com o da sessão, em tempo constante. */
    public static function valido(?string $token): bool
    {
        $esperado = Sessao::obter(self::CHAVE);

        if (!is_string($esperado) || !is_string($token) || $token === '') {
            return false;
        }

        return hash_equals($esperado, $token);
    }
}
