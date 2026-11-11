<?php
/**
 * Parcial: cabeçalho do site.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Estrutura migrada de js/components/header.js para HTML direto.
 * Mostra Início, Hub, Catálogo e Montar bordado. Sem login: botão Entrar.
 * Com login: Meus bordados, Painel (gerente) e Sair por POST em /sair.
 * Sem cidade ou região; usa os contatos e a marca de bootstrap.php.
 */

$usuario = Auth::usuario();
?>
<div class="topbar">
  <div class="container topbar__inner">
    <p>A plataforma de bordados sob encomenda</p>
    <a href="<?= e(link_whatsapp()) ?>" target="_blank" rel="noopener noreferrer">Fale com o ateliê</a>
  </div>
</div>

<header class="site-header">
  <div class="container site-header__inner">
    <a class="brand" href="/">
      <span class="brand__mark" aria-hidden="true">D</span>
      <span class="brand__text"><?= e(PLATAFORMA) ?><small><?= e(ATELIE_NOME) ?></small></span>
    </a>

    <nav class="nav" id="primary-nav" aria-label="Navegação principal">
      <ul class="nav__list" role="list">
        <li><a href="/">Início</a></li>
        <li><a href="/hub">Hub</a></li>
        <li><a href="/catalogo">Catálogo</a></li>
        <li><a href="/montador">Montar bordado</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <?php if ($usuario === null): ?>
        <a class="btn btn--primary btn--sm" href="/login">Entrar</a>
      <?php else: ?>
        <a class="btn btn--ghost btn--sm" href="/meus-bordados">Meus bordados</a>
        <?php if (Auth::eGerente()): ?>
          <a class="btn btn--ghost btn--sm" href="/gerente">Painel</a>
        <?php endif; ?>
        <form method="post" action="/sair">
          <?= Csrf::campo() ?>
          <button class="btn btn--ghost btn--sm" type="submit">Sair</button>
        </form>
      <?php endif; ?>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Abrir menu">
        <span class="nav-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
      </button>
    </div>
  </div>
</header>
