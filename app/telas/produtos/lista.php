<?php
/**
 * Tela: lista de produtos
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/produtos
 * Recebe: $titulo, $produtos
 * Campos de $produtos: id, nome, categoria, preco_base, destaque, ativo
 * Responsabilidade: listar produtos com situação (ativo/inativo e destaque) e ações de editar, ativar/desativar e excluir.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/gerente/produtos/form">Novo produto</a>
</div>

<?php if (!$produtos): ?>
  <p class="empty-state">Nenhum produto encontrado.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Categoria</th>
          <th>Preço base</th>
          <th>Destaque</th>
          <th>Situação</th>
          <th class="table__acao-head">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $p): ?>
          <tr>
            <td><?= e($p['nome']) ?></td>
            <td><?= e($p['categoria']) ?></td>
            <td><?= moeda($p['preco_base']) ?></td>
            <td>
              <?php if (!empty($p['destaque'])): ?>
                <span class="badge badge--ok">Destaque</span>
              <?php else: ?>
                <span class="badge">Normal</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!empty($p['ativo'])): ?>
                <span class="badge badge--ok">Ativo</span>
              <?php else: ?>
                <span class="badge badge--alerta">Inativo</span>
              <?php endif; ?>
            </td>
            <td class="table__actions table__actions--centro">
              <a class="btn btn--ghost btn--icon" href="/gerente/produtos/form?id=<?= (int) $p['id'] ?>" aria-label="Editar produto" title="Editar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.85 0 0 1 4 4L8 20l-5 1 1-5L17 3Z"/></svg>
              </a>
              <form method="post" action="/gerente/produtos/ativar">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <?php $ativo = !empty($p['ativo']); ?>
                <button class="btn btn--ghost btn--icon" type="submit"
                        aria-label="<?= $ativo ? 'Ocultar do catálogo' : 'Mostrar no catálogo' ?>"
                        title="<?= $ativo ? 'Ocultar do catálogo' : 'Mostrar no catálogo' ?>">
                  <?= $ativo ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.9 4.24A9.1 9.1 0 0 1 12 4c6.5 0 10 7 10 7a17.6 17.6 0 0 1-2.16 3.19"/><path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="m2 2 20 20"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>' : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>' ?>
                </button>
              </form>
              <form method="post" action="/gerente/produtos/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <button class="btn btn--ghost btn--icon" type="submit" aria-label="Excluir produto" title="Excluir">
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