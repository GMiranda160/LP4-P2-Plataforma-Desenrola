<?php
/**
 * Tela: lista de peças base
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Rota: GET /gerente/pecas
 * Recebe: $titulo, $pecas
 * Campos de $pecas: id, nome, categoria_id, categoria, categoria_slug, imagem_path, ativo, criado_em
 * Envia POST para /gerente/pecas/excluir.
 * Campos: id
 * Responsabilidade: listar as peças base (foto, nome, categoria, situação) com editar e excluir.
 * As peças ativas são as opções da etapa "Peça" do montador.
 */
?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/gerente/pecas/form">Nova peça</a>
</div>

<p class="field-hint">Peças com foto real que aparecem na etapa "Peça" do montador. Só as ativas aparecem.</p>

<?php if (!$pecas): ?>
  <p class="empty-state">Nenhuma peça cadastrada.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table table--centrada">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Nome</th>
          <th>Categoria</th>
          <th>Situação</th>
          <th class="table__acao-head">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pecas as $p): ?>
          <tr>
            <td>
              <img src="<?= e(StorageServico::urlPublica((string) $p['imagem_path'])) ?>" alt="<?= e($p['nome']) ?>"
                   width="56" height="64" loading="lazy" style="object-fit: contain">
            </td>
            <td><?= e($p['nome']) ?></td>
            <td><?= e($p['categoria']) ?></td>
            <td>
              <?php if (filter_var($p['ativo'], FILTER_VALIDATE_BOOLEAN)): ?>
                <span class="badge badge--ok">No montador</span>
              <?php else: ?>
                <span class="badge badge--alerta">Oculta</span>
              <?php endif; ?>
            </td>
            <td class="table__actions table__actions--centro">
              <a class="btn btn--ghost btn--icon" href="/gerente/pecas/form?id=<?= (int) $p['id'] ?>" aria-label="Editar peça" title="Editar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.85 0 0 1 4 4L8 20l-5 1 1-5L17 3Z"/></svg>
              </a>
              <form method="post" action="/gerente/pecas/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir esta peça?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <button class="btn btn--ghost btn--icon" type="submit" aria-label="Excluir peça" title="Excluir">
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
