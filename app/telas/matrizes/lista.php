<?php
/*
 * matrizes/lista.php
 * Tela de listagem de matrizes.
 */
?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/montador?modo=matriz">Nova matriz</a>
</div>

<?php if (!$matrizes): ?>
  <p class="empty-state">Nenhuma matriz encontrada.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Miniatura</th>
          <th>Nome</th>
          <th>Categoria</th>
          <th>Tamanho</th>
          <th>Pontos</th>
          <th>Tempo</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($matrizes as $m): ?>
          <tr>
            <td>
              <img src="<?= e(svg_data_uri($m['svg'])) ?>" alt="<?= e($m['nome']) ?>">
            </td>
            <td><?= e($m['nome']) ?></td>
            <td><?= e($m['categoria']) ?></td>
            <td><?= e($m['largura']) ?> x <?= e($m['altura']) ?></td>
            <td><?= e($m['total_pontos']) ?></td>
            <td><?= e($m['tempo_medio_min']) ?> min</td>
            <td class="table__actions">
              <a class="btn btn--ghost btn--sm" href="/gerente/matrizes/form?id=<?= (int) $m['id'] ?>">Editar</a>
              <form method="post" action="/gerente/matrizes/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir esta matriz?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                <button class="btn btn--ghost btn--sm" type="submit">Excluir</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>