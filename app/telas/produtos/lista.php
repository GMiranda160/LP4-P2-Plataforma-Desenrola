<?php
/**
 * Tela: lista de produtos
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/produtos
 * Recebe: $titulo, $produtos
 * Campos de $produtos: id, nome, categoria, preco_base, destaque, ativo
 *
 * 1. Mostrar tabela com situação e botão Novo produto.
 * 2. Adicionar editar e excluir por POST em /gerente/produtos/excluir.
 * 3. Adicionar Ativar/Desativar por POST em /gerente/produtos/ativar.
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
          <th>Ações</th>
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
            <td class="table__actions">
              <a class="btn btn--ghost btn--sm" href="/gerente/produtos/form?id=<?= (int) $p['id'] ?>">Editar</a>
              <form method="post" action="/gerente/produtos/ativar">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <button class="btn btn--ghost btn--sm" type="submit">
                  <?= !empty($p['ativo']) ? 'Desativar' : 'Ativar' ?>
                </button>
              </form>
              <form method="post" action="/gerente/produtos/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <button class="btn btn--ghost btn--sm" type="submit">Excluir</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>