<?php
/**
 * Tela: post do Hub
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Recebe: $b e, opcionalmente, $modoPost ('feed' ou 'detalhe') e $comentariosPost (lista completa, no detalhe)
 * Campos de $b: id, titulo, descricao, autor, autor_id, categoria, mockup, cor_peca, pos_x, pos_y, escala, svg,
 *               largura, altura, total_pontos, paleta, tutorial_ia, curtidas, comentarios, curtiu, oculto,
 *               criado_em, podeExcluir e ultimosComentarios (no feed)
 * Responsabilidade: um bordado como post completo de rede social: foto da peça, autor, tipo, tempo, título,
 *                   descrição, ficha da matriz com paleta, curtir, comentários com campo de comentar, tutorial com IA
 *                   e, para quem pode, ocultar/reexibir e excluir. Cada ação volta para a página onde o post está.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */

require_once RAIZ . '/app/telas/hub/_ajudantes.php';

$modoPost = ($modoPost ?? 'feed') === 'detalhe' ? 'detalhe' : 'feed';
$idPost = (int) $b['id'];
$urlPost = '/hub/bordado?id=' . $idPost;
$ancora = 'post-' . $idPost;

// Volta para a mesma página e para o mesmo post depois de comentar, ocultar ou excluir.
$caminhoAtual = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/hub'), PHP_URL_PATH);
$consultaAtual = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_QUERY);
$voltar = $caminhoAtual . ($consultaAtual !== '' ? '?' . $consultaAtual : '') . '#' . $ancora;

$totalCurtidas = (int) $b['curtidas'];
$totalComentarios = (int) $b['comentarios'];
$comentariosVisiveis = $modoPost === 'detalhe'
    ? ($comentariosPost ?? [])
    : ($b['ultimosComentarios'] ?? []);

$paleta = array_values(array_filter(
    is_array($b['paleta'] ?? null) ? $b['paleta'] : [],
    static fn ($cor): bool => is_string($cor) && preg_match('/\A#[0-9a-fA-F]{6}\z/', $cor) === 1
));

