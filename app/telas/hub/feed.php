<?php
/**
 * Tela: feed do Hub
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /hub
 * Recebe: $titulo, $bordados, $categorias, $categoriaAtual, $ordem, $pagina, $temMais
 *
 * TODO(Eduardo):
 * 1. Criar filtros ?categoria=slug e ordenação ?ordem=recentes ou ?ordem=curtidos.
 * 2. Mostrar a grade com _card.php.
 * 3. Criar Anterior e Próxima, preservando categoria e ordem.
 * 4. Sem posts, mostrar .empty-state com link para /montador.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */
?>

<?php
$categoriaAtual = is_string($categoriaAtual ?? null) ? $categoriaAtual : '';
$ordemAtual = ($ordem ?? '') === 'curtidos' ? 'curtidos' : 'recentes';

$parametrosBase = ['ordem' => $ordemAtual];

if ($categoriaAtual !== '') {
    $parametrosBase['categoria'] = $categoriaAtual;
}

$urlRecentes = '/hub?' . http_build_query(
    array_merge($parametrosBase, ['ordem' => 'recentes'])
);

$urlCurtidos = '/hub?' . http_build_query(
    array_merge($parametrosBase, ['ordem' => 'curtidos'])
);
?>

<div class="page-head">
  <h1><?= e($titulo) ?></h1>
</div>

<nav class="catalog-filters" aria-label="Filtrar por categoria">
  <a
    class="filter-chip <?= $categoriaAtual === '' ? 'filter-chip--active' : '' ?>"
    href="/hub?ordem=<?= e($ordemAtual) ?>"
  >
    Todas
  </a>

  <?php foreach ($categorias as $categoria): ?>
    <?php
    $slug = (string) $categoria['slug'];
    $urlCategoria = '/hub?' . http_build_query([
        'categoria' => $slug,
        'ordem' => $ordemAtual,
    ]);
    ?>
    <a
      class="filter-chip <?= $categoriaAtual === $slug ? 'filter-chip--active' : '' ?>"
      href="<?= e($urlCategoria) ?>"
    >
      <?= e($categoria['nome']) ?>
    </a>
  <?php endforeach; ?>
</nav>

<nav class="catalog-filters" aria-label="Ordenar bordados">
  <a
    class="filter-chip <?= $ordemAtual === 'recentes' ? 'filter-chip--active' : '' ?>"
    href="<?= e($urlRecentes) ?>"
  >
    Recentes
  </a>

  <a
    class="filter-chip <?= $ordemAtual === 'curtidos' ? 'filter-chip--active' : '' ?>"
    href="<?= e($urlCurtidos) ?>"
  >
    Mais curtidos
  </a>
</nav>

<?php if (empty($bordados)): ?>
  <p class="empty-state">
    Ainda não há bordados para mostrar.
    <a href="/montador">Crie um bordado</a>
  </p>
<?php else: ?>
  <div class="product-grid">
    <?php foreach ($bordados as $b): ?>
      <?php require RAIZ . '/app/telas/hub/_card.php'; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ($pagina > 1 || $temMais): ?>
  <nav class="hub-paginacao" aria-label="Paginação do feed">
    <?php if ($pagina > 1): ?>
      <?php
      $urlAnterior = '/hub?' . http_build_query(
          array_merge($parametrosBase, ['pagina' => $pagina - 1])
      );
      ?>
      <a class="btn btn--ghost" href="<?= e($urlAnterior) ?>">Anterior</a>
    <?php endif; ?>

    <?php if ($temMais): ?>
      <?php
      $urlProxima = '/hub?' . http_build_query(
          array_merge($parametrosBase, ['pagina' => $pagina + 1])
      );
      ?>
      <a class="btn btn--ghost" href="<?= e($urlProxima) ?>">Próxima</a>
    <?php endif; ?>
  </nav>
<?php endif; ?>
