<?php
/**
 * Tela: detalhe do bordado
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /hub/bordado?id=
 * Recebe: $titulo, $bordado, $comentarios, $podeExcluir
 * $bordado tem os campos do card, mais descricao, autor_id, largura, altura,
 * total_pontos, paleta e tipo.
 *
 * TODO(Eduardo):
 * 1. Mostrar peça grande, dados, amostras de paleta e curtir.
 * 2. Se $podeExcluir, mostrar Excluir por POST em /bordado/excluir.
 * 3. Para gerente, mostrar Ocultar por POST em /gerente/hub/ocultar e o selo Oculto.
 * 4. Incluir Visao::parcial('tutorial') e depois _comentarios.php.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */
?>

<?php
$paleta = is_array($bordado['paleta'] ?? null)
    ? $bordado['paleta']
    : [];
?>

<section class="hub-detalhe">
  <h1><?= e($bordado['titulo']) ?></h1>

  <?php if ($bordado['oculto']): ?>
    <span class="badge badge--alerta">Oculto</span>
  <?php endif; ?>

  <div class="hub-detalhe__peca">
    <?php Visao::parcial('peca_bordada', ['item' => $bordado]); ?>
  </div>

  <div class="hub-detalhe__dados">
    <p>por <?= e($bordado['autor']) ?></p>
    <p>Categoria: <?= e($bordado['categoria']) ?></p>

    <?php if (!empty($bordado['descricao'])): ?>
      <p class="hub-detalhe__descricao"><?= e($bordado['descricao']) ?></p>
    <?php endif; ?>

    <dl>
      <dt>Tamanho da matriz</dt>
      <dd><?= (int) $bordado['largura'] ?> × <?= (int) $bordado['altura'] ?> pontos</dd>

      <dt>Total de pontos</dt>
      <dd><?= (int) $bordado['total_pontos'] ?></dd>

      <dt>Publicado em</dt>
      <dd><?= e(data_br($bordado['criado_em'])) ?></dd>
    </dl>

    <?php if ($paleta !== []): ?>
      <h2>Cores das linhas</h2>
      <ul class="hub-detalhe__paleta">
        <?php foreach ($paleta as $cor): ?>
          <?php
          if (!is_string($cor) || !preg_match('/\A#[0-9a-fA-F]{6}\z/', $cor)) {
              continue;
          }
          ?>
          <li>
            <span
              class="hub-detalhe__amostra"
              style="--hub-cor-linha: <?= e($cor) ?>"
              aria-label="Linha <?= e($cor) ?>"
            ></span>
            <?= e($cor) ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <div class="hub-detalhe__acoes">
      <button
        class="btn btn--ghost js-curtir"
        type="button"
        data-id="<?= (int) $bordado['id'] ?>"
        aria-pressed="<?= $bordado['curtiu'] ? 'true' : 'false' ?>"
      >
        ♥ <span class="js-curtidas"><?= (int) $bordado['curtidas'] ?></span>
      </button>

      <?php if ($podeExcluir): ?>
        <form method="post" action="/bordado/excluir">
          <?= Csrf::campo() ?>
          <input type="hidden" name="id" value="<?= (int) $bordado['id'] ?>">
          <button class="btn btn--ghost" type="submit">Excluir bordado</button>
        </form>
      <?php endif; ?>

      <?php if (Auth::eGerente()): ?>
        <form method="post" action="/gerente/hub/ocultar">
          <?= Csrf::campo() ?>
          <input type="hidden" name="id" value="<?= (int) $bordado['id'] ?>">
          <button class="btn btn--ghost" type="submit">
            <?= $bordado['oculto'] ? 'Reexibir' : 'Ocultar' ?>
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php Visao::parcial('tutorial', ['bordado' => $bordado]); ?>
<?php require RAIZ . '/app/telas/hub/_comentarios.php'; ?>