$temTutorial = is_string($b['tutorial_ia'] ?? null) && trim((string) $b['tutorial_ia']) !== '';
$podeExcluir = !empty($b['podeExcluir']);
$eGerente = Auth::eGerente();
$logado = Auth::id() !== null;
?>
<article class="hub-post hub-post--<?= $modoPost ?>" id="<?= e($ancora) ?>" data-post>

  <div class="hub-post__midia">
    <?php if ($modoPost === 'feed'): ?>
      <a href="<?= e($urlPost) ?>" aria-label="Abrir <?= e($b['titulo']) ?>">
        <?php Visao::parcial('peca_bordada', ['item' => $b]); ?>
      </a>
    <?php else: ?>
      <?php Visao::parcial('peca_bordada', ['item' => $b]); ?>
    <?php endif; ?>
  </div>

  <div class="hub-post__corpo">

    <div class="hub-post__rolagem">
    <header class="hub-post__autoria">
      <?= hub_avatar((string) $b['autor']) ?>
      <div class="hub-post__quem">
        <strong><?= e($b['autor']) ?></strong>
        <time datetime="<?= e($b['criado_em']) ?>" title="<?= e(data_br($b['criado_em'])) ?>">
          <?= e(hub_tempo($b['criado_em'])) ?>
        </time>
      </div>
      <div class="hub-post__selos">
        <span class="hub-post__tipo"><?= e($b['categoria']) ?></span>
        <?php if (!empty($b['oculto'])): ?>
          <span class="badge badge--alerta">Oculto</span>
        <?php endif; ?>
      </div>
    </header>

    <div class="hub-post__texto">
      <?php if ($modoPost === 'feed'): ?>
        <h2 class="hub-post__titulo"><a href="<?= e($urlPost) ?>"><?= e($b['titulo']) ?></a></h2>
      <?php else: ?>
        <h1 class="hub-post__titulo"><?= e($b['titulo']) ?></h1>
      <?php endif; ?>

      <?php if (!empty($b['descricao'])): ?>
        <p class="hub-post__descricao <?= $modoPost === 'feed' ? 'hub-post__descricao--curta' : '' ?>"><?= e($b['descricao']) ?></p>
      <?php endif; ?>
    </div>

    <dl class="hub-ficha">
      <div class="hub-ficha__item">
        <dt><?= hub_icone('matriz') ?> Matriz</dt>
        <dd><?= (int) $b['largura'] ?> × <?= (int) $b['altura'] ?> pontos</dd>
      </div>
      <div class="hub-ficha__item">
        <dt><?= hub_icone('pontos') ?> Total</dt>
        <dd><?= number_format((int) $b['total_pontos'], 0, ',', '.') ?> pontos</dd>
      </div>
      <?php if ($paleta !== []): ?>
        <div class="hub-ficha__item hub-ficha__item--paleta">
          <dt>Linhas</dt>
          <dd>
            <ul class="hub-paleta" aria-label="<?= count($paleta) ?> cores de linha">
              <?php foreach ($paleta as $cor): ?>
                <li style="--hub-cor-linha: <?= e($cor) ?>" title="<?= e($cor) ?>"><span class="visually-hidden"><?= e($cor) ?></span></li>
              <?php endforeach; ?>
            </ul>
          </dd>
        </div>
      <?php endif; ?>
    </dl>
    </div>

    <div class="hub-post__barra">
      <div class="hub-post__interacoes">
        <button
          class="hub-botao js-curtir"
          type="button"
          data-id="<?= $idPost ?>"
          aria-pressed="<?= $b['curtiu'] ? 'true' : 'false' ?>"
        >
          <?= hub_icone('coracao') ?>
          <span class="js-curtidas"><?= $totalCurtidas ?></span>
          <span class="visually-hidden">curtidas, curtir</span>
        </button>

        <button
          class="hub-botao js-abrir-tutorial"
          type="button"
          aria-expanded="<?= $modoPost === 'detalhe' ? 'true' : 'false' ?>"
          aria-controls="tutorial-<?= $idPost ?>"
        >
          <?= hub_icone('tutorial') ?>
          <span>Tutorial<?= $temTutorial ? '' : ' com IA' ?></span>
        </button>
      </div>

      <?php if ($eGerente || $podeExcluir): ?>
        <div class="hub-post__gestao">
          <?php if ($eGerente): ?>
            <form method="post" action="/gerente/hub/ocultar">
              <?= Csrf::campo() ?>
              <input type="hidden" name="id" value="<?= $idPost ?>">
              <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
              <button class="hub-botao hub-botao--discreto" type="submit">
                <?= hub_icone($b['oculto'] ? 'olho' : 'olho-fechado') ?>
                <span><?= $b['oculto'] ? 'Reexibir' : 'Ocultar' ?></span>
              </button>
            </form>
          <?php endif; ?>

          <?php if ($podeExcluir): ?>
            <form method="post" action="/bordado/excluir" data-confirmar="Excluir o bordado “<?= e($b['titulo']) ?>”? Essa ação não pode ser desfeita.">
              <?= Csrf::campo() ?>
              <input type="hidden" name="id" value="<?= $idPost ?>">
              <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
              <button class="hub-botao hub-botao--perigo" type="submit">
                <?= hub_icone('lixeira') ?>
                <span>Excluir</span>
              </button>
            </form>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <section class="hub-post__comentarios" aria-label="Comentários de <?= e($b['titulo']) ?>">
      <header class="hub-comentarios__cabecalho">
        <h3 class="hub-comentarios__titulo">Comentários<?php if ($totalComentarios > 0): ?> <span class="hub-comentarios__total"><?= $totalComentarios ?></span><?php endif; ?></h3>
        <?php if ($modoPost === 'feed' && $totalComentarios > count($comentariosVisiveis)): ?>
          <a class="hub-post__ver-todos" href="<?= e($urlPost) ?>#comentarios">Ver todos</a>
        <?php endif; ?>
      </header>

      <?php if ($comentariosVisiveis === [] && $modoPost === 'detalhe'): ?>
        <p class="hub-post__aviso">Ainda não há comentários. Seja a primeira pessoa a comentar.</p>
      <?php endif; ?>

      <?php if ($comentariosVisiveis !== []): ?>
        <ul class="hub-comentarios" <?= $modoPost === 'detalhe' ? 'id="comentarios"' : '' ?>>
          <?php foreach ($comentariosVisiveis as $comentario): ?>
            <li class="hub-comentario <?= $comentario['oculto'] ? 'hub-comentario--oculto' : '' ?>">
              <?= hub_avatar((string) $comentario['autor'], 'sm') ?>
              <div class="hub-comentario__corpo">
                <p class="hub-comentario__texto"><strong><?= e($comentario['autor']) ?></strong> <?= e($comentario['texto']) ?></p>
                <div class="hub-comentario__rodape">
                  <time datetime="<?= e($comentario['criado_em']) ?>" title="<?= e(data_br($comentario['criado_em'])) ?>">
                    <?= e(hub_tempo($comentario['criado_em'])) ?>
                  </time>

                  <?php if ($comentario['oculto']): ?>
                    <span class="badge badge--alerta">Oculto</span>
                  <?php endif; ?>

                  <?php if (Auth::id() === (int) $comentario['usuario_id']): ?>
                    <form method="post" action="/hub/comentario/excluir" data-confirmar="Excluir este comentário?">
                      <?= Csrf::campo() ?>
                      <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">
                      <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
                      <button class="hub-botao hub-botao--icone hub-botao--perigo" type="submit"
                              aria-label="Excluir comentário" title="Excluir comentário">
                        <?= hub_icone('lixeira') ?>
                      </button>
                    </form>
                  <?php endif; ?>

                  <?php if ($eGerente): ?>
                    <?php $rotuloOcultar = $comentario['oculto'] ? 'Reexibir comentário' : 'Ocultar comentário'; ?>
                    <form method="post" action="/gerente/hub/comentario/ocultar">
                      <?= Csrf::campo() ?>
                      <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">
                      <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
                      <button class="hub-botao hub-botao--icone hub-botao--discreto" type="submit"
                              aria-label="<?= e($rotuloOcultar) ?>" title="<?= e($rotuloOcultar) ?>">
                        <?= hub_icone($comentario['oculto'] ? 'olho' : 'olho-fechado') ?>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if ($logado): ?>
        <form class="hub-comentar" method="post" action="/hub/comentar">
          <?= Csrf::campo() ?>
          <input type="hidden" name="bordado_id" value="<?= $idPost ?>">
          <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
          <label class="visually-hidden" for="comentar-<?= $idPost ?>">Comentar em <?= e($b['titulo']) ?></label>
          <input
            class="text-field hub-comentar__campo"
            id="comentar-<?= $idPost ?>"
            name="texto"
            type="text"
            maxlength="280"
            placeholder="Escreva um comentário..."
            autocomplete="off"
            required
          >
          <button class="btn btn--primary btn--sm" type="submit">Comentar</button>
        </form>
      <?php else: ?>
        <p class="hub-post__aviso"><a href="/login">Entre</a> para curtir e comentar.</p>
      <?php endif; ?>
  </section>
