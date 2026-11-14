<?php
/**
 * Tela: lista de clientes
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/clientes
 * Recebe: $titulo, $busca, $clientes
 * Campos de $clientes: id, nome, cpf_cnpj, email, telefone, endereco, cep, criado_em
 * Responsabilidade: listar clientes com busca por nome ou CPF/CNPJ e ações de editar/excluir.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/gerente/clientes/form">Novo cliente</a>
</div>

<form method="get" class="form">
  <label class="visually-hidden" for="busca">Buscar</label>
  <input class="text-field" id="busca" name="busca" value="<?= e($busca) ?>"
         placeholder="Buscar por nome ou CPF/CNPJ">
</form>

<?php if (!$clientes): ?>
  <p class="empty-state">Nenhum cliente encontrado.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>CPF/CNPJ</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Cadastrado em</th>
          <th class="table__acao-head">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $c): ?>
          <tr>
            <td><?= e($c['nome']) ?></td>
            <td><?= e(cpf_cnpj($c['cpf_cnpj'])) ?></td>
            <td><?= e($c['email']) ?></td>
            <td><?= e($c['telefone']) ?></td>
            <td><?= e(data_br($c['criado_em'])) ?></td>
            <td class="table__actions table__actions--centro">
              <a class="btn btn--ghost btn--icon" href="/gerente/clientes/form?id=<?= (int) $c['id'] ?>" aria-label="Editar cliente" title="Editar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.85 0 0 1 4 4L8 20l-5 1 1-5L17 3Z"/></svg>
              </a>
              <form method="post" action="/gerente/clientes/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                <button class="btn btn--ghost btn--icon" type="submit" aria-label="Excluir cliente" title="Excluir">
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