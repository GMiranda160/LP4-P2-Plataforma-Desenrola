<?php

declare(strict_types=1);

/**
 * Arquivo: bootstrap.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: preparar o ambiente antes de cada requisição.
 *
 * Ordem do que acontece aqui:
 * 1. Define RAIZ.
 * 2. Carrega o .env, sem sobrescrever variáveis já definidas no ambiente.
 * 3. Registra o autoloader de core, controladores, processamento e dados.
 * 4. Carrega as funções globais de app/core/ajudantes.php.
 * 5. Define as constantes da plataforma e do ateliê (sem cidade ou região).
 * 6. Configura a exibição de erros conforme APP_ENV.
 * 7. Registra o tratador de exceções que mostra a tela de erro 500.
 */

// 1. Raiz do projeto, usada para montar todos os caminhos.
define('RAIZ', dirname(__DIR__, 2));

// 2. Carrega o .env apenas se ele existir. No Railway não existe .env:
// as variáveis vêm do painel do serviço e têm prioridade sobre o arquivo.
$arquivoEnv = RAIZ . '/.env';

if (is_file($arquivoEnv)) {
    $linhas = file($arquivoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) {
        // Remove BOM e espaços, para funcionar com .env salvo no Windows.
        $linha = trim((string) preg_replace('/^\xEF\xBB\xBF/', '', $linha));

        // Pula linhas vazias e comentários.
        if ($linha === '' || str_starts_with($linha, '#')) {
            continue;
        }

        // Divide no primeiro "=", chaves sem "=" são ignoradas.
        $posicao = strpos($linha, '=');

        if ($posicao === false) {
            continue;
        }

        $chave = trim(substr($linha, 0, $posicao));
        $valor = trim(substr($linha, $posicao + 1));

        // Remove aspas externas, se houver.
        if (strlen($valor) >= 2) {
            $primeira = $valor[0];
            $ultima = $valor[strlen($valor) - 1];

            if (($primeira === '"' && $ultima === '"') || ($primeira === "'" && $ultima === "'")) {
                $valor = substr($valor, 1, -1);
            }
        }

        // Não sobrescreve variáveis já definidas no ambiente.
        if ($chave === '' || getenv($chave) !== false) {
            continue;
        }

        putenv($chave . '=' . $valor);
        $_ENV[$chave] = $valor;
    }
}

// 3. Autoloader: procura a classe em cada pasta, na ordem definida.
// As classes do projeto não usam namespace, o nome do arquivo é a classe.
spl_autoload_register(function (string $classe): void {
    $pastas = ['core', 'controladores', 'processamento', 'dados'];

    foreach ($pastas as $pasta) {
        $arquivo = RAIZ . '/app/' . $pasta . '/' . $classe . '.php';

        if (is_file($arquivo)) {
            require $arquivo;
            return;
        }
    }
});

// 4. Funções globais usadas nas telas (e(), moeda(), link_whatsapp() e outras).
require RAIZ . '/app/core/ajudantes.php';

// 5. Constantes da plataforma e do ateliê.
// TODO(Guilherme): trocar os contatos provisórios pelos reais do ateliê,
// quando existirem. Não usar cidade nem região em nenhum texto do site.
// TODO(Guilherme): no futuro, estes dados podem vir de uma tabela de
// configuração, para a artesã editar pelo painel. Por ora ficam fixos aqui.
define('PLATAFORMA', 'Desenrola');
define('ATELIE_NOME', 'Cris Bordados e Bolsas');
define('ATELIE_WHATSAPP', '5511999999999'); // provisório
define('ATELIE_EMAIL', 'contato@crisbordados.com.br'); // provisório
define('ATELIE_INSTAGRAM', 'https://www.instagram.com/'); // provisório
define('WHATSAPP_MENSAGEM', 'Olá! Vim pela plataforma Desenrola e gostaria de fazer um orçamento.');

// 6. Erros: exibidos só em APP_ENV=local. Em produção, vão para o log.
$ambiente = getenv('APP_ENV') ?: 'production';

if ($ambiente === 'local') {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

error_reporting(E_ALL);

// 7. Tratador de exceções: loga o erro e mostra a tela de erro 500.
// Se a própria tela falhar, responde um texto simples para não ficar em branco.
set_exception_handler(function (Throwable $erro): void {
    error_log(sprintf(
        '[%s] %s em %s:%d',
        get_class($erro),
        $erro->getMessage(),
        $erro->getFile(),
        $erro->getLine()
    ));

    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
    }

    $tela = RAIZ . '/app/telas/erros/500.php';

    if (is_file($tela)) {
        try {
            require $tela;
            return;
        } catch (Throwable) {
            // Cai no texto simples abaixo.
        }
    }

    echo '<h1>Algo deu errado</h1><p>Tente novamente em instantes.</p>';
});
