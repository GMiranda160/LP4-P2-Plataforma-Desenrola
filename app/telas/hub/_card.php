<?php
/**
 * Tela: card de bordado
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Recebe: $b
 * Campos de $b: id, titulo, autor, categoria, mockup, cor_peca, pos_x, pos_y, escala, svg,
 *               curtidas, comentarios, curtiu, oculto, criado_em
 *
 * TODO(Eduardo):
 * 1. Mostrar peça, título e autor.
 * 2. Criar o botão .js-curtir com data-id e aria-pressed.
 * 3. Criar o link de comentários para /hub/bordado?id=...#comentarios.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */

?>
<article class="product-card">
  <a class="product-card__media" href="/hub/bordado?id=<?= (int) $b['id'] ?>">
    <?php Visao::parcial('peca_bordada', ['item' => $b]); ?>
  </a>

  <div class="product-card__body">
    <h2 class="product-card__name"><?= e($b['titulo']) ?></h2>
    <p>por <?= e($b['autor']) ?></p>

    <div class="hub-card__acoes">
      <button
        class="btn btn--ghost btn--sm js-curtir"
        type="button"
        data-id="<?= (int) $b['id'] ?>"
        aria-pressed="<?= $b['curtiu'] ? 'true' : 'false' ?>"
      >
        ♥ <span class="js-curtidas"><?= (int) $b['curtidas'] ?></span>
      </button>

      <a
        class="btn btn--ghost btn--sm"
        href="/hub/bordado?id=<?= (int) $b['id'] ?>#comentarios"
      >
        Comentários (<?= (int) $b['comentarios'] ?>)
      </a>
    </div>
  </div>
</article>
