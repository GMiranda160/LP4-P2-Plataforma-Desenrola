<?php
/**
 * Tela: formulário de insumo
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/insumos/form
 * Recebe: $titulo, $insumo
 * Envia POST para /gerente/insumos/salvar.
 * Campos: id, tipo, nome, cor_hex, quantidade, unidade, custo_unitario, fornecedor, estoque_minimo
 * Responsabilidade: cadastro e edição de insumos; a validação final acontece no InsumoServico.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */

$tipos = ['tecido' => 'Tecido', 'linha' => 'Linha', 'aviamento' => 'Aviamento'];
$unidades = ['metro' => 'Metro', 'carretel' => 'Carretel', 'peca' => 'Peça'];
$corAtual = preg_match('/^#[0-9a-f]{6}$/i', $insumo['cor_hex'] ?? '')
    ? $insumo['cor_hex']
    : '#ffffff';
?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/insumos/salvar">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= (int) ($insumo['id'] ?? 0) ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="tipo">Tipo</label>
      <select class="text-field" id="tipo" name="tipo" required>
        <option value="">Selecione</option>
        <?php foreach ($tipos as $valor => $rotulo): ?>
          <option value="<?= $valor ?>" <?= ($insumo['tipo'] ?? '') === $valor ? 'selected' : '' ?>>
            <?= $rotulo ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" value="<?= e($insumo['nome'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="cor_hex">Cor</label>
      <input class="text-field" type="color" id="cor_hex" name="cor_hex" value="<?= e($corAtual) ?>">
    </div>

    <div class="form__field">
      <label for="fornecedor">Fornecedor</label>
      <input class="text-field" id="fornecedor" name="fornecedor" value="<?= e($insumo['fornecedor'] ?? '') ?>">
    </div>

    <div class="form__field">
      <label for="quantidade">Quantidade</label>
      <input class="text-field" type="number" step="0.01" min="0" id="quantidade" name="quantidade"
             value="<?= e($insumo['quantidade'] ?? '0') ?>">
    </div>

    <div class="form__field">
      <label for="unidade">Unidade</label>
      <select class="text-field" id="unidade" name="unidade" required>
        <option value="">Selecione</option>
        <?php foreach ($unidades as $valor => $rotulo): ?>
          <option value="<?= $valor ?>" <?= ($insumo['unidade'] ?? '') === $valor ? 'selected' : '' ?>>
            <?= $rotulo ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form__field">
      <label for="custo_unitario">Custo unitário</label>
      <input class="text-field" type="number" step="0.01" min="0.01" id="custo_unitario" name="custo_unitario"
             value="<?= e($insumo['custo_unitario'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="estoque_minimo">Estoque mínimo</label>
      <input class="text-field" type="number" step="0.01" min="0" id="estoque_minimo" name="estoque_minimo"
             value="<?= e($insumo['estoque_minimo'] ?? '0') ?>">
    </div>
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Salvar</button>
    <a class="btn btn--ghost" href="/gerente/insumos">Cancelar</a>
    <?php if (!empty($insumo['id'])): ?>
      <button class="btn btn--ghost" type="submit" formaction="/gerente/insumos/excluir"
              onclick="return confirm('Tem certeza que deseja excluir este insumo?')">Excluir</button>
    <?php endif; ?>
  </div>
</form>