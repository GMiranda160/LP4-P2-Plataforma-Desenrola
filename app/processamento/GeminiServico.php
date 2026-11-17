<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: chamar a API do Gemini por cURL, de uma vez (gerar) ou em fluxo (gerarEmFluxo).
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
    /** Espera no modelo principal: 25s de folga, sempre com a contingência pronta no fallback. */
    private const TIMEOUT_PRINCIPAL = 25;
    private const TIMEOUT_FALLBACK = 30;
    private const TIMEOUT_CONEXAO = 5;
    /** Teto do modo em fluxo: o texto chega aos poucos, então o limite é o total da conversa. */
    private const TIMEOUT_FLUXO = 60;

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
            return $this->chamar($principal, $prompt, $chave, self::TIMEOUT_PRINCIPAL);
        } catch (RuntimeException $erroPrincipal) {
            if ($principal === $fallback) {
                throw $erroPrincipal;
            }

            error_log('Gemini: modelo ' . $principal . ' falhou (' . $erroPrincipal->getMessage() . '); tentando ' . $fallback);

            return $this->chamar($fallback, $prompt, $chave, self::TIMEOUT_FALLBACK);
        }
    }

    /**
     * Gera o texto em fluxo (streaming): cada pedaço que o Gemini manda vai para $aoReceber
     * na hora, e o texto completo é devolvido no fim. Tenta o principal; se ele falhar antes
     * do primeiro pedaço, tenta o fallback em fluxo. Se falhar depois de já ter mandado texto,
     * lança FluxoInterrompido, para o chamador saber que a tela precisa recomeçar.
     */
    public function gerarEmFluxo(string $prompt, callable $aoReceber): string
    {
        $chave = (string) getenv('GEMINI_API_KEY');

        if ($chave === '') {
            throw new RuntimeException('Gemini não configurado: defina GEMINI_API_KEY.');
        }

        $principal = (string) getenv('GEMINI_MODEL') ?: self::MODELO_PADRAO;
        $fallback = (string) getenv('GEMINI_FALLBACK_MODEL') ?: self::MODELO_FALLBACK;

        try {
            return $this->chamarEmFluxo($principal, $prompt, $chave, $aoReceber);
        } catch (FluxoInterrompido $interrompido) {
            throw $interrompido;
        } catch (RuntimeException $erroPrincipal) {
            if ($principal === $fallback) {
                throw $erroPrincipal;
            }

            error_log('Gemini (fluxo): modelo ' . $principal . ' falhou (' . $erroPrincipal->getMessage() . '); tentando ' . $fallback);

            return $this->chamarEmFluxo($fallback, $prompt, $chave, $aoReceber);
        }
    }

    /** Corpo JSON do pedido: o prompt e a configuração de geração, iguais nos dois modos. */
    private function corpo(string $prompt): string
    {
        return json_encode([
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 1200,
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /** Abre o cURL com o POST, a chave no cabeçalho e o cacert.pem do projeto, se existir. */
    private function abrirCurl(string $url, string $prompt, string $chave): CurlHandle
    {
        $curl = curl_init($url);

        if ($curl === false) {
            throw new RuntimeException('Gemini indisponível: falha ao iniciar o cURL.');
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => self::TIMEOUT_CONEXAO,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $chave,
            ],
            CURLOPT_POSTFIELDS => $this->corpo($prompt),
        ]);

        // Em máquinas sem cacert.pem do sistema (Windows local), usa o do projeto.
        // No Railway não existe, e o Linux traz os certificados na imagem.
        $cacert = RAIZ . '/cacert.pem';

        if (is_file($cacert)) {
            curl_setopt($curl, CURLOPT_CAINFO, $cacert);
        }

        return $curl;
    }

    /**
     * Uma chamada streamGenerateContent em SSE. O cURL entrega os bytes aos poucos; cada
     * linha "data: {...}" é um JSON com o próximo pedaço do texto. Lança RuntimeException
     * se nada chegar, ou FluxoInterrompido se a conexão cair depois do primeiro pedaço.
     */
    private function chamarEmFluxo(string $modelo, string $prompt, string $chave, callable $aoReceber): string
    {
        $url = self::URL_BASE . rawurlencode($modelo) . ':streamGenerateContent?alt=sse';
        $curl = $this->abrirCurl($url, $prompt, $chave);

        $pendente = '';
        $completo = '';
        $corpoErro = '';
        $motivo = null;

        $lerLinha = function (string $linha) use (&$completo, &$motivo, $aoReceber): void {
            $linha = trim($linha);

            if (!str_starts_with($linha, 'data:')) {
                return;
            }

            $dados = json_decode(trim(substr($linha, 5)), true);

            if (!is_array($dados)) {
                return;
            }

            $candidato = $dados['candidates'][0] ?? [];
            $motivo = $candidato['finishReason'] ?? $motivo;

            foreach ($candidato['content']['parts'] ?? [] as $parte) {
                // Partes de "pensamento" de modelos com raciocínio não vão para a tela.
                if (!empty($parte['thought']) || !is_string($parte['text'] ?? null) || $parte['text'] === '') {
                    continue;
                }

                $completo .= $parte['text'];
                $aoReceber($parte['text']);
            }
        };

        curl_setopt_array($curl, [
            // Sem tempo total fixo: a resposta chega aos poucos. Cai se ficar
            // 15 s sem receber nada, ou se passar de 60 s no total.
            CURLOPT_TIMEOUT => self::TIMEOUT_FLUXO,
            CURLOPT_LOW_SPEED_LIMIT => 1,
            CURLOPT_LOW_SPEED_TIME => 15,
            CURLOPT_WRITEFUNCTION => function (CurlHandle $c, string $bytes) use (&$pendente, &$corpoErro, $lerLinha): int {
                if ((int) curl_getinfo($c, CURLINFO_RESPONSE_CODE) !== 200) {
                    $corpoErro .= $bytes;

                    return strlen($bytes);
                }

                $pendente .= $bytes;

                // Só processa linhas inteiras; o resto espera o próximo pacote.
                while (($fim = strpos($pendente, "\n")) !== false) {
                    $lerLinha(substr($pendente, 0, $fim));
                    $pendente = substr($pendente, $fim + 1);
                }

                return strlen($bytes);
            },
        ]);

        $ok = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $erroCurl = curl_errno($curl) !== 0 ? (string) curl_error($curl) : null;

        if ($pendente !== '') {
            $lerLinha($pendente);
        }

        if (is_string($motivo) && $motivo !== '' && $motivo !== 'STOP') {
            error_log('Gemini (fluxo): finishReason = ' . $motivo);
        }

        if ($ok === false || $status !== 200) {
            $detalhe = $erroCurl ?? ('HTTP ' . $status . ' ' . mb_substr(trim($corpoErro), 0, 200));
            $mensagem = 'Gemini indisponível (' . $modelo . '): ' . $detalhe;

            throw $completo === '' ? new RuntimeException($mensagem) : new FluxoInterrompido($mensagem);
        }

        if (trim($completo) === '') {
            throw new RuntimeException('Gemini sem texto na resposta (' . $modelo . ').');
        }

        return trim($completo);
    }

    /** Uma chamada generateContent, com o tempo máximo de espera em segundos. Lança RuntimeException se a resposta não vier no formato esperado. */
    private function chamar(string $modelo, string $prompt, string $chave, int $timeout): string
    {
        $url = self::URL_BASE . rawurlencode($modelo) . ':generateContent';
        $curl = $this->abrirCurl($url, $prompt, $chave);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
        ]);

        $resposta = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $erroCurl = curl_errno($curl) !== 0 ? (string) curl_error($curl) : null;

        if ($resposta === false || $status !== 200) {
            $detalhe = $erroCurl !== null ? $erroCurl : 'HTTP ' . $status;
            throw new RuntimeException('Gemini indisponível (' . $modelo . '): ' . $detalhe);
        }

        $dados = json_decode((string) $resposta, true);
        $texto = $dados['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $motivo = $dados['candidates'][0]['finishReason'] ?? null;

        if (is_string($motivo) && $motivo !== '' && $motivo !== 'STOP') {
            error_log('Gemini: finishReason = ' . $motivo);
        }

        if (!is_string($texto) || trim($texto) === '') {
            throw new RuntimeException('Gemini sem texto na resposta (' . $modelo . ').');
        }

        return trim($texto);
    }
}
