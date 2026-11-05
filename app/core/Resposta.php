<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: encerrar a requisição com redirecionamento ou JSON.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Resposta
{
    /** TODO(Guilherme): enviar o cabeçalho Location e encerrar. */
    public static function redirecionar(string $caminho): never
    {
        throw new RuntimeException('Não implementado: Resposta::redirecionar');
    }

    /** TODO(Guilherme): enviar JSON com o status informado e encerrar. */
    public static function json(array $dados, int $status = 200): never
    {
        throw new RuntimeException('Não implementado: Resposta::json');
    }
}
