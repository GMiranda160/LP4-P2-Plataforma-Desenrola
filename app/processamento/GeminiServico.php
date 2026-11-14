<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: chamar a API do Gemini por cURL.
 * A chave fica só no servidor, lida de GEMINI_API_KEY (nunca no código).
 * Modelos: GEMINI_MODEL (padrão gemini-flash-latest) com contingência em
 * GEMINI_FALLBACK_MODEL (padrão gemini-flash-lite-latest).
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */
class GeminiServico
{
    private const URL_BASE = 'https://generativelanguage.googleapis.com/v1beta/models/';
    private const MODELO_PADRAO = 'gemini-flash-latest';
    private const MODELO_FALLBACK = 'gemini-flash-lite-latest';
    private const TIMEOUT = 25;

    /**
     * Gera o texto para o prompt. Tenta o modelo principal; se ele falhar por
     * qualquer motivo (404, 429, 5xx, resposta inválida), tenta o fallback uma
     * vez. Se os dois falharem, lança RuntimeException e o chamador usa o texto reserva.
     */
    public function gerar(string $prompt): string
    {
        $chave = (string) getenv('GEMINI_API_KEY');

        if ($chave === '') {
            throw new RuntimeException('Gemini não configurado: defina GEMINI_API_KEY.');
        }

        $principal = (string) getenv('GEMINI_MODEL') ?: self::MODELO_PADRAO;
        $fallback = (string) getenv('GEMINI_FALLBACK_MODEL') ?: self::MODELO_FALLBACK;

        try {
            return $this->chamar($principal, $prompt, $chave);
        } catch (RuntimeException $erroPrincipal) {
            if ($principal === $fallback) {
                throw $erroPrincipal;
            }

            error_log('Gemini: modelo ' . $principal . ' falhou (' . $erroPrincipal->getMessage() . '); tentando ' . $fallback);

            return $this->chamar($fallback, $prompt, $chave);
        }
    }

    /** Uma chamada generateContent. Lança RuntimeException se a resposta não vier no formato esperado. */
    private function chamar(string $modelo, string $prompt, string $chave): string
    {
        $url = self::URL_BASE . rawurlencode($modelo) . ':generateContent';

        $corpo = json_encode([
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => ['temperature' => 0.4],
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $curl = curl_init($url);

        if ($curl === false) {
            throw new RuntimeException('Gemini indisponível: falha ao iniciar o cURL.');
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $chave,
            ],
            CURLOPT_POSTFIELDS => $corpo,
        ]);

        // Em máquinas sem cacert.pem do sistema (Windows local), usa o do projeto.
        // No Railway não existe, e o Linux traz os certificados na imagem.
        $cacert = RAIZ . '/cacert.pem';

        if (is_file($cacert)) {
            curl_setopt($curl, CURLOPT_CAINFO, $cacert);
        }

        $resposta = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $erroCurl = curl_errno($curl) !== 0 ? (string) curl_error($curl) : null;

        if ($resposta === false || $status !== 200) {
            $detalhe = $erroCurl !== null ? $erroCurl : 'HTTP ' . $status;
            throw new RuntimeException('Gemini indisponível (' . $modelo . '): ' . $detalhe);
        }

        $dados = json_decode((string) $resposta, true);
        $texto = $dados['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if (!is_string($texto) || trim($texto) === '') {
            throw new RuntimeException('Gemini sem texto na resposta (' . $modelo . ').');
        }

        return trim($texto);
    }
}
