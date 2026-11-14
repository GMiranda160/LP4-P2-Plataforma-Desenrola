<?php
/**
 * Tela: conversor e montador
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Rota: GET /montador, com ?modo=matriz ou ?modo=produto (as duas só para a gerente)
 * Recebe: $titulo, $modo, $matrizes, $categorias, $pecas
 * Campos de $pecas: id, nome, categoria_id, imagem_path (caminho no Storage)
 *
 * Quatro etapas: Matriz, Peça, Posição e Publicar. No modo matriz são só duas (Matriz e Nome):
 * a matriz não tem peça nem posição; a combinação é montada depois, no modo produto.
 * Recebe também $matrizInicial (id da matriz já marcada na etapa 1, ou 0).
 * O progresso, a conversão de imagem e o arraste da matriz ficam no montador.js.
 * Sem JavaScript, os campos continuam no formulário, mas a conversão de imagem
 * e a navegação por etapas exigem o navegador.
 */

$modo = $modo ?? 'hub';
$matrizInicial = (int) ($matrizInicial ?? 0);
$etapas = $modo === 'matriz' ? ['Matriz', 'Nome'] : ['Matriz', 'Peça', 'Posição', 'Publicar'];
$matrizes = $matrizes ?? [];
$categorias = $categorias ?? [];
$antigos = Sessao::tirarVelhos();
$tituloAntigo = (string) ($antigos['titulo'] ?? '');
$descricaoAntiga = (string) ($antigos['descricao'] ?? '');

$textoBotao = [
    'hub' => 'Publicar no Hub',
    'matriz' => 'Cadastrar matriz',
    'produto' => 'Salvar combinação',
][$modo] ?? 'Publicar no Hub';

// Peças base com foto real: vêm da tabela pecas (cadastro em /gerente/pecas); a foto fica no Storage.
$pecas = $pecas ?? [];
$categoriaPersonalizados = 0;
foreach ($categorias as $c) {
    if (($c['slug'] ?? '') === 'personalizados') {
        $categoriaPersonalizados = (int) $c['id'];
    }
}

// Dados iniciais para o JavaScript. svg da matriz vai como data URI, nunca SVG cru.
$dadosIniciais = [
    'modo' => $modo,
    'matrizes' => array_map(fn (array $m): array => [
        'id' => (int) $m['id'],
        'nome' => (string) $m['nome'],
        'categoria' => (string) ($m['categoria'] ?? 'Geral'),
        'svg' => svg_data_uri((string) $m['svg']),
        'largura' => (int) $m['largura'],
        'altura' => (int) $m['altura'],
        'total_pontos' => (int) $m['total_pontos'],
    ], $matrizes),
    'categorias' => array_map(fn (array $c): array => [
        'id' => (int) $c['id'],
        'nome' => (string) $c['nome'],
        'slug' => (string) $c['slug'],
    ], $categorias),
];

