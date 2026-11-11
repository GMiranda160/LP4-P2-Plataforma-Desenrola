<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: centralizar o uso de $_SESSION.
 *
 * Estratégia de armazenamento, decidida em iniciar():
 * - Banco definido (DB_HOST, DB_USER e DB_PASS no ambiente ou no .env):
 *   sessão na tabela sessoes do Supabase. Obrigatório em produção.
 * - Sem banco, fora de produção: sessão em arquivo local, para o site
 *   rodar no seu computador antes de o .env existir.
 * - Sem banco em produção: exceção, o site sobe vazio de sessão é pior
 *   que um erro 500 explícito nos logs do Railway.
 */
class Sessao
{
    /** Inicia a sessão com o cookie configurado e o handler escolhido. */
    public static function iniciar(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $bancoConfigurado = getenv('DB_HOST') && getenv('DB_USER') && getenv('DB_PASS') !== false;

        if ($bancoConfigurado) {
            session_set_save_handler(new SessaoBanco(), true);
        } elseif ((getenv('APP_ENV') ?: 'production') === 'local') {
            // Sessão em arquivo, só para desenvolvimento local sem .env do banco.
            error_log('Sessao: banco não configurado, usando sessão em arquivo local (APP_ENV=local).');
        } else {
            throw new RuntimeException('Sessão requer o banco: defina DB_HOST, DB_USER e DB_PASS em produção.');
        }

        // Secure só quando a conexão é HTTPS (o Railway fica atrás de proxy
        // que envia X-Forwarded-Proto). HttpOnly impede leitura por JS e
        // SameSite=Lax protege contra envio em navegação de terceiros.
        $https = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
            || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    /** Grava um valor na sessão. */
    public static function definir(string $chave, mixed $valor): void
    {
        $_SESSION[$chave] = $valor;
    }

    /** Lê um valor da sessão, ou o padrão se não existir. */
    public static function obter(string $chave, mixed $padrao = null): mixed
    {
        return $_SESSION[$chave] ?? $padrao;
    }

    /** Apaga um valor da sessão. */
    public static function remover(string $chave): void
    {
        unset($_SESSION[$chave]);
    }

    /**
     * Guarda uma mensagem de sucesso ou erro para a próxima tela.
     * Tipos usados: sucesso, erro e aviso.
     */
    public static function flash(string $tipo, string $mensagem): void
    {
        $_SESSION['flash'][] = ['tipo' => $tipo, 'mensagem' => $mensagem];
    }

    /** Lê e apaga as mensagens guardadas. Chamada pela tela de mensagens. */
    public static function tirarFlash(): array
    {
        $mensagens = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $mensagens;
    }

    /**
     * Guarda o formulário digitado para repreencher em caso de erro.
     * Nunca guarda senha nem resposta de segurança.
     */
    public static function guardarVelhos(array $dados): void
    {
        $filtrados = array_filter(
            $dados,
            fn (string $chave) => !preg_match('/senha|resposta/i', $chave),
            ARRAY_FILTER_USE_KEY
        );

        $_SESSION['velhos'] = $filtrados;
    }

    /** Devolve e apaga o formulário guardado. */
    public static function tirarVelhos(): array
    {
        $velhos = $_SESSION['velhos'] ?? [];
        unset($_SESSION['velhos']);

        return $velhos;
    }
}
