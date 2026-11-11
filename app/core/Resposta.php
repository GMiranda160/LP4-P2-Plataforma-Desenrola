<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: encerrar a requisição com redirecionamento ou JSON.
 * Nenhum controlador encerra a execução por si mesmo; sempre via Resposta.
 */
class Resposta
{
    /**
     * Envia Location e encerra. Use 302 logo após um POST bem-sucedido,
     * para implementar o padrão Post/Redirect/Get (F5 não reenvia o formulário).
     */
    public static function redirecionar(string $caminho): never
    {
        header('Location: ' . $caminho, true, 302);
        exit;
    }

    /** Envia JSON com o status HTTP informado e encerra a requisição. */
    public static function json(array $dados, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $dados,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );

        exit;
    }
}
