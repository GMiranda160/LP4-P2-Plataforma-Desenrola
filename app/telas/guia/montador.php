<?php
/**
 * Guia: como o montador é montado.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Popup em formato de deck: 9 slides, um por vez, com navegação por setas,
 * botões, pontos e teclado. Explica o caminho do clique na tela GET /montador
 * até a linha no banco: a trilha de pontos, as quatro etapas, o envio do
 * formulário e a transação. O que a home já explica (rotas, CSRF e o render
 * pela Visao) fica no guia da home; aqui só o que é do montador.
 * Segue os fatos do código; se o fluxo mudar, atualize aqui.
 */
?>
<div class="guia guia--deck guia--montador" id="guia-montador" role="dialog" aria-modal="true" aria-labelledby="guia-montador-titulo" hidden data-guia-deck>
  <div class="guia__dialog guia__deck-frame">

    <header class="guia__deck-head">
      <div>
        <p class="guia__eyebrow">Guia · Montar bordado</p>
        <h2 class="guia__deck-title" id="guia-montador-titulo">Como o montador funciona</h2>
      </div>
      <div class="guia__deck-meta">
        <span class="guia__contador" data-guia-contador aria-hidden="true">1 / 9</span>
        <button class="guia__fechar" type="button" data-guia-fechar aria-label="Fechar guia">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </header>

    <div class="guia__trilho">

      <!-- 1 CAPA ------------------------------------------------------- -->
      <section class="guia__slide is-active" role="group" aria-roledescription="slide" aria-label="1 de 9: capa">
        <div class="guia__capa">
          <div class="guia__capa-texto">
            <p class="guia__eyebrow guia__anim" style="--d:0ms">Guia da plataforma</p>
            <h3 class="guia__capa-h"><span class="guia__anim" style="--d:60ms">Do primeiro clique</span>
              <span class="guia__anim guia__capa-h-sub" style="--d:160ms">ao bordado na sua peça</span></h3>
            <p class="guia__lede guia__anim" style="--d:240ms">
              Escolha a matriz ou converta uma imagem, vista a peça, posicione o bordado e publique.
              Nada é gravado até o Publicar, e ainda assim a tela conta o que está acontecendo.
            </p>
            <ul class="guia__chips guia__anim" style="--d:320ms">
              <li>GET /montador</li>
              <li>logado</li>
              <li>4 etapas</li>
              <li>transação</li>
            </ul>
            <p class="guia__dica guia__anim" style="--d:400ms">Use as setas, os botões ou os pontos abaixo. ESC fecha.</p>
          </div>
          <div class="guia__capa-arte guia__anim" style="--d:200ms" aria-hidden="true">
            <svg class="mc-cena" viewBox="0 0 244 192" fill="none">
              <path class="gl-chao" d="M14 176 H230"/>

              <circle class="mc-aro" pathLength="100" cx="122" cy="74" r="54"/>
              <circle class="mc-aro-interno" cx="122" cy="74" r="41"/>

              <path class="mc-coracao" pathLength="100" d="M122 102c-15-11-25-20-25-33 0-8.4 6.2-14 13.6-14 5 0 9.4 3 11.4 7.4 2-4.4 6.4-7.4 11.4-7.4 7.4 0 13.6 5.6 13.6 14 0 13-10 22-25 33z"/>

              <g class="mc-pontos">
                <g class="mc-ponto" style="--d:120ms" transform="translate(108 47)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:220ms" transform="translate(136 47)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:320ms" transform="translate(98 60)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:420ms" transform="translate(122 56)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:520ms" transform="translate(146 60)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:620ms" transform="translate(98 74)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:720ms" transform="translate(146 74)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:820ms" transform="translate(110 88)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:920ms" transform="translate(134 88)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:1020ms" transform="translate(122 99)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
              </g>

              <line class="mc-agulha" x1="192" y1="2" x2="145" y2="57"/>
              <circle class="mc-agulha-olho" cx="192" cy="2" r="2.6"/>
              <path class="mc-fio" pathLength="100" d="M192 2c8-6 14 3 22-3"/>

              <g class="mc-trilha">
                <line class="mc-trilha-base" x1="42" y1="148" x2="202" y2="148"/>
                <circle class="mc-passo mc-passo--ativo" cx="50" cy="148" r="3.6"/>
                <circle class="mc-passo" cx="100" cy="148" r="3.6"/>
                <circle class="mc-passo" cx="150" cy="148" r="3.6"/>
                <circle class="mc-passo" cx="202" cy="148" r="3.6"/>
              </g>
            </svg>
          </div>
        </div>
      </section>

      <!-- 2 VISÃO GERAL -------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="2 de 9: três portas, a mesma tela">
        <p class="guia__eyebrow">Visão geral · Os três modos</p>
        <h3 class="guia__h">Uma tela, três fins</h3>
        <p class="guia__lede">O montador é a mesma tela para todo mundo. O que muda é a <code>?modo=</code>: para onde vai o bordado no final.</p>

        <div class="atos">
          <article class="ato guia__anim" style="--d:60ms">
            <div class="ato__palco">
              <span class="ato__knot">1</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><circle class="est2-desenha" pathLength="100" cx="9.2" cy="8.4" r="3"/><path class="est2-desenha est2-d2" pathLength="100" d="M3.9 19.5c.7-3.6 2.6-5.4 5.3-5.4s4.6 1.8 5.3 5.4"/><circle class="est2-desenha est2-d2" pathLength="100" cx="16.8" cy="9.5" r="2.3"/><path class="est2-desenha est2-d2" pathLength="100" d="M15.4 14c2.5.3 4 1.9 4.4 5"/></svg></span>
            </div>
            <small class="ato__fala">Porta 1 · Qualquer logado</small>
            <h4 class="ato__titulo">Hub</h4>
            <code class="ato__rota">/montador</code>
            <ul class="ato__linhas">
              <li>matriz, peça, posição e nome</li>
              <li>o POST grava <code>tipo 'hub'</code></li>
              <li>o bordado abre em <code>/hub/bordado?id=</code></li>
            </ul>
            <span class="ato__selo">INSERT em <code>bordados</code></span>
          </article>

          <span class="ato__seta guia__anim" style="--d:150ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:240ms">
            <div class="ato__palco">
              <span class="ato__knot">2</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="est2-desenha" pathLength="100" d="M4 9.2l1.5-4.7h13L20 9.2"/><path class="est2-desenha" pathLength="100" d="M4 9.2h16"/><path class="est2-desenha est2-d2" pathLength="100" d="M5 9.2v10.3h14V9.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.7 19.5v-4.6h4.6v4.6"/></svg></span>
            </div>
            <small class="ato__fala">Porta 2 · Exclusiva da gerente</small>
            <h4 class="ato__titulo">Combinação</h4>
            <code class="ato__rota">/montador?modo=produto</code>
            <ul class="ato__linhas">
              <li>o bordado nasce <code>tipo 'produto'</code></li>
              <li>o produto não nasce aqui: nome, preço e foto ficam na gerência</li>
              <li>a tela da combinação chega pré-preenchida</li>
            </ul>
            <span class="ato__selo">INSERT em <code>bordados</code> · sem produto</span>
          </article>

          <span class="ato__seta guia__anim" style="--d:330ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:420ms">
            <div class="ato__palco">
              <span class="ato__knot">3</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="est2-desenha" pathLength="100" d="M12 3v4M12 17v4M3 12h4M17 12h4"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="4.2"/></svg></span>
            </div>
            <small class="ato__fala">Porta 3 · Exclusiva da gerente</small>
            <h4 class="ato__titulo">Matriz</h4>
            <code class="ato__rota">/montador?modo=matriz</code>
            <ul class="ato__linhas">
              <li>só as etapas Matriz e Nome</li>
              <li>nenhuma peça, posição ou bordado</li>
              <li>a gerente volta na montagem com a matriz marcada</li>
            </ul>
            <span class="ato__selo">INSERT só em <code>matrizes</code></span>
          </article>
        </div>

        <p class="guia__nota"><span><b>Quem não é gerente</b> no modo produto ou matriz leva o flash de erro e volta para <code>/montador</code>; um modo fora da whitelist vira hub. A checagem é dupla: no controlador (GET) e no serviço (POST).</span></p>
      </section>

      <!-- 3 A TRILHA -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="3 de 9: a linha do tempo bordada, por dentro">
        <p class="guia__eyebrow">A tela · A linha do tempo bordada</p>
        <h3 class="guia__h">A trilha de pontos, por dentro</h3>
        <p class="guia__lede">Cada passo é um botão com um check de costura; a trilha pontilhada se costura até onde você chegou.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 12h7M13.5 9.5L16 12l-2.5 2.5"/></svg></span>
            <code>--trilha: passoAtual / (total - 1)</code>
            <p>O preenchimento da trilha pontilhada é uma <b>fração</b>: 0 no começo, 1 no fim. O CSS usa <code>width: calc((100% - var(--bola)) * var(--trilha))</code> com meio segundo de costura.</p>
            <span class="pipeline__chips"><code>::before rose-300</code><code>::after vinho</code><code>transition .5s</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M6.5 12.6l3.8 3.8L17.5 8"/></svg></span>
            <code>is-active · is-done · disabled</code>
            <p>Só a atual fica <b>is-active</b>; as anteriores ficam <b>is-done</b> e o check da bolinha se desenha (<code>stroke-dashoffset</code> 100 até 0). Os passos futuros ficam <b>disabled</b>: pular é ignorado, e o clique só navega para trás.</p>
            <span class="pipeline__chips"><code>aria-current="step"</code><code>pathLength="100"</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M3 8h18M3 12h12M3 16h9"/></svg></span>
            <code>.montador__barra</code>
            <p>A <b>barra fixa</b> no rodapé traz o progresso (Passo N de T e o nome da etapa) e as ações. <b>Avançar</b> vira <b>Publicar no Hub</b>, <b>Cadastrar matriz</b> ou <b>Salvar combinação</b> na última etapa; <b>Voltar</b> some no primeiro passo; <b>Baixar imagem</b> só aparece no modo não matriz.</p>
            <span class="pipeline__chips"><code>form="form-montador"</code><code>z-index 60</code><code>blur(12px)</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 6v6l4 2.5"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="8.5"/></svg></span>
            <code>novalidate + passoValido()</code>
            <p>A validação do navegador é desligada no <code>form</code>. Quem manda é o <code>passoValido()</code> do montador.js: ele confere a etapa antes de avançar e segura o envio na última; o servidor confere tudo de novo depois.</p>
            <span class="pipeline__chips"><code>role="alert"</code><code>aria-live="polite"</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>No modo matriz a trilha é de 2 passos</b> (Matriz e Nome); os painéis de peça e posição nem chegam ao HTML.</span></p>
      </section>

      <!-- 4 ETAPA 1 -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="4 de 9: etapa 1, a matriz">
        <p class="guia__eyebrow">Etapa 1 · Matriz</p>
        <h3 class="guia__h">O bordado começa na matriz</h3>
        <p class="guia__lede">Duas origens para o mesmo destino: a matriz pronta do ateliê, ou a sua imagem convertida em pontos aqui mesmo.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">FONTE 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.8" rx="7.4" ry="2.8"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.6 5.8v12.4c0 1.6 3.3 2.8 7.4 2.8s7.4-1.2 7.4-2.8V5.8"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.6 12c0 1.6 3.3 2.8 7.4 2.8s7.4-1.2 7.4-2.8"/></svg></span>
            <code>MatrizDados::listarTodas()</code>
            <p>O GET carrega <b>todas</b> as matrizes (visibilidade geral, por categoria e nome). O SVG entra por <code>svg_data_uri()</code>: caminho vira URL pública; o SVG legado vira data URI base64. <code>?matriz=ID</code> chega pré-marcada.</p>
            <span class="pipeline__chips"><code>COALESCE(svg_path, svg)</code><code>visibilidade geral</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">FONTE 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="3.5" y="3.5" width="17" height="17" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 12h7M12 8.5v7"/></svg></span>
            <code>conversor.js</code>
            <p>O canvas reduz a imagem pelo lado maior (<b>256, 512 ou 1024</b> pontos), alfa abaixo de 128 vira vazio e a paleta sai por <b>median cut</b>. Uma limpeza tira pontos soltos: a maioria em volta precisa de <b>5 de 8 votos</b>. Sem dithering.</p>
            <span class="pipeline__chips"><code>5 MB</code><code>4/8/16 cores</code><code>distância euclidiana</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">A GRADE</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg></span>
            <code>grade_json</code>
            <p>A grade viaja como <code>{largura, altura, paleta, pontos}</code>, um caractere por ponto (<code>.</code> e hex). O GradeServico confere tudo: lado entre 8 e 1024, no máximo 16 cores, 1 MB + 4 KB no total. O SVG é montado só no servidor.</p>
            <span class="pipeline__chips"><code>GradeServico::validar</code><code>gerarSvg()</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">O CAROUSEL</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="2.5" y="7.5" width="7" height="9" rx="1.4"/><rect class="est2-desenha est2-d2" pathLength="100" x="11.5" y="7.5" width="7" height="9" rx="1.4"/><path class="est2-desenha est2-d2" pathLength="100" d="M22 7.5v9"/></svg></span>
            <code>.carousel</code>
            <p>As matrizes ficam num <b>carrossel horizontal</b> de 158 px com snap e bordas desbotadas; as setas viram disabled no início e no fim, e em telas de toque o trilho é quem manda. A cada escolha, o resumo acende a prévia.</p>
            <span class="pipeline__chips"><code>scroll-snap</code><code>is-inicio / is-fim</code><code>pointer: coarse</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Com a imagem convertida</b>, a etapa Publicar ganha o bloco "Matriz nova" (nome e descrição); a matriz é criada junto com o bordado, na mesma transação.</span></p>
      </section>

      <!-- 5 ETAPA 2 -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="5 de 9: etapa 2, a peça">
        <p class="guia__eyebrow">Etapa 2 · Peça</p>
        <h3 class="guia__h">A peça veste o bordado</h3>
        <p class="guia__lede">Escolha uma peça base com foto de verdade, ou envie a foto da sua peça. A tinta só existe na peça base.</p>

        <div class="mc-opcoes">
          <article class="mc-opcao guia__anim" style="--d:60ms">
            <span class="mc-opcao__tag">Caminho A</span>
            <h4 class="mc-opcao__titulo">Peça base (foto real)</h4>
            <ul class="mc-opcao__linhas">
              <li>peças <b>ativas</b> vindas de <code>PecaServico::ativas()</code></li>
              <li>a foto fica no Storage e chega pela <code>StorageServico::urlPublica()</code></li>
              <li>a cor são 8 presets de tecido, mais a cor livre (<code>#rrggbb</code>)</li>
              <li>a tinta é um <code>div</code> multiply, com máscara feita da própria foto da peça</li>
              <li>a peça precisa estar ativa: inexistente recusa a gravação</li>
              <li>no tecido branco a tinta aparece; no branco puro, ela some</li>
            </ul>
            <div class="mc-opcao__visual" aria-hidden="true">
              <span class="mc-swatches">
                <i class="mc-swatch" style="--swatch:#f3ded9"></i>
                <i class="mc-swatch" style="--swatch:#ffffff"></i>
                <i class="mc-swatch" style="--swatch:#2e2321"></i>
                <i class="mc-swatch" style="--swatch:#691818"></i>
                <i class="mc-swatch" style="--swatch:#3f5a45"></i>
                <i class="mc-swatch" style="--swatch:#2f4a6b"></i>
                <i class="mc-swatch" style="--swatch:#d9c9b8"></i>
                <i class="mc-swatch" style="--swatch:#b7a6d9"></i>
                <i class="mc-swatch mc-swatch--livre"></i>
              </span>
              <small>8 tons + cor livre</small>
            </div>
            <span class="mc-opcao__selo"><code>peca_base=ID</code> · <code>cor_peca</code></span>
          </article>

          <article class="mc-opcao mc-opcao--foto guia__anim" style="--d:180ms">
            <span class="mc-opcao__tag">Caminho B</span>
            <h4 class="mc-opcao__titulo">Foto da sua peça</h4>
            <ul class="mc-opcao__linhas">
              <li>o enquadrador abre em <b>4:5</b> (1280×1600, <b>10 MB</b>)</li>
              <li><b>Ctrl+V</b>, arraste, arquivo ou link (proxy <code>/montador/imagem</code>)</li>
              <li>moldura com zoom 1x a 4x: roda, setas, Inteira/Preencher</li>
              <li>no envio, a foto vai a 1600 px e vira o rádio <code>peca_base=foto</code></li>
              <li>depois de enviada, o botão vira "Trocar ou reenquadrar a foto"</li>
              <li>as cores travam: multiply não faz sentido em foto</li>
            </ul>
            <div class="mc-opcao__visual" aria-hidden="true">
              <svg class="mc-moldura" viewBox="0 0 34 42">
                <rect class="mc-moldura-quadro" x="4" y="3.5" width="26" height="34.5" rx="2"/>
                <path class="mc-moldura-foto" d="M10 27l5-6.5 4 5 3.5-4.5L26 27"/>
                <circle class="mc-moldura-foto" cx="13.5" cy="12.5" r="2.6"/>
                <path class="mc-moldura-canto" d="M1.5 10V4a2.5 2.5 0 0 1 2.5-2.5h6"/>
                <path class="mc-moldura-canto" d="M32.5 10V4a2.5 2.5 0 0 0-2.5-2.5h-6"/>
                <path class="mc-moldura-canto" d="M1.5 31.5V37a2.5 2.5 0 0 0 2.5 2.5h6"/>
                <path class="mc-moldura-canto" d="M32.5 31.5V37a2.5 2.5 0 0 1-2.5 2.5h-6"/>
              </svg>
              <small>4:5 · 1280×1600 · q0.88</small>
            </div>
            <span class="mc-opcao__selo"><code>peca_base=foto</code> · <code>foto_peca</code></span>
          </article>
        </div>

        <p class="guia__nota"><span><b>No envio</b>, a foto sai a 1600 px, em JPEG q0.85, com fundo branco; no servidor ela é conferida pelos bytes reais (<code>getimagesizefromstring</code>) e o limite é <b>4 MB</b> decodificados.</span></p>
      </section>

      <!-- 6 ETAPA 3 -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="6 de 9: etapa 3, a posição">
        <p class="guia__eyebrow">Etapa 3 · Posição</p>
        <h3 class="guia__h">Posição é medir, arrastar e girar</h3>
        <p class="guia__lede">A caixa diz onde o bordado vai ficar na peça. Tudo que você faz aqui vira quatro números no banco.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3v18M3 12h18"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="4.6"/></svg></span>
            <code>.montador__caixa · mover</code>
            <p>Pointer Events: arraste o meio da caixa para mover; <b>toque fora dela recentra o bordado</b> no ponto tocado. Os valores saem em porcentagem, já travados entre 0 e 100.</p>
            <span class="pipeline__chips"><code>aspect-ratio 4/5</code><code>touch-action none</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M5 5l6 6M5 19V5h14v14H5z"/><rect class="est2-desenha est2-d2" pathLength="100" x="9.5" y="9.5" width="5" height="5" rx="0.8"/></svg></span>
            <code>.montador__alca</code>
            <p>Um canto puxa o <b>tamanho</b> (10 a 90, mantendo a proporção); a <b>alça de cima</b> gira, com snap de 45° que vira 15° por vez com Shift (limiar de 4°). Em telas de toque, as alças crescem.</p>
            <span class="pipeline__chips"><code>--alca 12 px</code><code>snap 45° / 15°</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="2.5" y="7" width="19" height="10" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M7.5 10.5h.01M12 10.5h.01M16.5 10.5h.01M7.5 13.5h9"/></svg></span>
            <code>teclaNaCaixa</code>
            <p><b>Setas</b> movem (1, com Shift 5); <b>+ e −</b> mudam o tamanho; <b>[ e ]</b> giram 5°; <b>0</b> endireita. "Centralizar e endireitar" volta a 50% × 50%, 45 e 0°.</p>
            <span class="pipeline__chips"><code>Shift ×5</code><code>0 = reto</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M6 4h12v16H6z"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.5 8h5M9.5 12h5M9.5 16h2.5"/></svg></span>
            <code>pos_x · pos_y · escala · rotacao</code>
            <p>São campos ocultos: <b>nada é salvo</b> ainda. No envio, o servidor valida inteiros (0-100, 10-90, ±180) e a tabela repete o limite com CHECK.</p>
            <span class="pipeline__chips"><code>CHECK</code><code>clamp no JS</code><code>±180°</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>O painel "Seu bordado"</b> espelha peça, tinta e matriz ao vivo a cada gesto, com as mesmas regras da área; nada é gravado ainda.</span></p>
      </section>

      <!-- 7 ETAPA 4 -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="7 de 9: etapa 4, publicar">
        <p class="guia__eyebrow">Etapa 4 · Publicar</p>
        <h3 class="guia__h">O POST fecha a costura</h3>
        <p class="guia__lede">Na última etapa o Avançar vira Publicar. Depois do envio, o Post/Redirect/Get devolve você com um flash de confirmação.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect class="est2-desenha est2-d2" pathLength="100" x="9" y="3" width="6" height="4" rx="1.2"/></svg></span>
            <code>titulo · descricao</code>
            <p>Título até 120, descrição até 500, título obrigatório. Com imagem convertida, o bloco <b>Matriz nova</b> aparece e passa a exigir o nome (120) e a descrição (500). Se falhar, só esses quatro textos voltam ao formulário.</p>
            <span class="pipeline__chips"><code>maxlength 120</code><code>velhos</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="4" y="10.5" width="16" height="9" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/></svg></span>
            <code>Csrf::campo()</code>
            <p>O token entra no formulário; o <b>roteador</b> valida <code>_csrf</code> antes do controlador, em tempo constante. Quem reprova leva <b>403</b> e o montador nem é chamado.</p>
            <span class="pipeline__chips"><code>hash_equals</code><code>403</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.6" rx="7.6" ry="2.9"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 12.2l2.3 2.3 4.7-5.2"/></svg></span>
            <code>MontadorServico::salvar()</code>
            <p><b>20 bordados por hora</b> por autor; uploads primeiro (SVG e grade em <code>matrizes/</code>, foto em <code>fotos/</code>, apagando tudo se falhar); depois <b>uma transação</b>: INSERT em matrizes (se veio grade) e em bordados, com <code>RETURNING id</code>. O banco recusando (23514) vira mensagem amigável.</p>
            <span class="pipeline__chips"><code>transaction</code><code>RETURNING id</code><code>23514</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 12h13"/><path class="est2-desenha est2-d2" pathLength="100" d="M13 6l6 6-6 6"/></svg></span>
            <code>Post / Redirect / Get</code>
            <p>Sucesso redireciona conforme o modo: Hub vai para <code>/hub/bordado?id=ID</code>; combinação, para <code>/gerente/produtos/form?bordado=ID</code>; matriz, de volta para <code>/montador?modo=produto&amp;matriz=ID</code>.</p>
            <span class="pipeline__chips"><code>flash</code><code>redirect</code><code>destino</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Com foto no envio</b>, o <code>foto_peca</code> viaja como data URL e o JavaScript reduz antes de enviar; por isso o servidor aceita no máximo 4 MB decodificados, e guarda só o caminho no banco.</span></p>
      </section>

      <!-- 8 SQL -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="8 de 9: o que o servidor grava">
        <p class="guia__eyebrow">Banco · O que é gravado</p>
        <h3 class="guia__h">O que o servidor grava, na ordem</h3>
        <p class="guia__lede">Tudo em parâmetro, numa única transação. A peça e a matriz continuam sendo do bordado: o produto herda delas.</p>

        <div class="ref__papel sqlp2 guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">MontadorServico::salvar()</span><span class="ref__tag">SQL 08 · 5 comandos · 1 transação</span></div>
          <div class="sqlp2__tabela" role="table" aria-label="Cada comando do SQL e o que ele faz">

            <div class="sqlp2__head" role="row" aria-hidden="true">
              <span role="columnheader">Comando</span>
              <span></span>
              <span role="columnheader">O que faz</span>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">matrizes · o carrossel</small>
                <code class="sqlp2__sql">SELECT id, nome, categoria, COALESCE(svg_path, svg) AS svg, largura, altura, total_pontos FROM matrizes ORDER BY categoria, nome</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">A <b>lista das matrizes</b>, visível a todo logado. <b>COALESCE</b> resolve os dois mundos: o caminho do Storage ou o SVG cru legado.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">matrizes · se a imagem veio</small>
                <code class="sqlp2__sql">INSERT INTO matrizes (nome, descricao, categoria, svg_path, grade_path, paleta, largura, altura, total_pontos, tempo_medio_min, padrao, autor_id) VALUES (...) RETURNING id</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">A <b>matriz nova</b> (só no envio da imagem). Categoria literal <code>'Geral'</code>, tempo médio fixo em 30, <code>padrao</code> verdadeiro para a gerente; a paleta vai como <code>jsonb</code>.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">bordados · a linha principal</small>
                <code class="sqlp2__sql">INSERT INTO bordados (titulo, descricao, autor_id, matriz_id, categoria_id, cor_peca, pos_x, pos_y, escala, rotacao, peca_id, foto_path, tipo) VALUES (...) RETURNING id</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">O <b>bordado</b>. <code>tipo</code> é <code>'hub'</code> ou <code>'produto'</code>, nunca matriz; <code>peca_id</code> fica nulo quando a foto é sua, <code>foto_path</code> fica nulo na peça base.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">categorias · a peça precisa existir</small>
                <code class="sqlp2__sql">SELECT 1 FROM categorias WHERE id = :id</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card"><b>categoria_id</b> é conferido antes de gravar; a peça escolhida também precisa estar <b>ativa</b> (<code>PecaDados::buscar</code> + <code>filter_var</code>).</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">bordados · o freio</small>
                <code class="sqlp2__sql">SELECT count(*) FROM bordados WHERE autor_id = :autor AND criado_em &gt; now() - interval '1 hour'</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card"><b>20 por hora</b> por autor (qualquer tipo). Passou disso, a gravação nem tenta; a matriz fica de fora do freio.</p>
            </div>

          </div>
        </div>

        <ul class="guia__chips guia__chips--rumo guia__anim" style="--d:240ms">
          <li>CHECK: pos 0-100 · escala 10-90 · rotacao ±180 · tipo IN ('hub','produto')</li>
          <li>matriz_id ON DELETE RESTRICT: a matriz não some enquanto o bordado existir</li>
          <li>banco em pgsql no Supabase, pelo pooler da porta 6543</li>
        </ul>
      </section>

      <!-- 9 ANEXO -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="9 de 9: a árvore do montador">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A árvore do montador</h3>
        <p class="guia__lede">Onze arquivos no caminho: da rota ao SVG no Storage, sem passar por nenhuma outra tela.</p>

        <div class="ref__papel arvore guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">$ tree app | grep -E "Montador|Grade|Enquadrador|conversor"</span><span class="ref__tag">REF 09 · 11 arquivos · 4 camadas</span></div>
          <div class="arvore__cols">

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/config/</code><small>as rotas</small></div>
                <p class="arvore__file"><code>rotas.php</code><span class="arvore__lider"></span><span class="arvore__role">3 rotas · acesso login</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/controladores/</code><small>a porta</small></div>
                <p class="arvore__file"><code>MontadorControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">index · imagem · salvar</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/processamento/</code><small>as regras</small></div>
                <p class="arvore__file"><code>MontadorServico.php</code><span class="arvore__lider"></span><span class="arvore__role">valida · sobe · transação</span></p>
                <p class="arvore__file"><code>GradeServico.php</code><span class="arvore__lider"></span><span class="arvore__role">confere a grade · gera o SVG</span></p>
                <p class="arvore__file"><code>ImagemRemotaServico.php</code><span class="arvore__lider"></span><span class="arvore__role">proxy anti-SSRF, 10 MB</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/dados/</code><small>o SQL</small></div>
                <p class="arvore__file"><code>MatrizDados.php</code><span class="arvore__lider"></span><span class="arvore__role">listarTodas · inserir</span></p>
                <p class="arvore__file"><code>BordadoDados.php</code><span class="arvore__lider"></span><span class="arvore__role">inserir · contarRecentes</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/montador/</code><small>as telas</small></div>
                <p class="arvore__file"><code>index.php</code><span class="arvore__lider"></span><span class="arvore__role">form · resumo · enquadrador</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>public/js/pages/</code><small>cada tela, o seu</small></div>
                <p class="arvore__file"><code>montador.js</code><span class="arvore__lider"></span><span class="arvore__role">etapas · resumo · gestos</span></p>
                <p class="arvore__file"><code>conversor.js</code><span class="arvore__lider"></span><span class="arvore__role">imagem vira pontos</span></p>
                <p class="arvore__file"><code>enquadrador.js</code><span class="arvore__lider"></span><span class="arvore__role">moldura 4:5 · zoom</span></p>
              </section>
            </div>

          </div>
        </div>

        <p class="guia__nota"><span><b>Quem entra é a rota; quem decide é o serviço; quem mostra é a tela.</b> O padrão se repete: comece pelo controlador para saber onde um dado nasce.</span></p>
      </section>

    </div><!-- /trilho -->

    <footer class="guia__deck-foot">
      <button class="guia__nav guia__nav--anterior" type="button" data-guia-nav="-1" aria-label="Slide anterior">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        <span>Anterior</span>
      </button>

      <div class="guia__progresso" aria-hidden="true"><span class="guia__progresso-fio" data-guia-progresso></span></div>

      <div class="guia__pontos" role="tablist" aria-label="Ir para o slide">
        <?php for ($i = 1; $i <= 9; $i++): ?>
          <button class="guia__ponto<?= $i === 1 ? ' is-active' : '' ?>" type="button" role="tab" data-guia-ponto="<?= $i - 1 ?>" aria-label="Slide <?= $i ?>" aria-selected="<?= $i === 1 ? 'true' : 'false' ?>"></button>
        <?php endfor; ?>
      </div>

      <button class="guia__nav guia__nav--proximo" type="button" data-guia-nav="1" aria-label="Próximo slide">
        <span>Próximo</span>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
      </button>
    </footer>

  </div>
</div>
