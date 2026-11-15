<?php
/**
 * Tela: meus bordados
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /meus-bordados
 * Recebe: $titulo, $bordados
 *
 * TODO(Eduardo):
 * 1. Reaproveitar _card.php.
 * 2. Sem bordados, mostrar um convite para /montador.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */
?>

<div class="page-head">
  <h1><?= e($titulo) ?></h1>
</div>

<?php if (empty($bordados)): ?>
  <p class="empty-state">
    Você ainda não tem bordados.
    <a href="/montador">Crie seu primeiro bordado</a>
  </p>
<?php else: ?>
  <div class="product-grid">
    <?php foreach ($bordados as $b): ?>
      <?php require RAIZ . '/app/telas/hub/_card.php'; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
