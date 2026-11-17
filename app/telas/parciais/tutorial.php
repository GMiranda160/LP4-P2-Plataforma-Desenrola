<?php
/**
 * Parcial: tutorial com IA
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Espera $bordado: id, titulo, tutorial_ia (texto ou null).
 *
 * Uso, na tela do detalhe do bordado:
 *   Visao::parcial('tutorial', ['bordado' => $bordado]);
 * Se o tutorial já existir no banco, ele aparece direto; senão, fica o botão.
 * O POST vai para /bordado/tutorial (rota 'login'); o CSRF vai no cabeçalho.
 */
$tutorialSalvo = is_array($bordado ?? null) ? ($bordado['tutorial_ia'] ?? null) : null;
$temTutorial = is_string($tutorialSalvo) && trim($tutorialSalvo) !== '';
?>
<section class="tutorial" data-tutorial data-bordado-id="<?= (int) ($bordado['id'] ?? 0) ?>">
  <h3 class="tutorial__titulo">Tutorial para bordar</h3>

  <?php if ($temTutorial): ?>
    <p class="field-hint">Texto gerado por IA, revise antes de bordar.
      <?php if (Auth::id() !== null): ?>
        <button type="button" class="link-button" data-tutorial-regenerar>Regenerar</button>
      <?php endif; ?>
    </p>
    <div class="tutorial__texto" data-tutorial-texto aria-live="polite"><?= e($tutorialSalvo) ?></div>
    <p class="tutorial__erro form__error" data-tutorial-erro role="alert" hidden></p>
  <?php else: ?>
    <p class="field-hint">A IA monta um passo a passo simples, a partir deste bordado.</p>
    <button type="button" class="btn btn--ghost" data-tutorial-gerar>Gerar tutorial com IA</button>
    <p class="field-hint" data-tutorial-nota hidden>Texto gerado por IA, revise antes de bordar.</p>
    <div class="tutorial__texto" data-tutorial-texto aria-live="polite" hidden></div>
    <p class="tutorial__erro form__error" data-tutorial-erro role="alert" hidden></p>
  <?php endif; ?>
</section>
