<?php
/**
 * Tela: catálogo público
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /catalogo
 * Recebe: $titulo, $categorias, $categoriaAtual, $produtos
 * Campos de $categorias: nome, slug
 *
 * 1. Basear a estrutura em Apenas Mostruario/catalogo.html.
 * 2. Mostrar filtros com link ?categoria=slug.
 * 3. Mostrar a grade com .product-grid e Visao::parcial('peca_bordada').
 * 4. Sem produtos, mostrar .empty-state.
*/

?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
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
          <h2 class="product-card__name"><?= e($produto['nome']) ?></h2>
          <p class="product-card__price">A partir de <?= moeda($produto['preco_base']) ?></p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>