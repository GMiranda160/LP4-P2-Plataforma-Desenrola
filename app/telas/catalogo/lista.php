<?php
/**
 * Tela: catálogo público
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /catalogo
 * Recebe: $titulo, $pagina, $categorias, $categoriaAtual, $produtos
 * Campos de $categorias: nome, slug
 * Campos de $produtos: id, nome, descricao, preco_base, categoria e dados da peça
 * Responsabilidade: filtros por categoria e grade de produtos do mostruário; sem produtos, exibe o estado vazio.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
<section class="section">
<div class="container">
<div class="page-head">
  <h1><?= e($pagina ?? $titulo) ?></h1>
</div>

<nav class="catalog-filters" aria-label="Filtrar por categoria">
  <a class="filter-chip <?= $categoriaAtual === '' ? 'filter-chip--active' : '' ?>"
     href="/catalogo">Todos</a>
  <?php foreach ($categorias as $c): ?>
    <a class="filter-chip <?= $categoriaAtual === $c['slug'] ? 'filter-chip--active' : '' ?>"
       href="/catalogo?categoria=<?= e($c['slug']) ?>">
      <?= e($c['nome']) ?>
    </a>
  <?php endforeach; ?>
</nav>

<?php if (!$produtos): ?>
  <p class="empty-state">Nenhum produto encontrado.</p>
<?php else: ?>
  <div class="product-grid">
    <?php foreach ($produtos as $produto): ?>
      <article class="product-card">
        <a class="product-card__media" href="/produto?id=<?= (int) $produto['id'] ?>">
          <?php Visao::parcial('peca_bordada', ['item' => $produto]); ?>
        </a>
        <div class="product-card__body">
          <?php if (!empty($produto['categoria'])): ?>
            <p class="product-card__category"><?= e($produto['categoria']) ?></p>
          <?php endif; ?>
          <h2 class="product-card__name"><?= e($produto['nome']) ?></h2>
          <p class="product-card__price"><span class="product-card__price-label">A partir de</span> <strong><?= moeda($produto['preco_base']) ?></strong></p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
</div>
</section>