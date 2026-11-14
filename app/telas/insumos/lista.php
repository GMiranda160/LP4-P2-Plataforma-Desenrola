<?php
/**
 * Tela: lista de insumos
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/insumos
 * Recebe: $titulo, $busca, $insumos
 * Campos de $insumos: id, tipo, nome, cor_hex, quantidade, unidade, custo_unitario,
 *                     fornecedor, estoque_minimo
 * Responsabilidade: listar insumos com busca por nome, situação de estoque e ações de editar/excluir.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
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
          <th class="table__acao-head">Ações</th>
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
            <td class="table__actions table__actions--centro">
              <a class="btn btn--ghost btn--icon" href="/gerente/insumos/form?id=<?= (int) $i['id'] ?>" aria-label="Editar insumo" title="Editar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.85 0 0 1 4 4L8 20l-5 1 1-5L17 3Z"/></svg>
              </a>
              <form method="post" action="/gerente/insumos/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este insumo?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $i['id'] ?>">
                <button class="btn btn--ghost btn--icon" type="submit" aria-label="Excluir insumo" title="Excluir">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>