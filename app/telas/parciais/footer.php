<?php
/**
 * Parcial: rodapé do site.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Estrutura migrada de js/components/footer.js para HTML direto.
 * Contato e redes do ateliê vêm de bootstrap.php. Sem cidade ou região.
 */
?>
<footer class="site-footer" id="contato">
  <div class="container">
    <div class="footer__grid">
      <div class="footer__brand">
        <a class="brand" href="/">
          <span class="brand__mark" aria-hidden="true">D</span>
          <span class="brand__text"><?= e(PLATAFORMA) ?><small><?= e(ATELIE_NOME) ?></small></span>
        </a>
        <p>Peças bordadas sob encomenda. Cada detalhe é pensado para durar e contar uma história.</p>
      </div>

      <div>
        <h2 class="footer__title">Navegação</h2>
        <ul class="footer__list" role="list">
          <li><a href="/">Início</a></li>
          <li><a href="/hub">Hub</a></li>
          <li><a href="/catalogo">Catálogo</a></li>
          <li><a href="/montador">Montar bordado</a></li>
        </ul>
      </div>

      <div>
        <h2 class="footer__title">Atendimento</h2>
        <ul class="footer__list" role="list">
          <li><a href="/catalogo">Como encomendar</a></li>
          <li><a href="<?= e(link_whatsapp()) ?>" target="_blank" rel="noopener noreferrer">Falar no WhatsApp</a></li>
          <li><a href="mailto:<?= e(ATELIE_EMAIL) ?>">E-mail do ateliê</a></li>
        </ul>
      </div>

      <div>
        <h2 class="footer__title">Contato</h2>
        <ul class="footer__list" role="list">
          <li><a href="<?= e(link_whatsapp()) ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
          <li><a href="mailto:<?= e(ATELIE_EMAIL) ?>"><?= e(ATELIE_EMAIL) ?></a></li>
          <li><a href="<?= e(ATELIE_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer">Instagram</a></li>
        </ul>
      </div>
    </div>

    <div class="footer__bottom">
      <p>&copy; <?= date('Y') ?> <?= e(PLATAFORMA) ?>, por <?= e(ATELIE_NOME) ?>. Todos os direitos reservados.</p>
      <p>Bordado sob encomenda, feito com afeto.</p>
    </div>
  </div>
</footer>
