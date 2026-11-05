<?php
/**
 * Arquivo: bootstrap.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: preparar o ambiente antes de cada requisição.
 *
 * TODO(Guilherme):
 * 1. Definir a constante RAIZ.
 * 2. Carregar .env com putenv(), se existir, sem substituir variáveis já definidas.
 * 3. Registrar spl_autoload_register para core, controladores, processamento e dados.
 * 4. Carregar app/core/ajudantes.php.
 * 5. Definir constantes da plataforma e do ateliê: nome, WhatsApp, e-mail e redes. Sem cidade ou região.
 * 6. Exibir erros só com APP_ENV=local, e registrar em log nos demais casos.
 * 7. Registrar um tratador de exceções que mostre telas/erros/500.php.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
