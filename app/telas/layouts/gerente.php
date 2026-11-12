<?php
/**
 * Tela: layout da gerente
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Recebe: $titulo, $conteudo
 *
 * Moldura do painel: mesma base do layout público, com um cabeçalho próprio,
 * menu lateral .admin-nav e a área de conteúdo. Sem as coisas do site aberto
 * (topbar, navegação pública, rodapé): é uma área interna.
 */

// Rota atual, só para marcar o item ativo no menu.
$caminho = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$ativo = static function (string $rota) use ($caminho): string {
    $combina = $rota === '/gerente'
        ? $caminho === '/gerente'
        : ($caminho === $rota || str_starts_with($caminho, $rota . '/'));

    return $combina ? ' aria-current="page"' : '';
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($titulo) ?></title>
  <meta name="robots" content="noindex" />
  <meta name="csrf" content="<?= e(Csrf::token()) ?>" />
  <meta name="theme-color" content="#691818" />

  <link rel="icon" href="/assets/img/logo/favicon.svg" type="image/svg+xml" />

  <script>document.documentElement.classList.add('js');</script>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="/assets/css/main.css" />
</head>
<body class="admin">
  <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>

  <header class="admin-topbar">
    <div class="container admin-topbar__inner">
      <a class="brand" href="/gerente">
        <span class="brand__mark" aria-hidden="true">D</span>
        <span class="brand__text">Desenrola<small>Painel da gerente</small></span>
      </a>
      <div class="admin-topbar__actions">
        <a class="btn btn--ghost btn--sm" href="/">Ver site</a>
        <form class="admin-topbar__sair" method="post" action="/sair">
          <?= Csrf::campo() ?>
          <button class="btn btn--ghost btn--sm" type="submit">Sair</button>
        </form>
      </div>
    </div>
  </header>

  <div class="container admin-layout">
    <nav class="admin-nav" aria-label="Administração">
      <a href="/gerente"<?= $ativo('/gerente') ?>>Painel</a>
      <a href="/gerente/usuarios"<?= $ativo('/gerente/usuarios') ?>>Usuários</a>
      <a href="/gerente/insumos"<?= $ativo('/gerente/insumos') ?>>Insumos</a>
      <a href="/gerente/clientes"<?= $ativo('/gerente/clientes') ?>>Clientes</a>
      <a href="/gerente/matrizes"<?= $ativo('/gerente/matrizes') ?>>Matrizes</a>
      <a href="/gerente/produtos"<?= $ativo('/gerente/produtos') ?>>Produtos</a>
      <a href="/hub"<?= $ativo('/hub') ?>>Hub</a>
    </nav>

    <main id="conteudo">
      <?php Visao::parcial('mensagens'); ?>
      <?= $conteudo ?>
    </main>
  </div>

  <script type="module" src="/assets/js/main.js"></script>
</body>
</html>
