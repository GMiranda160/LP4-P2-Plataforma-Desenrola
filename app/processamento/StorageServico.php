<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: enviar, apagar e montar a URL pública de arquivos no Supabase Storage
 * (bucket público 'mostruario'). Guarda os arquivos das matrizes (SVG e grade) e as fotos
 * das peças base. O banco guarda só o caminho do objeto, nunca o arquivo.
 * Usa SUPABASE_URL e SUPABASE_SERVICE_ROLE_KEY do ambiente. Sem SQL, sem sessão e sem HTML.
 */
class StorageServico
{
    public const BUCKET = 'mostruario';
    private const URL_PADRAO = 'https://azgbhmxfkpaciqdcfzdl.supabase.co';
    private const TIMEOUT = 20;

    /** URL pública de um objeto do bucket, a partir do caminho salvo no banco (ex.: matrizes/abc.svg). */
    public static function urlPublica(string $caminho): string
    {
        return self::urlBase() . '/storage/v1/object/public/' . self::BUCKET . '/' . self::caminhoSeguro($caminho);
    }

    /**
     * Envia (ou substitui) um arquivo no bucket. Lança RuntimeException se o Storage recusar,
     * para quem chamou desfazer o que já gravou no banco.
     */
    public function enviar(string $caminho, string $conteudo, string $tipo): void
    {
        $this->pedir('POST', $caminho, $conteudo, [
            'Content-Type: ' . $tipo,
            'x-upsert: true',
            'Cache-Control: max-age=31536000',
        ]);
    }

    /** Apaga um arquivo do bucket. Não lança erro: arquivo órfão não pode derrubar a tela. */
    public function remover(string $caminho): void
    {
        try {
            $this->pedir('DELETE', $caminho, null, []);
        } catch (Throwable $erro) {
            error_log('Storage: não consegui apagar ' . $caminho . ': ' . $erro->getMessage());
        }
    }

    /** Caminho novo e único dentro de uma pasta do bucket, ex.: matrizes/3f9c...e1.svg. */
    public static function novoCaminho(string $pasta, string $extensao): string
    {
        return trim($pasta, '/') . '/' . bin2hex(random_bytes(16)) . '.' . ltrim($extensao, '.');
    }

    private function pedir(string $metodo, string $caminho, ?string $corpo, array $cabecalhos): void
    {
        $chave = (string) getenv('SUPABASE_SERVICE_ROLE_KEY');

        if ($chave === '') {
            throw new RuntimeException('Storage não configurado: defina SUPABASE_SERVICE_ROLE_KEY.');
        }

        $url = self::urlBase() . '/storage/v1/object/' . self::BUCKET . '/' . self::caminhoSeguro($caminho);
        $curl = curl_init($url);

        if ($curl === false) {
            throw new RuntimeException('Storage indisponível: falha ao iniciar o cURL.');
        }

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $metodo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_HTTPHEADER => array_merge(['Authorization: Bearer ' . $chave, 'apikey: ' . $chave], $cabecalhos),
        ]);

        if ($corpo !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $corpo);
        }

        // Mesmo cuidado do GeminiServico: no Windows local usa o cacert.pem do projeto.
        $cacert = RAIZ . '/cacert.pem';

        if (is_file($cacert)) {
            curl_setopt($curl, CURLOPT_CAINFO, $cacert);
        }

        $resposta = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $erroCurl = curl_errno($curl) !== 0 ? (string) curl_error($curl) : null;

        if ($resposta === false || $status < 200 || $status >= 300) {
            $detalhe = $erroCurl ?? ('HTTP ' . $status . ' ' . substr((string) $resposta, 0, 200));
            throw new RuntimeException('Storage recusou ' . $metodo . ' ' . $caminho . ': ' . $detalhe);
        }
    }

    private static function urlBase(): string
    {
        return rtrim((string) (getenv('SUPABASE_URL') ?: self::URL_PADRAO), '/');
    }

    /** Só letras, números, hífen, ponto e barra: o caminho nunca sai do bucket. */
    private static function caminhoSeguro(string $caminho): string
    {
        $caminho = ltrim($caminho, '/');

        if (!preg_match('#^[a-z0-9._-]+(/[a-z0-9._-]+)*$#i', $caminho) || str_contains($caminho, '..')) {
            throw new InvalidArgumentException('Caminho de arquivo inválido.');
        }

        return $caminho;
    }
}
