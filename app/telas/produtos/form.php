<?php
/*
 * produtos/form.php
 * Tela de cadastro e edição de produtos.
 */
?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/produtos/salvar">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= (int) ($produto['id'] ?? 0) ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="bordado_id">Combinação</label>
      <select class="text-field" id="bordado_id" name="bordado_id" required>
        <option value="">Selecione</option>
        <?php foreach ($combinacoes as $c): ?>
          <option value="<?= (int) $c['id'] ?>"
            <?= (int) ($produto['bordado_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
            <?= e($c['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <a class="btn btn--ghost btn--sm" href="/montador?modo=produto">Criar combinação</a>
    </div>

    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" value="<?= e($produto['nome'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="preco_base">Preço base</label>
      <input class="text-field" type="number" min="0" step="0.01" id="preco_base"
             name="preco_base" value="<?= e($produto['preco_base'] ?? '') ?>" required>
    </div>
  </div>

  <div class="form__field">
    <label for="descricao">Descrição</label>
    <textarea class="text-field" id="descricao" name="descricao" rows="4"><?= e($produto['descricao'] ?? '') ?></textarea>
  </div>

  <div class="form__field">
    <input type="checkbox" id="destaque" name="destaque" value="1"
           <?= !empty($produto['destaque']) ? 'checked' : '' ?>>
    <label for="destaque">Destaque</label>
  </div>

  <div class="form__field">
    <input type="checkbox" id="ativo" name="ativo" value="1"
           <?= !empty($produto['ativo']) ? 'checked' : '' ?>>
    <label for="ativo">Ativo</label>
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Salvar</button>
    <a class="btn btn--ghost" href="/gerente/produtos">Cancelar</a>
    <?php if (!empty($produto['id'])): ?>
      <button class="btn btn--ghost" type="submit" formaction="/gerente/produtos/excluir"
              onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</button>
    <?php endif; ?>
  </div>
</form>