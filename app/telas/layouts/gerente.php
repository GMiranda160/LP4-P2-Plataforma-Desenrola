<?php
/**
 * Tela: layout da gerente
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Recebe: $titulo, $conteudo
 *
 * Moldura do painel: mesma base do layout público, com um cabeçalho próprio
 * (brand e menu do usuário, com a inicial e o botão para sair), menu lateral
 * .admin-nav e a área de conteúdo. Sem as coisas do site aberto (topbar,
 * navegação pública, rodapé): é uma área interna.
 *
 * Também exibe, dentro de <main>, um botão "voltar" contextual (seta para a esquerda):
 * a raiz (/gerente) não ganha o botão; listas vão para /gerente; subtelas vão para a seção.
 *
 * O Hub não é uma tela da área: o link fica num elemento separado, abaixo do menu,
 * para indicar que leva ao Hub público (onde a moderação acontece).
 */

// Rota atual, só para marcar o item ativo no menu.
$caminho = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$ativo = static function (string $rota) use ($caminho): string {
    $combina = $rota === '/gerente'
        ? $caminho === '/gerente'
        : ($caminho === $rota || str_starts_with($caminho, $rota . '/'));

    return $combina ? ' aria-current="page"' : '';
};

// Botão "voltar" contextual, para navegar entre as telas da área.
// O Painel não ganha botão próprio: o "Voltar para o site" da topbar já cobre essa volta.
$secoes = [
    'insumos' => 'Insumos',
    'clientes' => 'Clientes',
    'pecas' => 'Peças',
    'matrizes' => 'Matrizes',
    'produtos' => 'Produtos',
    'usuarios' => 'Usuários',
];
$partes = array_values(array_filter(explode('/', trim($caminho, '/')), 'strlen'));
$voltar = null;
if (($partes[0] ?? '') === 'gerente' && $caminho !== '/gerente') {
    $secao = $partes[1] ?? '';
    $temSubtela = isset($partes[2]) && $partes[2] !== '';

    if (isset($secoes[$secao])) {
        $voltar = $temSubtela
            ? ['/gerente/' . $secao, 'Voltar para ' . $secoes[$secao]]
            : ['/gerente', 'Voltar para o Painel'];
    } else {
        $voltar = ['/gerente', 'Voltar para o Painel'];
    }
}
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

  <link rel="stylesheet" href="/assets/css/main.css?v=<?= (int) (@filemtime(RAIZ . '/public/assets/css/main.css') ?: 1) ?>" />
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
        <?php Visao::parcial('menu_usuario', ['naGerencia' => true]); ?>
      </div>
    </div>
  </header>

  <div class="container admin-layout">
    <div class="admin-rail">
      <nav class="admin-nav" aria-label="Administração">
        <a href="/gerente"<?= $ativo('/gerente') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          Painel
        </a>
        <a href="/gerente/usuarios"<?= $ativo('/gerente/usuarios') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Usuários
        </a>
        <a href="/gerente/insumos"<?= $ativo('/gerente/insumos') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>
          Insumos
        </a>
        <a href="/gerente/clientes"<?= $ativo('/gerente/clientes') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          Clientes
        </a>
        <a href="/gerente/pecas"<?= $ativo('/gerente/pecas') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/></svg>
          Peças
        </a>
        <a href="/gerente/matrizes"<?= $ativo('/gerente/matrizes') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/></svg>
          Matrizes
        </a>
        <a href="/gerente/produtos"<?= $ativo('/gerente/produtos') ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          Produtos
        </a>
      </nav>

      <a class="admin-hub" href="/hub">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        Ir para o Hub
      </a>
    </div>

    <main id="conteudo">
      <?php if ($voltar): ?>
        <a class="btn btn--ghost btn--sm admin-voltar" href="<?= e($voltar[0]) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"></path><path d="M12 19l-7-7 7-7"></path></svg>
          <?= e($voltar[1]) ?>
        </a>
      <?php endif; ?>

      <?php Visao::parcial('mensagens'); ?>
      <?= $conteudo ?>
    </main>
  </div>

  <?php Visao::guia('painel'); ?>

  <script type="module" src="/assets/js/main.js?v=<?= (int) (@filemtime(RAIZ . '/public/assets/js/main.js') ?: 1) ?>"></script>
</body>
</html>
