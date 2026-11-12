<?php
/**
 * Tela: lista de insumos
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/insumos
 * Recebe: $titulo, $busca, $insumos
 * Responsabilidade: listar insumos com busca por nome, situação de estoque e ações de editar/excluir.
 */

/** Quantidade em pt-BR, sem zeros à direita ("10", "2,5", "1,75"). */
$quantidade = static fn ($valor): string => rtrim(rtrim(number_format((float) $valor, 2, ',', '.'), '0'), ',');
?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/gerente/insumos/form">Novo insumo</a>
</div>

<form method="get" class="form">
  <label class="visually-hidden" for="busca">Buscar</label>
  <input class="text-field" id="busca" name="busca" value="<?= e($busca) ?>" placeholder="Buscar por nome">
</form>

<?php if (!$insumos): ?>
  <p class="empty-state">Nenhum insumo encontrado.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Nome</th>
          <th>Quantidade</th>
          <th>Custo</th>
          <th>Situação</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($insumos as $i): ?>
          <tr>
            <td><?= e($i['tipo']) ?></td>
            <td><?= e($i['nome']) ?></td>
            <td><?= e($quantidade($i['quantidade'])) ?> <?= e($i['unidade']) ?></td>
            <td><?= moeda($i['custo_unitario']) ?></td>
            <td>
              <?php if ((float) $i['quantidade'] <= (float) $i['estoque_minimo']): ?>
                <span class="badge badge--alerta">Estoque baixo</span>
              <?php else: ?>
                <span class="badge badge--ok">Ok</span>
              <?php endif; ?>
            </td>
            <td class="table__actions">
              <a class="btn btn--ghost btn--sm" href="/gerente/insumos/form?id=<?= (int) $i['id'] ?>">Editar</a>
              <form method="post" action="/gerente/insumos/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este insumo?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $i['id'] ?>">
                <button class="btn btn--ghost btn--sm" type="submit">Excluir</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>