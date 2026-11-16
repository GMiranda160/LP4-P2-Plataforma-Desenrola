<?php
/**
 * Tela: formulário de produto
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/produtos/form
 * Recebe: $titulo, $produto, $combinacoes
 * Envia POST (multipart, por causa da foto) para /gerente/produtos/salvar.
 * Campos: id, bordado_id, nome, descricao, preco_base, destaque, ativo, foto (arquivo), remover_foto
 * Responsabilidade: cadastro e edição de produtos; a combinação é um bordado feito por um gerente
 * (como combinação no Montador, ou como post no Hub), ainda sem produto vinculado.
 * Sem foto própria, a página do produto usa a renderização da peça bordada.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */

$id = (int) ($produto['id'] ?? 0);
$fotoAtual = (string) ($produto['foto_path'] ?? '');
?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/produtos/salvar" enctype="multipart/form-data">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= (int) ($produto['id'] ?? 0) ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="bordado_id">Combinação</label>
      <select class="text-field" id="bordado_id" name="bordado_id" required data-combinacao>
        <option value="">Selecione</option>
        <optgroup label="Combinações de produto">
          <?php foreach ($combinacoes as $c): ?>
            <?php if (($c['tipo'] ?? 'produto') !== 'produto') { continue; } ?>
            <option value="<?= (int) $c['id'] ?>"
              data-nome="<?= e($c['titulo']) ?>" data-descricao="<?= e($c['descricao'] ?? '') ?>"
              <?= (int) ($produto['bordado_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
              <?= e($c['titulo']) ?><?= !empty($c['autor']) ? ' · ' . e($c['autor']) : '' ?>
            </option>
          <?php endforeach; ?>
        </optgroup>
        <optgroup label="Bordados de gerente no Hub">
          <?php foreach ($combinacoes as $c): ?>
            <?php if (($c['tipo'] ?? 'produto') === 'produto') { continue; } ?>
            <option value="<?= (int) $c['id'] ?>"
              data-nome="<?= e($c['titulo']) ?>" data-descricao="<?= e($c['descricao'] ?? '') ?>"
              <?= (int) ($produto['bordado_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
              <?= e($c['titulo']) ?><?= !empty($c['autor']) ? ' · ' . e($c['autor']) : '' ?>
            </option>
          <?php endforeach; ?>
        </optgroup>
      </select>
      <a class="btn btn--ghost btn--sm" href="/montador?modo=produto">Criar combinação</a>
    </div>

    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" value="<?= e($produto['nome'] ?? '') ?>" required>
    </div>

    <div class="form__field">
      <label for="preco_base">Preço base</label>
      <input class="text-field" type="number" min="0" step="0.01" id="preco_base" data-moeda
             name="preco_base" value="<?= e($produto['preco_base'] ?? '') ?>" required>
    </div>
  </div>

  <div class="form__field">
    <label for="descricao">Descrição</label>
    <textarea class="text-field" id="descricao" name="descricao" rows="4"><?= e($produto['descricao'] ?? '') ?></textarea>
  </div>

  <div class="form__field">
    <label for="foto">Foto do produto <?= $id ? '(opcional: envie só para trocar)' : '(opcional)' ?></label>
    <input class="text-field" type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp">
    <p class="field-hint">PNG, JPEG ou WebP de até 2 MB. Sem foto, a página do produto e os cards usam a renderização da peça bordada.</p>
    <?php if ($fotoAtual !== ''): ?>
      <img src="<?= e(StorageServico::urlPublica($fotoAtual)) ?>" alt="Foto atual de <?= e($produto['nome'] ?? 'produto') ?>"
           width="160" height="200" style="object-fit: cover">
    <?php endif; ?>
  </div>
  <?php if ($fotoAtual !== ''): ?>
    <div class="form__field">
      <input type="checkbox" id="remover_foto" name="remover_foto" value="1">
      <label for="remover_foto">Remover a foto e voltar à renderização da peça</label>
    </div>
  <?php endif; ?>

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