<?php
/**
 * Tela: feed do Hub
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /hub
 * Recebe: $titulo, $bordados, $categorias, $categoriaAtual, $ordem, $pagina, $temMais
 * Responsabilidade: feed do Hub como rede social: coluna de posts completos (_card.php) e, ao lado,
 *                   filtros por tipo de peça, ordenação e atalhos para criar e ver os próprios bordados.
 *                   Paginação no fim e convite para o montador quando não há posts.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */

require_once RAIZ . '/app/telas/hub/_ajudantes.php';

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
<div class="container hub-page">

  <header class="hub-cabecalho">
    <div>
      <p class="eyebrow">Comunidade</p>
      <h1><?= e($titulo) ?></h1>
      <p class="lead">O que a comunidade está bordando agora. Curta, comente e peça o tutorial de cada peça.</p>
    </div>
    <a class="btn btn--primary" href="/montador"><?= hub_icone('novo') ?> Criar bordado</a>
  </header>

  <div class="hub-layout">

    <aside class="hub-lateral" aria-label="Filtros do feed">
      <section class="hub-lateral__bloco">
        <h2 class="hub-lateral__titulo">Tipo de peça</h2>
        <nav class="catalog-filters" aria-label="Filtrar por tipo de peça">
          <a
            class="filter-chip <?= $categoriaAtual === '' ? 'filter-chip--active' : '' ?>"
            href="/hub?ordem=<?= e($ordemAtual) ?>"
          >
            <?= hub_icone_categoria('todas') ?> Todas
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
              <?= hub_icone_categoria($slug) ?> <?= e($categoria['nome']) ?>
            </a>
          <?php endforeach; ?>
        </nav>
      </section>

      <section class="hub-lateral__bloco">
        <h2 class="hub-lateral__titulo">Ordenar por</h2>
        <nav class="catalog-filters" aria-label="Ordenar bordados">
          <a
            class="filter-chip <?= $ordemAtual === 'recentes' ? 'filter-chip--active' : '' ?>"
            href="<?= e($urlRecentes) ?>"
          >
            <?= hub_icone('recentes') ?> Recentes
          </a>
          <a
            class="filter-chip <?= $ordemAtual === 'curtidos' ? 'filter-chip--active' : '' ?>"
            href="<?= e($urlCurtidos) ?>"
          >
            <?= hub_icone('coracao') ?> Mais curtidos
          </a>
        </nav>
      </section>

      <section class="hub-lateral__bloco hub-lateral__convite">
        <h2 class="hub-lateral__titulo">Seu bordado no Hub</h2>
        <p>Transforme uma imagem em matriz, escolha a peça e publique para a comunidade.</p>
        <a class="btn btn--ghost btn--sm" href="/montador">Abrir o montador</a>
        <?php if (Auth::id() !== null): ?>
          <a class="link-button" href="/meus-bordados">Ver meus bordados</a>
        <?php endif; ?>
      </section>
    </aside>

    <div class="hub-feed">
      <?php if (empty($bordados)): ?>
        <div class="hub-vazio">
          <h2>Nenhum bordado por aqui</h2>
          <p>Ainda não há bordados para mostrar com esses filtros.</p>
          <a class="btn btn--primary" href="/montador">Crie um bordado</a>
        </div>
      <?php else: ?>
        <?php foreach ($bordados as $b): ?>
          <?php $modoPost = 'feed'; require RAIZ . '/app/telas/hub/_card.php'; ?>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ($pagina > 1 || $temMais): ?>
        <nav class="hub-paginacao" aria-label="Paginação do feed">
          <?php if ($pagina > 1): ?>
            <?php
            $urlAnterior = '/hub?' . http_build_query(
                array_merge($parametrosBase, ['pagina' => $pagina - 1])
            );
            ?>
            <a class="btn btn--ghost" href="<?= e($urlAnterior) ?>">Anteriores</a>
          <?php endif; ?>

          <?php if ($temMais): ?>
            <?php
            $urlProxima = '/hub?' . http_build_query(
                array_merge($parametrosBase, ['pagina' => $pagina + 1])
            );
            ?>
            <a class="btn btn--ghost" href="<?= e($urlProxima) ?>">Mais bordados</a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    </div>

  </div>
</div>
