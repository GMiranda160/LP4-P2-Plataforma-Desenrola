<?php
/*
 * clientes/lista.php
 * Tela de listagem de clientes.
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
          <th>Ações</th>
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
            <td class="table__actions">
              <a class="btn btn--ghost btn--sm" href="/gerente/clientes/form?id=<?= (int) $c['id'] ?>">Editar</a>
              <form method="post" action="/gerente/clientes/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                <button class="btn btn--ghost btn--sm" type="submit">Excluir</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>