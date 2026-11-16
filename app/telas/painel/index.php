<?php
/**
 * Tela: painel da gerente
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Rota: GET /gerente
 * Recebe: $titulo, $totais
 * Campos de $totais: rotulo, total (int, ou null quando a contagem ainda não está pronta), link
 * Responsabilidade: cartões com os totais de insumos, clientes, produtos, matrizes, usuários,
 * posts, curtidas e comentários, cada um levando para a sua área.
 * Veja INSTRUCOES_GUILHERME.md, seção 10.
 */
?>
<div class="page-head">
  <div class="page-head__lado">
    <h1><?= e($titulo) ?></h1>
    <button class="guia-btn" type="button" data-guia-abrir="guia-painel" aria-label="Como o painel funciona" aria-haspopup="dialog">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
    </button>
  </div>
</div>

<ul class="painel-cards">
  <?php foreach ($totais as $t): ?>
    <li>
      <a class="painel-card" href="<?= e($t['link']) ?>">
        <?php if ($t['total'] === null): ?>
          <span class="painel-card__total painel-card__total--breve">Em breve</span>
        <?php else: ?>
          <span class="painel-card__total"><?= e(number_format($t['total'], 0, ',', '.')) ?></span>
        <?php endif; ?>
        <span class="painel-card__rotulo"><?= e($t['rotulo']) ?></span>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
