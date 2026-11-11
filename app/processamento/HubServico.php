<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras do Hub. Sem SQL e sem HTML.
 */
class HubServico
{
    /** Os mais curtidos do Hub, para a home. Visitante usa :usuario = 0. */
    public function maisCurtidos(int $limite = 6, int $usuario = 0): array
    {
        return (new HubDados())->maisCurtidos($limite, $usuario);
    }
}
