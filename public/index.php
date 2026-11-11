<?php

declare(strict_types=1);

/**
 * Arquivo: index.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: ponto único de entrada de todas as requisições.
 *
 * Fluxo de cada requisição:
 * 1. O servidor embutido do PHP devolve direto os arquivos estáticos de public/.
 * 2. O bootstrap prepara o ambiente: autoloader, .env, constantes e erros.
 * 3. A sessão inicia (no banco, via SessaoBanco, em qualquer ambiente).
 * 4. O roteador encontra a rota e chama o controlador.
 */

// No servidor embutido (rodar-local.bat), os arquivos que existem em public/
// são servidos sem passar pelo PHP. No Docker com FrankenPHP e Caddy, quem
// serve os estáticos é o Caddy antes de chegar aqui, e este bloco não é usado.
if (PHP_SAPI === 'cli-server') {
    $caminho = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

    // Bloqueia tentativa de sair de public/ com ".." na URL.
    if (is_string($caminho) && !str_contains($caminho, '..')) {
        $arquivo = __DIR__ . $caminho;

        if (is_file($arquivo)) {
            return false;
        }
    }
}

// Prepara o ambiente. A partir daqui existem: autoloader das classes de app/,
// a constante RAIZ, as constantes da plataforma (marca, WhatsApp, e-mail) e o
// tratamento de erros e exceções.
require __DIR__ . '/../app/config/bootstrap.php';

// Sessão no banco (tabela sessoes). O disco do servidor não é durável, então
// a sessão sobrevive a deploys e reinícios, tanto no Railway quanto local.
Sessao::iniciar();

// O roteador recebe a lista de rotas e despacha para o controlador certo,
// validando CSRF nos POSTs e o acesso (publico, login ou gerente).
(new Roteador(require __DIR__ . '/../app/config/rotas.php'))->despachar();
