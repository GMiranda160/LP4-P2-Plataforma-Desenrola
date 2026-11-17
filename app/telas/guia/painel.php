<?php
/**
 * Guia: como o painel da gerente é montado.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Popup em formato de deck: 6 slides, um por vez, com navegação por setas,
 * botões, pontos e teclado. Explica o caminho do clique GET /gerente até os
 * oito cartões: o portão 'gerente' do roteador, o PainelControlador, a
 * tolerância do contar() e a moldura própria da área. O que a home já
 * explica (rotas, CSRF e o render pela Visao) fica no guia da home.
 * Segue os fatos do código; se o fluxo mudar, atualize aqui.
 */
?>
<div class="guia guia--deck guia--painel" id="guia-painel" role="dialog" aria-modal="true" aria-labelledby="guia-painel-titulo" hidden data-guia-deck>
  <div class="guia__dialog guia__deck-frame">

    <header class="guia__deck-head">
      <div>
        <p class="guia__eyebrow">Guia · Painel da gerente</p>
        <h2 class="guia__deck-title" id="guia-painel-titulo">Como o painel da gerente é montado</h2>
      </div>
      <div class="guia__deck-meta">
        <span class="guia__contador" data-guia-contador aria-hidden="true">1 / 6</span>
        <button class="guia__fechar" type="button" data-guia-fechar aria-label="Fechar guia">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </header>

    <div class="guia__trilho">

      <!-- 1 CAPA ------------------------------------------------------- -->
      <section class="guia__slide is-active" role="group" aria-roledescription="slide" aria-label="1 de 6: capa">
        <div class="guia__capa">
          <div class="guia__capa-texto">
            <p class="guia__eyebrow guia__anim" style="--d:0ms">Guia da plataforma</p>
            <h3 class="guia__capa-h"><span class="guia__anim" style="--d:60ms">O tamanho do ateliê</span>
              <span class="guia__anim guia__capa-h-sub" style="--d:160ms">em oito cartões</span></h3>
            <p class="guia__lede guia__anim" style="--d:240ms">
              A gerente abre, mede e decide: oito cartões ao vivo dizem onde o ateliê está hoje,
              e cada um leva direto para a sua área.
            </p>
            <ul class="guia__chips guia__anim" style="--d:320ms">
              <li>GET /gerente</li>
              <li>gerente</li>
              <li>8 cartões</li>
              <li>ao vivo</li>
            </ul>
            <p class="guia__dica guia__anim" style="--d:400ms">Use as setas, os botões ou os pontos abaixo. ESC fecha.</p>
          </div>
          <div class="guia__capa-arte guia__anim" style="--d:200ms" aria-hidden="true">
            <svg class="pc-cena" viewBox="0 0 244 158" fill="none">
              <path class="gl-chao" d="M14 150 H230"/>

              <path class="pc-arco" pathLength="100" d="M52 118 A70 70 0 0 1 192 118"/>

              <g class="mc-pontos">
                <g class="mc-ponto" style="--d:120ms" transform="translate(64 118)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:210ms" transform="translate(69 94)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:300ms" transform="translate(85 74)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:390ms" transform="translate(107 62)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:480ms" transform="translate(132 61)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:570ms" transform="translate(155 70)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:660ms" transform="translate(172 89)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
                <g class="mc-ponto" style="--d:750ms" transform="translate(180 113)"><path d="M-3.3 -3.3L3.3 3.3M3.3 -3.3L-3.3 3.3"/></g>
              </g>

              <line class="pc-agulha" x1="122" y1="118" x2="122" y2="76"/>
              <circle class="pc-no" cx="122" cy="118" r="4.2"/>
              <circle class="pc-no-interno" cx="122" cy="118" r="1.5"/>

              <line class="pc-linha" x1="52" y1="132.5" x2="192" y2="132.5"/>
              <g class="pc-oitos">
                <circle class="pc-dot" style="--d:330ms" cx="61.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:400ms" cx="79.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:470ms" cx="97.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:540ms" cx="115.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:610ms" cx="133.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:680ms" cx="151.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:750ms" cx="169.5" cy="132.5" r="2.5"/>
                <circle class="pc-dot" style="--d:820ms" cx="187.5" cy="132.5" r="2.5"/>
              </g>
            </svg>
          </div>
        </div>
      </section>

      <!-- 2 VISÃO GERAL -------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="2 de 6: a porta da gerência">
        <p class="guia__eyebrow">Visão geral · Quem e o quê</p>
        <h3 class="guia__h">A porta da gerência</h3>
        <p class="guia__lede">Uma única tela mede a casa: quem entra, o que aparece e em que moldura.</p>

        <div class="atos">
          <article class="ato guia__anim" style="--d:60ms">
            <div class="ato__palco">
              <span class="ato__knot">1</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><rect class="est2-desenha" pathLength="100" x="4" y="10.5" width="16" height="9" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="15" r="1.3"/></svg></span>
            </div>
            <small class="ato__fala">Porta 1 · O portão</small>
            <h4 class="ato__titulo">Quem entra</h4>
            <code class="ato__rota">/gerente · acesso gerente</code>
            <ul class="ato__linhas">
              <li>só quem tem perfil <code>gerente</code></li>
              <li>logado comum ou anônimo recebe <b>403</b> na tela, sem redirecionar</li>
              <li>não há POST aqui; o CSRF só vale para formulários</li>
            </ul>
            <span class="ato__selo"><code>Roteador · erro(403)</code></span>
          </article>

          <span class="ato__seta guia__anim" style="--d:150ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:240ms">
            <div class="ato__palco">
              <span class="ato__knot">2</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><rect class="est2-desenha" pathLength="100" x="3.5" y="3.5" width="7" height="7" rx="1.4"/><rect class="est2-desenha" pathLength="100" x="13.5" y="3.5" width="7" height="7" rx="1.4"/><rect class="est2-desenha est2-d2" pathLength="100" x="3.5" y="13.5" width="7" height="7" rx="1.4"/><rect class="est2-desenha est2-d2" pathLength="100" x="13.5" y="13.5" width="7" height="7" rx="1.4"/></svg></span>
            </div>
            <small class="ato__fala">Porta 2 · O conteúdo</small>
            <h4 class="ato__titulo">Os 8 cartões</h4>
            <code class="ato__rota">GET /gerente</code>
            <ul class="ato__linhas">
              <li>rótulo, total e link para a área</li>
              <li>total <code>null</code> exibe <b>Em breve</b></li>
              <li>Posts, Curtidas e Comentários puxam do Hub</li>
            </ul>
            <span class="ato__selo">8 consultas · sem cache</span>
          </article>

          <span class="ato__seta guia__anim" style="--d:330ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:420ms">
            <div class="ato__palco">
              <span class="ato__knot">3</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="est2-desenha" pathLength="100" d="M4 5h16M4 12h16M4 19h10"/><circle class="est2-desenha est2-d2" pathLength="100" cx="9" cy="5" r="1.5"/><circle class="est2-desenha est2-d2" pathLength="100" cx="14" cy="12" r="1.5"/></svg></span>
            </div>
            <small class="ato__fala">Porta 3 · A moldura</small>
            <h4 class="ato__titulo">Sem o site público</h4>
            <code class="ato__rota">app/telas/layouts/gerente.php</code>
            <ul class="ato__linhas">
              <li>topbar "Desenrola · Painel da gerente" com o menu do usuário</li>
              <li>menu lateral com as 7 seções, e o atalho para o Hub por fora</li>
              <li>sem navbar, sem rodapé, <code>robots noindex</code></li>
            </ul>
            <span class="ato__selo">layout próprio · body.admin</span>
          </article>
        </div>

        <p class="guia__nota"><span><b>O Painel não ganha o botão Voltar contextual</b>: ele é a raiz da área. As outras telas voltam para cá ou para a seção.</span></p>
      </section>

      <!-- 3 DO CLIQUE AO HTML ----------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="3 de 6: do clique ao HTML do painel">
        <p class="guia__eyebrow">Do clique ao HTML</p>
        <h3 class="guia__h">O caminho do pedido</h3>
        <p class="guia__lede">Quatro estações, nenhuma com regra de negócio: a gerente entra, os dados contam, a tela pinta.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 8L4 12.5 8.5 17M15.5 8l4.5 4.5-4.5 4.5"/></svg></span>
            <code>['GET', '/gerente', [PainelControlador::class, 'index'], 'gerente']</code>
            <p>O <b>rotas.php</b> declara o acesso <code>'gerente'</code>. No roteador, <code>Auth::eGerente()</code> falso vira <b>403</b>: a tela de erro, não um redirecionamento.</p>
            <span class="pipeline__chips"><code>rota exata</code><code>403</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect class="est2-desenha est2-d2" pathLength="100" x="9" y="3" width="6" height="4" rx="1.2"/></svg></span>
            <code>PainelControlador::index()</code>
            <p>O controlador monta o <code>$totais</code>: um <b>array literal</b> com 8 entradas, cada uma com rótulo, total e link. Nenhuma outra regra; a escolha do link (área ou Hub) é a única decisão.</p>
            <span class="pipeline__chips"><code>$totais</code><code>array literal</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3v6M12 15v6M3 12h6M15 12h6"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="3.6"/></svg></span>
            <code>contar(fn () => dados-&gt;contar())</code>
            <p>Cada contagem passa pelo <code>contar()</code> do controlador: uma <code>RuntimeException</code> com "Não implementado" vira <b>null</b> (o cartão mostra Em breve); qualquer outro erro <b>sobe</b> e vira 500. O painel nunca fica refém de um método faltando.</p>
            <span class="pipeline__chips"><code>try/catch</code><code>null → Em breve</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 9.2l1.5-4.7h13L20 9.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M5 9.2v10.3h14V9.2"/></svg></span>
            <code>Visao::render('painel/index', ..., 'gerente')</code>
            <p>O render usa a <b>moldura da gerência</b>, não a pública: topbar com a marca e o menu do usuário, menu lateral com as 7 seções, <code>main#conteudo</code> com o aviso de mensagens. <code>robots noindex</code> e <code>csrf</code> no meta.</p>
            <span class="pipeline__chips"><code>layout gerente</code><code>noindex</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>O Painel é a única tela da área sem POST.</b> Tudo aqui é navegação; cada cartão entra numa sessão diferente da área.</span></p>
      </section>

      <!-- 4 AS OITO FONTES -------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="4 de 6: de onde vem cada número">
        <p class="guia__eyebrow">Banco · As oito fontes</p>
        <h3 class="guia__h">De onde vem cada número</h3>
        <p class="guia__lede">Oito contagens, cada uma na casa dela. Nada é pré-calculado: o painel mede agora.</p>

        <div class="ref__papel sqlp2 guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">PainelControlador::index()</span><span class="ref__tag">SQL 04 · 8 consultas · sem cache</span></div>
          <div class="sqlp2__tabela" role="table" aria-label="Cada cartão do painel, a consulta e para onde leva">

            <div class="sqlp2__head" role="row" aria-hidden="true">
              <span role="columnheader">Cartão</span>
              <span></span>
              <span role="columnheader">Consulta</span>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">insumos · /gerente/insumos</small>
                <code class="sqlp2__sql">SELECT count(*) FROM insumos</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">Todos os insumos cadastrados, com ou sem estoque abaixo do mínimo.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">clientes · /gerente/clientes</small>
                <code class="sqlp2__sql">SELECT count(*) FROM clientes</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">Todos os clientes do ateliê. Cliente é registro comercial: não tem login.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">produtos · /gerente/produtos</small>
                <code class="sqlp2__sql">SELECT count(*) FROM produtos</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">O mostruário cadastrado, incluindo os inativos (o catálogo público só mostra os ativos).</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">matrizes · /gerente/matrizes</small>
                <code class="sqlp2__sql">SELECT count(*) FROM matrizes</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">As matrizes do ateliê, as do Hub e as convertidas por imagem.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">usuarios · /gerente/usuarios</small>
                <code class="sqlp2__sql">SELECT COUNT(*) FROM usuarios</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">Contas com login: a comunidade do Hub e a gerência. Não confundir com clientes.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">posts no Hub · /hub</small>
                <code class="sqlp2__sql">SELECT count(*) FROM bordados
