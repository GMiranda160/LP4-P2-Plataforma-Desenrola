<?php
/**
 * Tela: formulário de matriz
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/matrizes/form
 * Recebe: $titulo, $matriz
 * Envia POST para /gerente/matrizes/salvar.
 * Campos: id, nome, descricao, categoria, tempo_medio_min
 *
 * 1. Criar o formulário com Csrf::campo() e o id oculto.
 * 2. Mostrar a miniatura da matriz em img.
*/

?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/matrizes/salvar">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= (int) ($matriz['id'] ?? 0) ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" value="<?= e($matriz['nome'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="categoria">Categoria</label>
      <input class="text-field" id="categoria" name="categoria" value="<?= e($matriz['categoria'] ?? '') ?>">
    </div>

    <div class="form__field">
      <label for="tempo_medio_min">Tempo médio (min)</label>
      <input class="text-field" type="number" min="0" step="1" id="tempo_medio_min"
             name="tempo_medio_min" value="<?= e($matriz['tempo_medio_min'] ?? '') ?>">
    </div>
  </div>

  <div class="form__field">
    <label for="descricao">Descrição</label>
    <textarea class="text-field" id="descricao" name="descricao" rows="4"><?= e($matriz['descricao'] ?? '') ?></textarea>
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Salvar</button>
    <a class="btn btn--ghost" href="/gerente/matrizes">Cancelar</a>
    <?php if (!empty($matriz['id'])): ?>
      <button class="btn btn--ghost" type="submit" formaction="/gerente/matrizes/excluir"
              onclick="return confirm('Tem certeza que deseja excluir esta matriz?')">Excluir</button>
    <?php endif; ?>
  </div>
</form>