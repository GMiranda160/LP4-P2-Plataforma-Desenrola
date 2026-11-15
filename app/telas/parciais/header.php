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
        <a class="btn btn--primary btn--sm" href="/login">Entrar</a>
      <?php else: ?>
        <?php Visao::parcial('menu_usuario', ['naGerencia' => false]); ?>
      <?php endif; ?>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Abrir menu">
        <span class="nav-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
      </button>
    </div>
  </div>
</header>

<?php Visao::guia('home'); ?>
