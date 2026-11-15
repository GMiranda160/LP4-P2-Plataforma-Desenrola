<?php
/**
 * Tela: lista de usuários
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /gerente/usuarios
 * Recebe: $titulo, $busca, $usuarios
 * Campos de $usuarios: id, nome, email, perfil, criado_em, total_bordados
 *
 * TODO(Eduardo):
 * 1. Mostrar busca e tabela, sem ações de edição.
 * 2. Não adicionar botão de voltar no .page-head: o layout gerente.php já exibe o botão
 *    contextual no topo da tela ("Voltar para o Painel"), junto com o "Voltar para o site" da topbar.
 * Veja INSTRUCOES_EDUARDO.md, seção 4.
 */
?>

<div class="page-head">
  <h1><?= e($titulo) ?></h1>
</div>

<form class="form" method="get" action="/gerente/usuarios">
  <div class="form__field">
    <label for="busca">Buscar por nome ou e-mail</label>
    <input
      class="text-field"
      id="busca"
      name="busca"
      type="search"
      value="<?= e($busca) ?>"
    >
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Buscar</button>
    <a class="btn btn--ghost" href="/gerente/usuarios">Limpar busca</a>
  </div>
</form>

<?php if (empty($usuarios)): ?>
  <p class="empty-state">Nenhum usuário encontrado.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <caption class="visually-hidden">Usuários cadastrados</caption>
      <thead>
        <tr>
          <th scope="col">Nome</th>
          <th scope="col">E-mail</th>
          <th scope="col">Perfil</th>
          <th scope="col">Cadastro</th>
          <th scope="col">Bordados</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $usuario): ?>
          <tr>
            <td><?= e($usuario['nome']) ?></td>
            <td><?= e($usuario['email']) ?></td>
            <td><?= e($usuario['perfil']) ?></td>
            <td><?= e(data_br($usuario['criado_em'])) ?></td>
            <td><?= (int) $usuario['total_bordados'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
