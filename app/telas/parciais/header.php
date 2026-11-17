<?php
/**
 * Parcial: cabeçalho do site.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Estrutura migrada de js/components/header.js para HTML direto.
 * Mostra Início, Hub, Catálogo e Montar bordado. Sem login: botão Entrar.
 * Com login: botão de usuário com a inicial do nome (parcial menu_usuario.php),
 * que abre o painel com Meus bordados, Painel (gerente) e Sair por POST em /sair.
 * Sem cidade ou região; usa os contatos e a marca de bootstrap.php.
 * Marca com aria-current="page" o item do menu que corresponde à rota atual.
 * O botão "?" do guia de auth fica ao lado de Entrar ou do menu do usuário;
 * é absoluto (.guia-btn--auth), fora do fluxo, para não entrar no cálculo
 * de alinhamento da navbar. Ao lado fica o "?" do guia da home, junto à marca.
 * O botão "?" do guia do catálogo não fica na navbar: fica na própria tela,
 * ao lado do título (app/telas/catalogo/lista.php).
 */

$usuario = Auth::usuario();

// Rota atual, só para marcar o item ativo do menu.
$caminho = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

/**
 * Devolve o atributo aria-current quando a rota atual é uma das informadas.
 * Casa o caminho exato e também os que começam pela rota, para as subpáginas
 * continuarem com o item do menu marcado.
 */
$ativo = static function (string ...$rotas) use ($caminho): string {
    foreach ($rotas as $rota) {
        if ($caminho === $rota || str_starts_with($caminho, $rota . '/')) {
            return ' aria-current="page"';
        }
    }

    return '';
};
?>
<div class="topbar">
  <div class="container topbar__inner">
    <p>A plataforma de bordados sob encomenda</p>
    <a href="<?= e(link_whatsapp()) ?>" target="_blank" rel="noopener noreferrer">
      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
      Fale com o ateliê
    </a>
  </div>
</div>

<header class="site-header">
  <div class="container site-header__inner">
    <div class="brand-wrap">
      <a class="brand" href="/">
        <span class="brand__mark" aria-hidden="true">D</span>
        <span class="brand__text"><?= e(PLATAFORMA) ?><small><?= e(ATELIE_NOME) ?></small></span>
      </a>
      <button class="guia-btn" type="button" data-guia-abrir="guia-home" aria-label="Como a home é montada" aria-haspopup="dialog">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
      </button>
    </div>

    <nav class="nav" id="primary-nav" aria-label="Navegação principal">
      <ul class="nav__list" role="list">
        <li><a href="/"<?= $ativo('/') ?>>Início</a></li>
        <li><a href="/hub"<?= $ativo('/hub') ?>>Hub</a></li>
        <li><a href="/catalogo"<?= $ativo('/catalogo', '/produto') ?>>Catálogo</a></li>
        <li><a href="/montador"<?= $ativo('/montador') ?>>Montar bordado</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <?php if ($usuario === null): ?>
        <a class="btn btn--primary btn--sm header-actions__entrar" href="/login" aria-label="Entrar">
          <svg class="header-actions__entrar-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
          <span class="header-actions__entrar-texto">Entrar</span>
        </a>
      <?php else: ?>
        <?php Visao::parcial('menu_usuario', ['naGerencia' => false]); ?>
      <?php endif; ?>
      <button class="guia-btn guia-btn--auth" type="button" data-guia-abrir="guia-auth" aria-label="Como o acesso funciona" aria-haspopup="dialog">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
      </button>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Abrir menu">
        <span class="nav-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
      </button>
    </div>
  </div>
</header>

<!-- Barra de navegação fixa no rodapé, só no mobile (≤ 1100px), no estilo de app. -->
<nav class="nav-bottom" aria-label="Navegação principal">
  <a class="nav-bottom__item" href="/"<?= $ativo('/') ?>>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 11 12 4l8 7"/><path d="M6.5 9.5V20h11V9.5"/></svg>
    <span>Início</span>
  </a>
  <a class="nav-bottom__item" href="/hub"<?= $ativo('/hub') ?>>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9.4" cy="8.6" r="3.1"/><path d="M3.8 19.6c.7-3.8 2.8-5.7 5.6-5.7s4.9 1.9 5.6 5.7"/><circle cx="16.6" cy="9.6" r="2.4"/><path d="M14.8 14.2c2.6.3 4.2 2 4.6 5.3"/></svg>
    <span>Hub</span>
  </a>
  <a class="nav-bottom__item" href="/catalogo"<?= $ativo('/catalogo', '/produto') ?>>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/></svg>
    <span>Catálogo</span>
  </a>
  <a class="nav-bottom__item" href="/montador" aria-label="Montar bordado"<?= $ativo('/montador') ?>>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20c5.5 0 9.5-4 9.5-9.5"/><path d="M13.5 10.5 20 4"/><circle cx="20" cy="4" r="1.5"/></svg>
    <span aria-hidden="true">Montar</span>
  </a>
</nav>

<?php Visao::guia('home'); ?>
<?php Visao::guia('auth'); ?>
<?php Visao::guia('catalogo'); ?>
<?php Visao::guia('montador'); ?>
<?php Visao::guia('hub'); ?>
