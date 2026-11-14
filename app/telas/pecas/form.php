<?php
/**
 * Tela: formulário de peça base
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Rota: GET /gerente/pecas/form
 * Recebe: $titulo, $peca, $categorias
 * Campos de $categorias: id, nome, slug, mockup
 * Envia POST para /gerente/pecas/salvar (multipart, por causa da foto).
 * Campos: id, nome, categoria_id, foto (arquivo), ativo
 * Responsabilidade: cadastro e edição de peças base. Na edição, a foto é opcional (sem foto nova,
 * mantém a atual). A validação final acontece no PecaServico.
 */

$id = (int) ($peca['id'] ?? 0);
$fotoAtual = (string) ($peca['imagem_path'] ?? '');
$ativa = filter_var($peca['ativo'] ?? false, FILTER_VALIDATE_BOOLEAN);
?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/pecas/salvar" enctype="multipart/form-data">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= $id ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" maxlength="80" required
             value="<?= e($peca['nome'] ?? '') ?>" placeholder="Ex.: Toalha de rosto">
    </div>

    <div class="form__field">
      <label for="categoria_id">Categoria</label>
      <select class="text-field" id="categoria_id" name="categoria_id" required>
        <option value="">Selecione</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= (int) $c['id'] ?>" <?= (int) ($peca['categoria_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
            <?= e($c['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form__field">
    <label for="foto">Foto da peça <?= $id ? '(opcional: envie só para trocar)' : '' ?></label>
    <input class="text-field" type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp" <?= $id ? '' : 'required' ?>>
    <p class="field-hint">PNG, JPEG ou WebP de até 2 MB. Prefira fundo transparente ou claro, com a peça inteira e de frente.</p>
    <?php if ($fotoAtual !== ''): ?>
      <img src="<?= e(StorageServico::urlPublica($fotoAtual)) ?>" alt="Foto atual de <?= e($peca['nome'] ?? 'peça') ?>"
           width="160" height="200" style="object-fit: contain">
    <?php endif; ?>
  </div>

  <div class="form__field">
    <input type="checkbox" id="ativo" name="ativo" value="1" <?= $ativa ? 'checked' : '' ?>>
    <label for="ativo">Mostrar no montador</label>
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Salvar</button>
    <a class="btn btn--ghost" href="/gerente/pecas">Cancelar</a>
  </div>
</form>
