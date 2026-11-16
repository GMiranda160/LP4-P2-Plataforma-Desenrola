<?php
/**
 * Guia: como o Hub funciona.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Popup em formato de deck: 8 slides, um por vez, com navegação por setas,
 * botões, pontos e teclado. Explica o caminho do clique no feed até o post
 * completo: a anatomia do post, curtir e comentar sem recarregar, a
 * moderação sem apagar, o filtro e a paginação, o tutorial com IA e os
 * bastidores do feed. O que a home já explica (rotas, CSRF e o render
 * pela Visao) fica no guia da home; aqui só o que é do Hub.
 * Segue os fatos do código; se o fluxo mudar, atualize aqui.
 */
?>
<div class="guia guia--deck guia--hub" id="guia-hub" role="dialog" aria-modal="true" aria-labelledby="guia-hub-titulo" hidden data-guia-deck>
  <div class="guia__dialog guia__deck-frame">

    <header class="guia__deck-head">
      <div>
        <p class="guia__eyebrow">Guia · O Hub</p>
        <h2 class="guia__deck-title" id="guia-hub-titulo">Como o Hub funciona</h2>
      </div>
      <div class="guia__deck-meta">
        <span class="guia__contador" data-guia-contador aria-hidden="true">1 / 8</span>
        <button class="guia__fechar" type="button" data-guia-fechar aria-label="Fechar guia">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </header>

    <div class="guia__trilho">

      <!-- 1 CAPA ------------------------------------------------------- -->
      <section class="guia__slide is-active" role="group" aria-roledescription="slide" aria-label="1 de 8: capa">
        <div class="guia__capa">
          <div class="guia__capa-texto">
            <p class="guia__eyebrow guia__anim" style="--d:0ms">Guia da plataforma</p>
            <h3 class="guia__capa-h"><span class="guia__anim" style="--d:60ms">A praça do ateliê</span>
              <span class="guia__anim guia__capa-h-sub" style="--d:160ms">o feed da comunidade</span></h3>
            <p class="guia__lede guia__anim" style="--d:240ms">
              O que a comunidade está bordando agora, do jeito que uma rede social mostra:
              peça, autor, ficha da matriz, curtida, comentário e tutorial com IA.
            </p>
            <ul class="guia__chips guia__anim" style="--d:320ms">
              <li>GET /hub</li>
              <li>público</li>
              <li>curtir sem recarregar</li>
              <li>moderação</li>
            </ul>
            <p class="guia__dica guia__anim" style="--d:400ms">Use as setas, os botões ou os pontos abaixo. ESC fecha.</p>
          </div>
          <div class="guia__capa-arte guia__anim" style="--d:200ms" aria-hidden="true">
            <svg class="hc-cena" viewBox="0 0 244 176" fill="none">
              <path class="gl-chao" d="M14 166 H230"/>

              <rect class="hc-card" pathLength="100" x="40" y="10" width="164" height="130" rx="12"/>

              <circle class="hc-avatar" pathLength="100" cx="66" cy="38" r="10.5"/>
              <path class="hc-linha" pathLength="100" d="M86 33.5h72"/>
              <path class="hc-linha hc-linha--2" pathLength="100" d="M86 46.5h44"/>

              <path class="hc-coracao" d="M122 96c-10.5-8-16.5-13-16.5-22.3 0-5.6 4-9.5 9.5-9.5 3.4 0 6 2 7 4.8 1-2.8 3.6-4.8 7-4.8 5.5 0 9.5 3.9 9.5 9.5 0 9.3-6 14.3-16.5 22.3z"/>

              <path class="hc-acao" pathLength="100" d="M66.5 123.5 74.6 116"/>
              <path class="hc-acao hc-acao--2" pathLength="100" d="M108 124.5h12a1.6 1.6 0 0 0 1.6-1.6V112"/>
              <path class="hc-acao hc-acao--3" pathLength="100" d="M152.5 119l3.5-4.8 3.5 4.8"/>
            </svg>
          </div>
        </div>
      </section>

      <!-- 2 VISÃO GERAL -------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="2 de 8: quem vê o quê no Hub">
        <p class="guia__eyebrow">Visão geral · Três papéis</p>
        <h3 class="guia__h">Quem vê o quê</h3>
        <p class="guia__lede">O Hub é público de verdade: quem entra participa, quem modera não apaga nada.</p>

        <div class="atos">
          <article class="ato guia__anim" style="--d:60ms">
            <div class="ato__palco">
              <span class="ato__knot">1</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="3.4"/></svg></span>
            </div>
            <small class="ato__fala">Papel 1 · Qualquer visitante</small>
            <h4 class="ato__titulo">Só vê</h4>
            <code class="ato__rota">GET /hub · público</code>
            <ul class="ato__linhas">
              <li>vê só os bordados visíveis do Hub</li>
              <li>filtra por tipo de peça e ordena por recentes ou curtidos</li>
              <li>rola a paginação com "Mais bordados"</li>
            </ul>
            <span class="ato__selo">leitura livre, sem conta</span>
          </article>

          <span class="ato__seta guia__anim" style="--d:150ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:240ms">
            <div class="ato__palco">
              <span class="ato__knot">2</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="est2-desenha" pathLength="100" d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.7-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg></span>
            </div>
            <small class="ato__fala">Papel 2 · Quem está logado</small>
            <h4 class="ato__titulo">Participa</h4>
            <code class="ato__rota">POST /hub/curtir · /hub/comentar</code>
            <ul class="ato__linhas">
              <li>curte sem recarregar (uma curtida por pessoa e bordado)</li>
              <li>comenta até 5 vezes por minuto, em 280 caracteres</li>
              <li>gera o tutorial com IA e exclui o que é seu</li>
            </ul>
            <span class="ato__selo">1 curtida · 5 comentários/min</span>
          </article>

          <span class="ato__seta guia__anim" style="--d:330ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:420ms">
            <div class="ato__palco">
              <span class="ato__knot">3</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="est2-desenha" pathLength="100" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="2.8"/></svg></span>
            </div>
            <small class="ato__fala">Papel 3 · A gerência</small>
            <h4 class="ato__titulo">Modera</h4>
            <code class="ato__rota">POST /gerente/hub/ocultar</code>
            <ul class="ato__linhas">
              <li>oculta ou reexibe bordado e comentário, sem apagar nada</li>
              <li>oculto some do feed e do detalhe para todo mundo</li>
              <li>badge "Oculto" aparece só para quem deve ver</li>
            </ul>
            <span class="ato__selo">ocultar ≠ excluir</span>
          </article>
        </div>

        <p class="guia__nota"><span><b>O autor não abre o próprio oculto</b>: só a gerência abre o detalhe de um post oculto, e o autor só vê o seu em <code>/meus-bordados</code>.</span></p>
      </section>

      <!-- 3 ANATOMIA DO POST -------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="3 de 8: anatomia do post">
        <p class="guia__eyebrow">O post · Peça da tela</p>
        <h3 class="guia__h">Anatomia do post</h3>
        <p class="guia__lede">A peça ao lado, o corpo ao meio, os comentários embaixo. O card é o post, no feed e no detalhe.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">A MÍDIA</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="3.5" y="3.5" width="17" height="17" rx="2"/><circle class="est2-desenha est2-d2" pathLength="100" cx="9.5" cy="9" r="1.6"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.5 16.5 10 11l4.5 5 3.5-3.5 2 2.5"/></svg></span>
            <code>peca_bordada.php</code>
            <p>A peça em <b>4:5</b>: a peça do catálogo (ou a foto da pessoa) tingida com a cor do tecido, e a <b>matriz SVG</b> por cima, posicionada por pos_x, pos_y, escala e rotacao.</p>
            <span class="pipeline__chips"><code>peca_bordada</code><code>svg do servidor</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">A AUTORIA</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="9.5" cy="8.5" r="3.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M3.5 19.5c1.2-4.6 3.3-6.9 6-6.9s4.8 2.3 6 6.9"/></svg></span>
            <code>hub_avatar() · hub_tempo()</code>
            <p>Avatar com a <b>inicial</b> do nome (mesmo estilo do menu) e o tempo relativo: "agora", "há 5 min", "há 3 h", "há 2 dias"; depois de uma semana, a data vira <code>d/m/Y</code>.</p>
            <span class="pipeline__chips"><code>avatar na cor da marca</code><code>time datetime</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">A FICHA</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 9.2l1.5-4.7h13L20 9.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M4 9.2h16"/><path class="est2-desenha est2-d2" pathLength="100" d="M5 9.2v10.3h14V9.2"/></svg></span>
            <code>.hub-ficha</code>
            <p>Os dois números da matriz: <b>largura × altura</b> em pontos e a <b>contagem total</b> formatada em pt-BR. Quando há paleta, as <b>linhas</b> aparecem como bolinhas com o hex no title.</p>
            <span class="pipeline__chips"><code>number_format</code><code>paleta filtrada</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">A BARRA</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.7-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/><path class="est2-desenha est2-d2" pathLength="100" d="M4 17.6c5.2 0 9.6-4.4 9.6-9.6M4 21c7 0 13-6 13-13"/></svg></span>
            <code>.hub-post__barra</code>
            <p><b>Curtir</b> e <b>Tutorial</b> (ou "Tutorial com IA") na frente; oculto/reexibir e excluir na gestão, por quem pode. O feed mostra os <b>2 últimos comentários</b>; "Ver todos" leva ao detalhe.</p>
            <span class="pipeline__chips"><code>aria-pressed</code><code>2 últimos</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Cada post tem uma âncora</b> (<code>#post-ID</code>): comentar, ocultar e excluir voltam para a mesma página, no mesmo post.</span></p>
      </section>

      <!-- 4 CURTIR E COMENTAR ------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="4 de 8: curtir e comentar, cada um do seu jeito">
        <p class="guia__eyebrow">Ação · Sem recarregar</p>
        <h3 class="guia__h">Curtir e comentar, cada um do seu jeito</h3>
        <p class="guia__lede">A curtida vai e volta como um pulso: o botão trava, o servidor responde, a tela acende de novo.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M8.5 8L4 12.5 8.5 17M15.5 8l4.5 4.5-4.5 4.5"/></svg></span>
            <code>.js-curtir · delegação</code>
            <p>O <b>hub.js</b> escuta o clique no documento inteiro; o botão desabilita durante a viagem. O CSRF vai no cabeçalho <code>X-CSRF-Token</code>, lido do <code>meta[name="csrf"]</code>.</p>
            <span class="pipeline__chips"><code>document.addEventListener</code><code>async</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.8" rx="7.4" ry="2.8"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.6 5.8v12.4c0 1.6 3.3 2.8 7.4 2.8s7.4-1.2 7.4-2.8V5.8"/></svg></span>
            <code>POST /hub/curtir</code>
            <p><b>HubServico::curtir</b> confere: o bordado existe, é <code>tipo 'hub'</code> e não está oculto; senão devolve null, e o controlador responde <b>404</b>. O toggle usa <code>INSERT ... ON CONFLICT DO NOTHING</code> na curtida: se gravou, curtiu; se já existia, remove.</p>
            <span class="pipeline__chips"><code>PK composta</code><code>INSERT ON CONFLICT</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 12h13"/><path class="est2-desenha est2-d2" pathLength="100" d="M13 6l6 6-6 6"/></svg></span>
            <code>{ curtiu, curtidas }</code>
            <p>O JSON volta com a curtida do momento e a contagem fresca: o JS atualiza <code>aria-pressed</code> e o número do botão. <b>401</b> leva para o login; qualquer erro vira <code>console.error</code> e o botão religa.</p>
            <span class="pipeline__chips"><code>aria-pressed</code><code>401 → /login</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.5 13.5l2 2 3.5-4"/></svg></span>
            <code>POST /hub/comentar</code>
            <p>Neste caso é <b>form normal</b> (302 + flash): <code>mb_strlen</code> de 1 a 280, <b>5 por minuto por sessão</b> (guardados em <code>comentarios_minuto</code>), e só comenta em bordado visível (<code>NOT oculto</code>).</p>
            <span class="pipeline__chips"><code>280 caracteres</code><code>5 por minuto</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Ninguém curte um oculto.</b> Para curtir um post que a gerência ocultou, as duas pontas respondem não: o servidor recusa e o botão fica como estava.</span></p>
      </section>

      <!-- 5 MODERAÇÃO --------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="5 de 8: a moderação sem borracha">
        <p class="guia__eyebrow">Moderação · Ocultar não é apagar</p>
        <h3 class="guia__h">A moderação sem borracha</h3>
        <p class="guia__lede">A gerência esconde o que não cabe no feed; excluir de verdade é para o dono ou para ela.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">OCULTAR O POST</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><path class="est2-desenha est2-d2" pathLength="100" d="m5 5 14 14"/></svg></span>
            <code>POST /gerente/hub/ocultar</code>
            <p>Só a <b>gerência</b>: <code>oculto = NOT oculto</code> em <code>bordados</code>. O feed nunca mostra oculto; o detalhe devolve "não encontrado" para quem não é ela; o badge "Oculto" só aparece para ela ou para o autor (na lista "Meus bordados").</p>
            <span class="pipeline__chips"><code>UPDATE NOT oculto</code><code>badge</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">OCULTAR O COMENTÁRIO</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M21 12a8 8 0 0 1-11.6 7.1L4 20.5l1.4-4.6A8 8 0 1 1 21 12Z"/><path class="est2-desenha est2-d2" pathLength="100" d="m5 5 14 14"/></svg></span>
            <code>POST /gerente/hub/comentario/ocultar</code>
            <p>Mesmo mecanismo: <code>NOT oculto</code> no <code>comentarios</code>. O contador do card ignora os ocultos; a gerência ainda os vê, com o selo.</p>
            <span class="pipeline__chips"><code>contador só visíveis</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">EXCLUIR</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 7h16M9.5 7V4.5h5V7M6 7l1 13h10l1-13"/><path class="est2-desenha est2-d2" pathLength="100" d="M10 11v5.5M14 11v5.5"/></svg></span>
            <code>POST /bordado/excluir</code>
            <p>O <b>autor</b> ou a <b>gerência</b> exclui o bordado: as curtidas e os comentários caem por <code>ON DELETE CASCADE</code>; a foto no Storage é removida junto. Excluir comentário: o autor dele ou a gerência.</p>
            <span class="pipeline__chips"><code>CASCADE</code><code>Storage::remover</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">O ARREMATE</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="3.4"/></svg></span>
            <code>destino_hub() · 23503</code>
            <p>Depois de excluir, o destino só pode ser <code>/hub</code> ou <code>/meus-bordados</code>: o redirecionador recusa qualquer outra URL. E o bordado que é <b>produto do mostruário</b> recusa excluir de novo: o banco devolve <code>23503</code> e isso vira uma frase educada, não um 500.</p>
            <span class="pipeline__chips"><code>anti open-redirect</code><code>23503 amigável</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Nada de apagar às cegas.</b> Oculto some do feed na hora; excluir é definitivo, e antes da exclusão aparece um "Tem certeza?".</span></p>
      </section>

      <!-- 6 O FEED ------------------------------------------------------ -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="6 de 8: o feed, filtro, ordem e paginação">
        <p class="guia__eyebrow">O feed · Filtros e ordem</p>
        <h3 class="guia__h">O feed, filtrado e ordenado</h3>
        <p class="guia__lede">Três consultas por abertura: os posts, os dois últimos comentários de todos e as categorias dos filtros.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">OS FILTROS</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 6h16M7 12h10M10 18h4"/></svg></span>
            <code>?categoria=</code>
            <p>Chips por <b>tipo de peça</b>, com <code>?categoria=slug</code>; o slug só passa numa regex <code>^[a-z0-9-]{1,80}$</code>; o desconhecido vira "sem filtro", nunca chega ao SQL.</p>
            <span class="pipeline__chips"><code>filter-chip</code><code>regex slug</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">A ORDENAÇÃO</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 4v13M8 13l4 4 4-4"/><path class="est2-desenha est2-d2" pathLength="100" d="M5 7h14"/></svg></span>
            <code>?ordem=recentes|curtidos</code>
            <p><b>recentes</b> (o padrão) ordena por criação e desempata por id; <b>curtidos</b> ordena pela contagem de curtidas da própria consulta. O parâmetro só escolhe a chave do mapa no serviço, nunca vai para o SQL.</p>
            <span class="pipeline__chips"><code>recentes: criado_em</code><code>curtidos: curtidas</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">A PAGINAÇÃO</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="3.5" y="5.5" width="17" height="13" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9 19h6"/></svg></span>
            <code>LIMIT 13 · OFFSET</code>
            <p><b>12 por página</b>, com uma linha a mais só para saber se há próxima (<code>temMais</code>); não há COUNT e não há número de página no fundo. "Anteriores" e "Mais bordados" navega por <code>?pagina=N</code>.</p>
            <span class="pipeline__chips"><code>POR_PAGINA = 12</code><code>ROW_NUMBER</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">O VAZIO</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M5 12h14"/><path class="est2-desenha est2-d2" pathLength="100" d="M13 6l6 6-6 6"/></svg></span>
            <code>.hub-vazio</code>
            <p>Feed vazio (ou filtro que não acha nada) mostra a mensagem e um botão para o montador. A lateral convida a criar, e para quem está logado também aponta "Ver meus bordados".</p>
            <span class="pipeline__chips"><code>empty-state</code><code>/montador</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>URL que manda.</b> Filtro, ordem e página são querystring: dá para compartilhar o link de uma vista filtrada do Hub.</span></p>
      </section>

      <!-- 7 O TUTORIAL -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="7 de 8: o tutorial com IA">
        <p class="guia__eyebrow">Tutorial · Texto sob demanda</p>
        <h3 class="guia__h">O tutorial com IA, sob demanda</h3>
        <p class="guia__lede">Ninguém borda por sorte: a IA monta o passo a passo, o banco guarda o texto e a tela pede só quando alguém quiser.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">O BOTÃO</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3.5 13.6 8a2 2 0 0 0 1.3 1.3l4.6 1.6-4.6 1.6a2 2 0 0 0-1.3 1.3L12 18.5l-1.6-4.6a2 2 0 0 0-1.3-1.3L4.5 11l4.6-1.6A2 2 0 0 0 10.4 8Z"/><path class="est2-desenha est2-d2" pathLength="100" d="M19 3v3M17.5 4.5h3"/></svg></span>
            <code>.js-abrir-tutorial</code>
            <p>No <b>detalhe</b> o tutorial abre na própria tela; no <b>feed</b> abre um modal com fundo clicável, botão de fechar com foco e ESC. Sem sessão, a tela mostra "Entre para gerar".</p>
            <span class="pipeline__chips"><code>modal no feed</code><code>ESC fecha</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">A ROTA</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M12 7.5V12l3 2"/></svg></span>
            <code>POST /bordado/tutorial</code>
            <p>A requisição é um <b>fetch</b> JSON com o CSRF no cabeçalho. O controlador confere: bordado existe (<b>404</b>), monta o texto (cache ou Gemini) e responde <code>{ texto, origem }</code>.</p>
            <span class="pipeline__chips"><code>JSON</code><code>fetch + CSRF</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">O CACHE</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 7v5l3 2"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="8.5"/></svg></span>
            <code>tutorial_ia · no próprio bordado</code>
            <p>Já gerado, o texto fica salvo em <code>bordados.tutorial_ia</code>: as próximas visitas leem do banco, <b>sem chamar a IA</b>. Regenerar (<code>forcar=1</code>) força nova geração e substitui; o limite é <b>5 tutoriais novos a cada 10 minutos</b> por sessão (429).</p>
            <span class="pipeline__chips"><code>origem: cache | ia | reserva</code><code>5 por 10 min</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">A TELA</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/></svg></span>
            <code>texto.textContent</code>
            <p>O texto entra com <code>textContent</code>: nada de HTML do servidor vira marcação na tela. A nota "Revise antes de bordar" lembra que a IA é uma dica, não uma garantia.</p>
            <span class="pipeline__chips"><code>textContent</code><code>sem HTML</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Geração é sob demanda.</b> O post carrega instantâneo com o que o banco tem; o tutorial só nasce quando alguém pede: nunca no carregamento da página.</span></p>
      </section>

      <!-- 8 ANEXO -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="8 de 8: a árvore do Hub">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A árvore do Hub</h3>
        <p class="guia__lede">Treze arquivos no caminho: da rota ao coração que infla na tela.</p>

        <div class="ref__papel arvore guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">$ tree app | grep -E "Hub|Comentario|Tutorial"</span><span class="ref__tag">REF 08 · 13 arquivos · 3 camadas</span></div>
          <div class="arvore__cols">

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/config/</code><small>as rotas</small></div>
                <p class="arvore__file"><code>rotas.php</code><span class="arvore__lider"></span><span class="arvore__role">feed · detalhe · curtir · comentar · ocultar</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/controladores/</code><small>a porta</small></div>
                <p class="arvore__file"><code>HubControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">feed · detalhe · meus · curtir · ocultar (Eduardo)</span></p>
                <p class="arvore__file"><code>ComentarioControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">comentar · excluir · ocultar (Eduardo)</span></p>
                <p class="arvore__file"><code>TutorialControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">gerar: JSON, limite 5/10min</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/processamento/</code><small>as regras</small></div>
                <p class="arvore__file"><code>HubServico.php</code><span class="arvore__lider"></span><span class="arvore__role">feed sem SQL · curtir: insert/delete</span></p>
                <p class="arvore__file"><code>ComentarioServico.php</code><span class="arvore__lider"></span><span class="arvore__role">1 a 280 · 5 por minuto</span></p>
                <p class="arvore__file"><code>TutorialServico.php</code><span class="arvore__lider"></span><span class="arvore__role">texto do Gemini, em cache</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/dados/</code><small>o SQL</small></div>
                <p class="arvore__file"><code>HubDados.php</code><span class="arvore__lider"></span><span class="arvore__role">CAMPOS_CARD · feed: ordenação fixa</span></p>
                <p class="arvore__file"><code>ComentarioDados.php</code><span class="arvore__lider"></span><span class="arvore__role">ROW_NUMBER · os 2 últimos</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/hub/</code><small>as telas</small></div>
                <p class="arvore__file"><code>feed.php</code><span class="arvore__lider"></span><span class="arvore__role">listagem, filtros, vazio</span></p>
                <p class="arvore__file"><code>detalhe.php</code><span class="arvore__lider"></span><span class="arvore__role">um post, com tutorial aberto</span></p>
                <p class="arvore__file"><code>meus.php</code><span class="arvore__lider"></span><span class="arvore__role">os seus bordados (Eduardo)</span></p>
                <p class="arvore__file"><code>_card.php</code><span class="arvore__lider"></span><span class="arvore__role">o componente post (Eduardo)</span></p>
                <p class="arvore__file"><code>_ajudantes.php</code><span class="arvore__lider"></span><span class="arvore__role">tempo relativo · avatar · ícones</span></p>
              </section>
            </div>

          </div>
        </div>

        <p class="guia__nota"><span><b>Telas do Eduardo, núcleo do Guilherme.</b> As divisas do time são exatamente as divisas do Hub: quem escolhe a regra da rede, e quem mostra.</span></p>
      </section>

    </div><!-- /trilho -->

    <footer class="guia__deck-foot">
      <button class="guia__nav guia__nav--anterior" type="button" data-guia-nav="-1" aria-label="Slide anterior">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        <span>Anterior</span>
      </button>

      <div class="guia__progresso" aria-hidden="true"><span class="guia__progresso-fio" data-guia-progresso></span></div>

      <div class="guia__pontos" role="tablist" aria-label="Ir para o slide">
        <?php for ($i = 1; $i <= 8; $i++): ?>
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
