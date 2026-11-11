<?php
/**
 * Tela: página do produto
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /produto?id=
 * Recebe: $titulo, $produto
 * Campos de $produto: id, nome, descricao, preco_base, categoria e dados da peça
 *
 * 1. Mostrar peça, nome, descrição e preço.
 * 2. Criar o botão Solicitar orçamento com link_whatsapp.
*/

?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
</div>

<article class="product-detail">
  <div class="product-detail__media">
    <?php Visao::parcial('peca_bordada', ['item' => $produto]); ?>
  </div>

  <div class="product-detail__info">
    <h2 class="product-detail__name"><?= e($produto['nome']) ?></h2>
    <p class="product-detail__price">A partir de <?= moeda($produto['preco_base']) ?></p>
    <p class="product-detail__desc"><?= e($produto['descricao']) ?></p>

    <a class="btn btn--primary btn--block" target="_blank" rel="noopener noreferrer"
       href="<?= e(link_whatsapp("Olá! Quero um orçamento de: {$produto['nome']}")) ?>">
      Solicitar orçamento
    </a>
  </div>
</article>