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
  <h1><?= e($titulo) ?></h1>
</div>

<ul class="painel-cards">
  <?php foreach ($totais as $t): ?>
    <li>
      <a class="painel-card" href="<?= e($t['link']) ?>">
        <span class="painel-card__total">
          <?= $t['total'] === null ? 'Em breve' : e(number_format($t['total'], 0, ',', '.')) ?>
        </span>
        <span class="painel-card__rotulo"><?= e($t['rotulo']) ?></span>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
