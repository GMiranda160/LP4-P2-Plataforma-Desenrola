<?php
/**
 * Tela: meus bordados
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /meus-bordados
 * Recebe: $titulo, $bordados
 * Responsabilidade: os bordados do próprio usuário (inclusive ocultos) no mesmo post completo do feed (_card.php),
 *                   com um resumo ao lado (bordados, curtidas e comentários recebidos) e atalho para o montador.
 *                   Convite para o montador quando vazia.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */

require_once RAIZ . '/app/telas/hub/_ajudantes.php';

$usuario = Auth::usuario() ?? [];
$nome = (string) ($usuario['nome'] ?? '');
$totalBordados = count($bordados);
$totalCurtidas = array_sum(array_map(static fn (array $b): int => (int) $b['curtidas'], $bordados));
$totalComentarios = array_sum(array_map(static fn (array $b): int => (int) $b['comentarios'], $bordados));
?>
<div class="container hub-page">

  <header class="hub-cabecalho">
    <div>
      <p class="eyebrow">Hub de bordados</p>
      <h1><?= e($titulo) ?></h1>
      <p class="lead">Tudo o que você publicou, inclusive os bordados ocultos pela gerente.</p>
    </div>
    <a class="btn btn--primary" href="/montador"><?= hub_icone('novo') ?> Novo bordado</a>
  </header>

  <div class="hub-layout">

    <aside class="hub-lateral" aria-label="Resumo">
      <section class="hub-lateral__bloco hub-resumo">
        <div class="hub-resumo__quem">
          <?= hub_avatar($nome) ?>
          <strong><?= e($nome) ?></strong>
        </div>
        <dl class="hub-resumo__numeros">
          <div><dt>Bordados</dt><dd><?= $totalBordados ?></dd></div>
          <div><dt>Curtidas</dt><dd><?= $totalCurtidas ?></dd></div>
          <div><dt>Comentários</dt><dd><?= $totalComentarios ?></dd></div>
        </dl>
        <a class="link-button" href="/hub">Ver o feed do Hub</a>
      </section>
    </aside>

    <div class="hub-feed">
      <?php if (empty($bordados)): ?>
        <div class="hub-vazio">
          <h2>Você ainda não tem bordados</h2>
          <p>Quando publicar um bordado pelo montador, ele aparece aqui e no feed.</p>
          <a class="btn btn--primary" href="/montador">Crie seu primeiro bordado</a>
        </div>
      <?php else: ?>
        <?php foreach ($bordados as $b): ?>
          <?php $modoPost = 'feed'; require RAIZ . '/app/telas/hub/_card.php'; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</div>