</article>

<?php if ($modoPost === 'detalhe'): ?>
  <section class="hub-tutorial-card" id="tutorial-<?= $idPost ?>" aria-label="Tutorial do bordado">
    <?php if ($logado || $temTutorial): ?>
      <?php Visao::parcial('tutorial', ['bordado' => $b]); ?>
    <?php else: ?>
      <p class="hub-post__aviso"><a href="/login">Entre</a> para gerar o tutorial deste bordado com IA.</p>
    <?php endif; ?>
  </section>
<?php else: ?>
  <div class="hub-modal" id="tutorial-<?= $idPost ?>" role="dialog" aria-modal="true" aria-label="Tutorial do bordado" hidden>
    <div class="hub-modal__fundo" data-hub-fechar></div>
    <div class="hub-modal__caixa">
      <button type="button" class="hub-modal__fechar" data-hub-fechar aria-label="Fechar tutorial">
        <svg class="hub-icone" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m6 6 12 12M18 6 6 18"/></svg>
      </button>
      <?php if ($logado || $temTutorial): ?>
        <?php Visao::parcial('tutorial', ['bordado' => $b]); ?>
      <?php else: ?>
        <p class="hub-post__aviso"><a href="/login">Entre</a> para gerar o tutorial deste bordado com IA.</p>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