$json = json_encode($dadosIniciais, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

$coresTecido = ['#f3ded9', '#ffffff', '#2e2321', '#691818', '#3f5a45', '#2f4a6b', '#d9c9b8', '#b7a6d9'];
?>
<div class="montador container section" data-montador>
  <script type="application/json" id="dados-montador"><?= $json ?></script>

  <ol class="stepper" role="list">
    <?php foreach ($etapas as $i => $nomeEtapa): ?>
      <li class="stepper__step<?= $i === 0 ? ' is-active' : '' ?>" data-step><span class="stepper__number"><?= $i + 1 ?></span> <?= e($nomeEtapa) ?></li>
    <?php endforeach; ?>
  </ol>

  <form id="form-montador" class="form" method="post" action="/montador/salvar" novalidate>
    <?= Csrf::campo() ?>
    <input type="hidden" name="modo" value="<?= e($modo) ?>">
    <input type="hidden" name="grade_json" data-grade-json value="">
    <input type="hidden" name="cor_peca" data-cor-peca value="#f3ded9">
    <input type="hidden" name="categoria_id" data-categoria value="1">
    <input type="hidden" name="pos_x" data-pos-x value="50">
    <input type="hidden" name="pos_y" data-pos-y value="50">
    <input type="hidden" name="escala" data-escala value="45">
    <input type="hidden" name="rotacao" data-rotacao value="0">
    <input type="hidden" name="peca_id" data-peca-id value="">
    <input type="hidden" name="foto_peca" data-foto-peca value="">

    <p class="montador__erro form__error" role="alert" hidden></p>

    <!-- Etapa 1: matriz existente ou imagem convertida -->
    <section class="montador__etapa is-active" data-etapa="matriz" aria-label="Escolha a matriz">
      <?php if ($modo === 'matriz'): ?>
        <h2 class="montador__titulo">1. Envie a imagem</h2>
        <p class="field-hint">A imagem é convertida em pontos aqui no navegador e vira uma matriz padrão do ateliê.</p>
      <?php else: ?>
        <h2 class="montador__titulo">1. Escolha a matriz</h2>
        <p class="field-hint">Escolha uma matriz existente ou envie uma imagem para converter em pontos.</p>
      <?php endif; ?>

      <?php if ($modo === 'matriz'): ?>
        <?php /* Matriz nova sempre nasce de uma imagem: as existentes não aparecem aqui. */ ?>
      <?php elseif ($matrizes): ?>
        <div class="matrix-grid" role="radiogroup" aria-label="Matrizes disponíveis">
          <?php foreach ($matrizes as $matriz): ?>
            <label class="matrix-card">
              <input type="radio" name="matriz_id" value="<?= (int) $matriz['id'] ?>"
                     <?= (int) $matriz['id'] === $matrizInicial ? 'checked' : '' ?>>
              <span class="matrix-card__img"><img src="<?= e(svg_data_uri((string) $matriz['svg'])) ?>" alt="<?= e($matriz['nome']) ?>" loading="lazy"></span>
              <span class="matrix-card__nome"><?= e($matriz['nome']) ?></span>
              <span class="matrix-card__meta"><?= (int) $matriz['largura'] ?>×<?= (int) $matriz['altura'] ?> · <?= (int) $matriz['total_pontos'] ?> pontos</span>
            </label>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="empty-state">Nenhuma matriz ainda. Envie uma imagem abaixo para criar a primeira.</p>
      <?php endif; ?>

      <?php if ($modo !== 'matriz'): ?>
        <p class="montador__ou"><span>ou envie uma imagem</span></p>
      <?php endif; ?>

      <div class="montador__conversor">
        <label class="montador__upload" for="arquivo">
          <input class="montador__arquivo" id="arquivo" type="file" accept="image/png,image/jpeg,image/webp">
          <span class="montador__upload-texto">Escolher imagem (PNG, JPEG ou WebP, até 5 MB)</span>
        </label>

        <div class="montador__opcoes">
          <label class="form__field" for="largura">Tamanho (lado maior)
            <select class="text-field montador__largura" id="largura">
              <option value="256" selected>256 pontos</option>
              <option value="512">512 pontos</option>
              <option value="1024">1024 pontos (mais lento)</option>
            </select>
          </label>
          <label class="form__field" for="cores">Cores
            <select class="text-field montador__cores" id="cores">
              <option value="4">4 cores</option>
              <option value="8" selected>8 cores</option>
              <option value="16">16 cores</option>
            </select>
          </label>
        </div>

        <canvas class="montador__grade" width="48" height="48" role="img" aria-label="Prévia da matriz convertida"></canvas>
        <p class="montador__status" aria-live="polite"></p>
        <button type="button" class="btn btn--ghost btn--sm montador__limpar">Limpar imagem</button>
      </div>

      <div class="step-nav">
        <span></span>
        <button type="button" class="btn btn--primary" data-avancar>Avançar</button>
      </div>
    </section>

    <!-- Etapa 2: peça e cor do tecido -->
    <?php if ($modo !== 'matriz'): ?>
    <section class="montador__etapa" data-etapa="peca" aria-label="Escolha a peça">
      <h2 class="montador__titulo">2. Escolha a peça</h2>
      <p class="field-hint">Escolha uma peça base com foto real ou envie a foto da sua peça.</p>

      <p class="montador__subtitulo">Peça base (foto real)</p>
      <div class="categorias-mini" role="radiogroup" aria-label="Peças base">
        <?php foreach ($pecas as $item): ?>
          <?php $url = StorageServico::urlPublica((string) $item['imagem_path']); ?>
          <label class="categoria-mini">
            <input type="radio" name="peca_base" value="<?= (int) $item['id'] ?>" data-foto="<?= e($url) ?>"
                   data-categoria-id="<?= (int) $item['categoria_id'] ?>">
            <span class="categoria-mini__img categoria-mini__img--foto"><img src="<?= e($url) ?>" alt="<?= e($item['nome']) ?>" loading="lazy"></span>
            <span class="categoria-mini__nome"><?= e($item['nome']) ?></span>
          </label>
        <?php endforeach; ?>
        <label class="categoria-mini">
          <input type="radio" name="peca_base" value="foto" data-categoria-id="<?= $categoriaPersonalizados ?>">
          <span class="categoria-mini__img categoria-mini__img--upload" aria-hidden="true">
            <img class="categoria-mini__foto-usuario" data-foto-miniatura alt="" hidden>
            <svg data-foto-icone viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </span>
          <span class="categoria-mini__nome">Minha foto</span>
        </label>
      </div>

      <button type="button" class="montador__upload montador__upload-foto" data-foto-linha data-foto-abrir hidden>
        <span class="montador__upload-texto" data-foto-abrir-texto>Escolher a foto da sua peça</span>
      </button>

      <!-- Popup da foto: origem (computador, arrastar, colar ou link) e enquadramento 4:5.
           Os campos não têm name: nada daqui vai no formulário; a foto pronta vai pelo montador.js. -->
      <dialog class="enquadrador" data-enquadrador aria-labelledby="enquadrador-titulo" aria-describedby="enquadrador-sub">
        <header class="enquadrador__cabeca">
          <div>
            <h2 class="enquadrador__titulo" id="enquadrador-titulo">Foto da sua peça</h2>
            <p class="enquadrador__sub" id="enquadrador-sub" data-enq-sub
               data-origem="Envie uma foto da peça, de frente e com boa luz."
               data-ajuste="Arraste para enquadrar a peça e use o zoom para aproximar ou afastar.">
              Envie uma foto da peça, de frente e com boa luz.
            </p>
          </div>
          <button type="button" class="enquadrador__fechar" data-enq-fechar aria-label="Fechar">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
          </button>
        </header>

        <div class="enquadrador__corpo">
          <div class="enquadrador__origem" data-enq-origem>
            <label class="enquadrador__soltar" data-enq-soltar>
              <input class="montador__arquivo" type="file" data-enq-arquivo
                     accept="image/png,image/jpeg,image/webp,.png,.jpg,.jpeg,.webp,.heic,.heif">
              <svg class="enquadrador__icone" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              <span class="enquadrador__soltar-titulo">Escolher do computador</span>
              <span class="enquadrador__dica">ou arraste a imagem para cá · PNG, JPEG ou WebP até 10 MB</span>
            </label>

            <div class="enquadrador__ou"><span>ou</span></div>

            <div class="enquadrador__campo">
              <label class="enquadrador__rotulo" for="enquadrador-url">Link de uma imagem da web</label>
              <div class="enquadrador__link">
                <input class="text-field" id="enquadrador-url" type="url" inputmode="url" data-enq-url
                       placeholder="https://exemplo.com/foto.jpg" autocomplete="off">
                <button type="button" class="btn btn--primary btn--sm" data-enq-carregar>Carregar</button>
              </div>
              <p class="enquadrador__dica">Na imagem do site, clique com o botão direito e use “Copiar endereço da imagem”. Ctrl+V também cola uma imagem copiada.</p>
            </div>
          </div>

          <div class="enquadrador__ajuste" data-enq-ajuste hidden>
            <div class="enquadrador__moldura" data-enq-moldura tabindex="0"
                 aria-label="Enquadramento da foto. Setas movem, mais e menos dão zoom, zero preenche a moldura.">
              <img class="enquadrador__imagem" data-enq-img alt="" draggable="false">
              <span class="enquadrador__grade" aria-hidden="true"></span>
            </div>
            <div class="enquadrador__zoom">
              <button type="button" class="enquadrador__zoom-botao" data-enq-inteira title="Foto inteira" aria-label="Mostrar a foto inteira">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M8 11h6M20 20l-4-4"/></svg>
              </button>
              <input type="range" min="0" max="1000" step="1" data-enq-zoom aria-label="Zoom">
              <button type="button" class="enquadrador__zoom-botao" data-enq-preencher title="Preencher a moldura" aria-label="Preencher a moldura">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M8 11h6M11 8v6M20 20l-4-4"/></svg>
              </button>
            </div>
          </div>

          <p class="enquadrador__erro" data-enq-erro role="alert" hidden></p>
        </div>

        <footer class="enquadrador__acoes">
          <button type="button" class="enquadrador__trocar" data-enq-trocar>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 0 1 15.5-6.2L21 8M21 3v5h-5M21 12a9 9 0 0 1-15.5 6.2L3 16M3 21v-5h5"/></svg>
            Trocar imagem
          </button>
          <button type="button" class="btn btn--ghost btn--sm" data-enq-fechar>Cancelar</button>
          <button type="button" class="btn btn--primary btn--sm" data-enq-usar>Usar esta foto</button>
        </footer>
      </dialog>
      <img class="montador__foto-preview" data-foto-preview alt="Prévia da foto da sua peça" hidden>
      <p class="field-hint montador__foto-nota" data-foto-nota hidden>
        A foto é reduzida neste navegador e guardada junto com o bordado quando você salvar.
      </p>

      <p class="montador__subtitulo">Cor da peça</p>
      <div class="chip-group montador__cores-tecido" role="group" aria-label="Cor da peça">
        <?php foreach ($coresTecido as $cor): ?>
          <button type="button" class="swatch" data-cor="<?= e($cor) ?>" style="--swatch: <?= e($cor) ?>" aria-pressed="<?= $cor === '#f3ded9' ? 'true' : 'false' ?>" aria-label="Cor <?= e($cor) ?>"></button>
        <?php endforeach; ?>
      </div>

      <div class="step-nav">
        <button type="button" class="btn btn--ghost" data-voltar>Voltar</button>
        <button type="button" class="btn btn--primary" data-avancar>Avançar</button>
      </div>
    </section>

    <!-- Etapa 3: posição e tamanho do bordado na peça -->
    <section class="montador__etapa" data-etapa="posicao" aria-label="Posicione o bordado">
      <h2 class="montador__titulo">3. Posicione o bordado</h2>
      <p class="field-hint">
        Arraste o bordado para mover. Puxe um canto para mudar o tamanho e a alça de cima para girar
        (segure Shift para girar de 15 em 15 graus).
      </p>

      <div class="montador__area" style="color: #f3ded9" role="application" aria-label="Área de posicionamento do bordado">
        <div class="montador__mockup" aria-hidden="true"></div>
        <div class="montador__tinta" data-tinta aria-hidden="true"></div>
        <div class="montador__caixa" data-caixa tabindex="0" role="group"
             aria-label="Bordado. Setas movem, mais e menos mudam o tamanho, colchetes giram, zero endireita.">
          <img class="montador__peca" alt="" draggable="false">
          <span class="montador__alca montador__alca--girar" data-alca="girar" aria-hidden="true"></span>
          <span class="montador__alca montador__alca--no" data-alca="redimensionar" aria-hidden="true"></span>
          <span class="montador__alca montador__alca--ne" data-alca="redimensionar" aria-hidden="true"></span>
          <span class="montador__alca montador__alca--so" data-alca="redimensionar" aria-hidden="true"></span>
          <span class="montador__alca montador__alca--se" data-alca="redimensionar" aria-hidden="true"></span>
        </div>
      </div>

      <div class="montador__controles">
        <p class="montador__posicao field-hint" aria-live="polite">Posição 50% × 50% · Tamanho 45 · Giro 0°</p>
        <button type="button" class="btn btn--ghost btn--sm" data-resetar>Centralizar e endireitar</button>
      </div>

      <div class="step-nav">
        <button type="button" class="btn btn--ghost" data-voltar>Voltar</button>
        <button type="button" class="btn btn--primary" data-avancar>Avançar</button>
      </div>
    </section>

    <!-- Etapa 4: título e envio -->
    <?php endif; ?>

    <section class="montador__etapa" data-etapa="publicar" aria-label="Dê um nome e publique">
      <h2 class="montador__titulo"><?= count($etapas) ?>. Quase lá</h2>
      <?php if ($modo === 'matriz'): ?>
        <p class="field-hint">Dê um nome à matriz. Depois de cadastrar, você segue direto para montar a combinação do produto com ela.</p>
      <?php else: ?>
        <p class="field-hint">Dê um nome ao seu bordado. É assim que ele aparece no Hub ou no mostruário.</p>
      <?php endif; ?>

      <div class="form__field">
        <label for="titulo">Título</label>
        <input class="text-field" id="titulo" name="titulo" data-titulo maxlength="120" required
               value="<?= e($tituloAntigo) ?>" placeholder="Ex.: Coração afetivo em toalha">
      </div>

      <div class="form__field">
        <label for="descricao">Descrição (opcional)</label>
        <textarea class="text-field" id="descricao" name="descricao" rows="3" maxlength="500"
                  placeholder="Conta rapidinho o que é, ou deixa em branco"><?= e($descricaoAntiga) ?></textarea>
      </div>

      <div class="step-nav">
        <button type="button" class="btn btn--ghost" data-voltar>Voltar</button>
        <span class="step-nav__fim">
          <?php if ($modo !== 'matriz'): ?>
            <button type="button" class="btn btn--ghost" data-baixar>Baixar imagem</button>
          <?php endif; ?>
          <button type="submit" class="btn btn--primary"><?= e($textoBotao) ?></button>
        </span>
      </div>
    </section>
  </form>
</div>

<noscript>
  <p class="empty-state container">Para converter uma imagem, ative o JavaScript. As outras etapas funcionam, mas a grade só pode ser gerada no navegador.</p>
</noscript>
