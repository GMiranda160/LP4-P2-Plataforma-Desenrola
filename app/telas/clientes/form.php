<?php
/**
 * Tela: formulário de cliente
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/clientes/form
 * Recebe: $titulo, $cliente
 * Envia POST para /gerente/clientes/salvar.
 * Campos: id, nome, cpf_cnpj, email, telefone, endereco, cep
 * Responsabilidade: cadastro e edição de clientes; a validação final acontece no ClienteServico.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/clientes/salvar">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= (int) ($cliente['id'] ?? 0) ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" value="<?= e($cliente['nome'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="cpf_cnpj">CPF ou CNPJ</label>
      <input class="text-field" id="cpf_cnpj" name="cpf_cnpj"
             value="<?= e($cliente['cpf_cnpj'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="email">E-mail</label>
      <input class="text-field" type="email" id="email" name="email"
             value="<?= e($cliente['email'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="telefone">Telefone</label>
      <input class="text-field" id="telefone" name="telefone"
             value="<?= e($cliente['telefone'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="endereco">Endereço</label>
      <input class="text-field" id="endereco" name="endereco"
             value="<?= e($cliente['endereco'] ?? '') ?>">
    </div>

    <div class="form__field">
      <label for="cep">CEP</label>
      <input class="text-field" id="cep" name="cep"
             value="<?= e($cliente['cep'] ?? '') ?>">
    </div>
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Salvar</button>
    <a class="btn btn--ghost" href="/gerente/clientes">Cancelar</a>
    <?php if (!empty($cliente['id'])): ?>
      <button class="btn btn--ghost" type="submit" formaction="/gerente/clientes/excluir"
              onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</button>
    <?php endif; ?>
  </div>
</form>