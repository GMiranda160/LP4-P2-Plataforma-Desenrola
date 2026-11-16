<?php
/**
 * Tela: catálogo público
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /catalogo
 * Recebe: $titulo, $pagina, $categorias, $categoriaAtual, $produtos
 * Campos de $categorias: nome, slug
 * Campos de $produtos: id, nome, descricao, preco_base, categoria e dados da peça
 * Responsabilidade: filtros por categoria e grade de produtos do mostruário; sem produtos, exibe o estado vazio.
 * Botão de guia ao lado do título: incluído pelo Guilherme (regra: o Guilherme altera qualquer arquivo).
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
<section class="section catalogo">
<div class="container">
<div class="page-head">
  <div class="page-head__lado">
    <h1><?= e($pagina ?? $titulo) ?></h1>
    <button class="guia-btn" type="button" data-guia-abrir="guia-catalogo" aria-label="Como o catálogo funciona" aria-haspopup="dialog">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
    </button>
  </div>
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