<?php
/**
 * Tela: layout público
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Recebe: $titulo (título completo da página), $conteudo (HTML da tela).
 *
 * Monta o head (meta, fontes, main.css), o cabeçalho, as mensagens flash,
 * o conteúdo e o rodapé, e carrega main.js como módulo.
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($titulo) ?></title>
  <meta name="description" content="Desenrola: plataforma de bordados sob encomenda. Escolha no mostruário ou monte o seu bordado e acompanhe no Hub da comunidade." />
  <meta name="theme-color" content="#691818" />
  <meta name="csrf" content="<?= e(Csrf::token()) ?>" />

  <link rel="icon" href="/assets/img/logo/favicon.svg" type="image/svg+xml" />

  <script>document.documentElement.classList.add('js');</script>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="/assets/css/main.css" />
</head>
<body>
  <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>

  <?php Visao::parcial('header'); ?>

  <main id="conteudo">
    <?php Visao::parcial('mensagens'); ?>
    <?= $conteudo ?>
  </main>

  <?php Visao::parcial('footer'); ?>

  <script type="module" src="/assets/js/main.js"></script>
</body>
</html>
