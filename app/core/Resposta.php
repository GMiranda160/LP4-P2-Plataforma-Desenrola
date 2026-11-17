<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: encerrar a requisição com redirecionamento, JSON ou fluxo NDJSON.
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

    /**
     * Resposta em fluxo NDJSON: um JSON por linha, enviado na hora em que fica pronto.
     * $produzir recebe a função $enviar(array $linha) e escreve quantas linhas quiser.
     * Se $produzir lançar exceção, a última linha é {"tipo":"erro"} e a requisição encerra.
     */
    public static function fluxo(callable $produzir): never
    {
        // Sem buffer do PHP nem compressão: cada linha precisa sair na hora.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        ini_set('zlib.output_compression', '0');
        ini_set('implicit_flush', '1');

        http_response_code(200);
        header('Content-Type: application/x-ndjson; charset=utf-8');
        header('Cache-Control: no-cache, no-transform');
        // Pede aos proxies (Railway, nginx) que não segurem a resposta.
        header('X-Accel-Buffering: no');

        $enviar = function (array $linha): void {
            echo json_encode($linha, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE), "\n";
            flush();
        };

        try {
            $produzir($enviar);
        } catch (Throwable $erro) {
            error_log('Resposta::fluxo: ' . get_class($erro) . ': ' . $erro->getMessage());
            $enviar(['tipo' => 'erro']);
        }

        exit;
    }
}
