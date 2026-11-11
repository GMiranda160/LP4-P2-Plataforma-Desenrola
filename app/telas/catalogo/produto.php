<?php
/*
 * catalogo/produto.php
 * Tela pública de um produto.
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