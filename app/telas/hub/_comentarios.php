<?php
/**
 * Tela: comentários do bordado
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Recebe: $bordado, $comentarios
 * Campos de $comentarios: id, usuario_id, autor, texto, oculto, criado_em
 *
 * TODO(Eduardo):
 * 1. Criar a seção com id="comentarios".
 * 2. Com login, criar o POST para /hub/comentar, com bordado_id e texto, em textarea maxlength="280". Sem login, mostrar link para /login.
 * 3. Mostrar Excluir ao autor, por POST em /hub/comentario/excluir.
 * 4. Mostrar Ocultar ou Reexibir à gerente, por POST em /gerente/hub/comentario/ocultar.
 * 5. Sem comentários, mostrar: Seja a primeira pessoa a comentar.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */
?>

<section class="hub-comentarios" id="comentarios">
  <h2>Comentários</h2>

  <?php if (Auth::id() !== null): ?>
    <form class="form hub-comentarios__form" method="post" action="/hub/comentar">
      <?= Csrf::campo() ?>

      <input
        type="hidden"
        name="bordado_id"
        value="<?= (int) $bordado['id'] ?>"
      >

      <label class="form__field" for="texto-comentario">Seu comentário</label>
      <textarea
        class="text-field"
        id="texto-comentario"
        name="texto"
        maxlength="280"
        rows="4"
        required
      ></textarea>

      <button class="btn btn--primary" type="submit">Comentar</button>
    </form>
  <?php else: ?>
    <p><a href="/login">Entre para comentar.</a></p>
  <?php endif; ?>

  <?php if (empty($comentarios)): ?>
    <p class="empty-state">Seja a primeira pessoa a comentar.</p>
  <?php else: ?>
    <div class="hub-comentarios__lista">
      <?php foreach ($comentarios as $comentario): ?>
        <article class="hub-comentario">
          <header class="hub-comentario__cabecalho">
            <strong><?= e($comentario['autor']) ?></strong>
            <time><?= e(data_br($comentario['criado_em'])) ?></time>
          </header>

          <?php if ($comentario['oculto']): ?>
            <span class="badge badge--alerta">Oculto</span>
          <?php endif; ?>

          <p class="hub-comentario__texto"><?= e($comentario['texto']) ?></p>

          <div class="hub-comentario__acoes">
            <?php if (Auth::id() === (int) $comentario['usuario_id']): ?>
              <form method="post" action="/hub/comentario/excluir">
                <?= Csrf::campo() ?>
                <input
                  type="hidden"
                  name="id"
                  value="<?= (int) $comentario['id'] ?>"
                >
                <button class="btn btn--ghost btn--sm" type="submit">
                  Excluir
                </button>
              </form>
            <?php endif; ?>

            <?php if (Auth::eGerente()): ?>
              <form method="post" action="/gerente/hub/comentario/ocultar">
                <?= Csrf::campo() ?>
                <input
                  type="hidden"
                  name="id"
                  value="<?= (int) $comentario['id'] ?>"
                >
                <button class="btn btn--ghost btn--sm" type="submit">
                  <?= $comentario['oculto'] ? 'Reexibir' : 'Ocultar' ?>
                </button>
              </form>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