WHERE tipo = 'hub' AND NOT oculto</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">Os bordados <b>visíveis</b> do Hub. O que a gerente ocultou na moderação, este número não conta.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">curtidas · /hub?ordem=curtidos</small>
                <code class="sqlp2__sql">SELECT count(*) FROM curtidas</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">As curtidas de tudo, sem olhar se o post está oculto: a métrica histórica do Hub inteiro.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">comentários · /hub</small>
                <code class="sqlp2__sql">SELECT count(*) FROM comentarios</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">Todos os comentários do Hub, inclusive os ocultos: ela confere aqui o volume total.</p>
            </div>

          </div>
        </div>

        <ul class="guia__chips guia__chips--rumo guia__anim" style="--d:240ms">
          <li>oito consultas a cada abertura, sem cache nem contagem prévia</li>
          <li>Posts no Hub filtram por tipo 'hub' e oculto = false</li>
          <li>Peças não tem cartão: vive só no menu lateral</li>
        </ul>
      </section>

      <!-- 5 A TELA ----------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="5 de 6: o cartão é o botão">
        <p class="guia__eyebrow">A tela · Os oito cartões</p>
        <h3 class="guia__h">O cartão é o botão</h3>
        <p class="guia__lede">O número é a hierarquia: cresce de cima para baixo, e o cartão inteiro vira o link.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4z"/></svg></span>
            <code>.painel-cards</code>
            <p>Grade responsiva por <code>auto-fill</code> e <code>minmax(10.5rem, 1fr)</code>, com folha de 1rem e sem marcador de lista: quanto cabe na largura, assim ficam os cartões.</p>
            <span class="pipeline__chips"><code>auto-fill</code><code>minmax(10.5rem, 1fr)</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="3.5" y="6.5" width="17" height="12" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9 12h6"/></svg></span>
            <code>.painel-card</code>
            <p>O cartão inteiro é um <code>&lt;a&gt;</code> para a área correspondente; no hover ou no <b>foco de teclado</b> a borda vira vinho e uma sombra suave sobe. Mínimo de 44 px de altura, fundo areia e raio grande.</p>
            <span class="pipeline__chips"><code>&lt;a&gt; inteiro</code><code>hover / focus-visible</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M7 14.5 12 5l5 9.5M9.2 11h5.6"/></svg></span>
            <code>.painel-card__total</code>
            <p>O número em <code>--font-6xl</code>, vinho-900 e <code>tabular-nums</code>, formatado em pt-BR por <code>number_format(..., 0, ',', '.')</code>. Com o total null, <b>Em breve</b> entra no lugar com tamanho e peso bem menores.</p>
            <span class="pipeline__chips"><code>tabular-nums</code><code>1.234</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M6 15h12M6 11h12M6 7h8"/></svg></span>
            <code>.painel-card__rotulo</code>
            <p>O rótulo fecha o cartão em caixa alta com espaçamento de 0.12em e cor tinta-suave: é o que identifica o número sem competir com ele.</p>
            <span class="pipeline__chips"><code>uppercase</code><code>0.12em</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Uma tela paciente.</b> Sem hover, sem filtro, sem ordenação: o número importa, a navegação é o resto.</span></p>
      </section>

      <!-- 6 ANEXO -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="6 de 6: a árvore do painel">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A árvore do painel</h3>
        <p class="guia__lede">Onze arquivos no caminho mais curto da área: da rota ao número grande.</p>

        <div class="ref__papel arvore guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">$ tree app | grep -E "Painel|Gerente"</span><span class="ref__tag">REF 06 · 11 arquivos · 3 camadas</span></div>
          <div class="arvore__cols">

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/config/</code><small>a rota</small></div>
                <p class="arvore__file"><code>rotas.php</code><span class="arvore__lider"></span><span class="arvore__role">GET /gerente · acesso gerente</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/controladores/</code><small>a porta</small></div>
                <p class="arvore__file"><code>PainelControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">index · contar() com tolerância</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/dados/</code><small>o miolo</small></div>
                <p class="arvore__file"><code>InsumoDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contar()</span></p>
                <p class="arvore__file"><code>ClienteDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contar()</span></p>
                <p class="arvore__file"><code>ProdutoDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contar()</span></p>
                <p class="arvore__file"><code>MatrizDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contar()</span></p>
                <p class="arvore__file"><code>UsuarioDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contar()</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/dados/</code><small>o Hub</small></div>
                <p class="arvore__file"><code>HubDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contarPublicados · contarCurtidas</span></p>
                <p class="arvore__file"><code>ComentarioDados.php</code><span class="arvore__lider"></span><span class="arvore__role">contar()</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/painel/</code><small>a tela</small></div>
                <p class="arvore__file"><code>index.php</code><span class="arvore__lider"></span><span class="arvore__role">os oito cartões</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/layouts/</code><small>a moldura</small></div>
                <p class="arvore__file"><code>gerente.php</code><span class="arvore__lider"></span><span class="arvore__role">topbar · admin-nav · noindex</span></p>
              </section>
            </div>

          </div>
        </div>

        <p class="guia__nota"><span><b>O índice da área.</b> Cada cartão é a porta de um cadastro; quem quiser o caminho até um dado, começa por este número grande.</span></p>
      </section>

    </div><!-- /trilho -->

    <footer class="guia__deck-foot">
      <button class="guia__nav guia__nav--anterior" type="button" data-guia-nav="-1" aria-label="Slide anterior">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        <span>Anterior</span>
      </button>

      <div class="guia__progresso" aria-hidden="true"><span class="guia__progresso-fio" data-guia-progresso></span></div>

      <div class="guia__pontos" role="tablist" aria-label="Ir para o slide">
        <?php for ($i = 1; $i <= 6; $i++): ?>
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
