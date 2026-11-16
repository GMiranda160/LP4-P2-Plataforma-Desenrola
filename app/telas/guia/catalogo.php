<?php
/**
 * Guia: como o catálogo é montado.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Popup em formato de deck: 6 slides, um por vez, com navegação por setas,
 * botões, pontos e teclado. Explica como um produto nasce (montador no modo
 * produto e cadastro na gerência), a consulta SQL do catálogo e como a
 * tela vira HTML. O que a home já explica (rotas, CSRF e renderização pela
 * Visao) fica no guia da home; aqui só o que é do produto.
 * Segue os fatos do código; se o fluxo mudar, atualize aqui.
 */
?>
<div class="guia guia--deck guia--catalogo" id="guia-catalogo" role="dialog" aria-modal="true" aria-labelledby="guia-catalogo-titulo" hidden data-guia-deck>
  <div class="guia__dialog guia__deck-frame">

    <header class="guia__deck-head">
      <div>
        <p class="guia__eyebrow">Guia · Catálogo</p>
        <h2 class="guia__deck-title" id="guia-catalogo-titulo">Como o catálogo é montado</h2>
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
            <h3 class="guia__capa-h"><span class="guia__anim" style="--d:60ms">Do montador à vitrine,</span>
              <span class="guia__anim guia__capa-h-sub" style="--d:160ms">o produto em três atos</span></h3>
            <p class="guia__lede guia__anim" style="--d:240ms">
              A gerente monta a combinação no montador, o produto ganha nome e preço,
              e o visitante encontra tudo no catálogo. Você quer ver o caminho completo?
            </p>
            <ul class="guia__chips guia__anim" style="--d:320ms">
              <li>GET /catalogo</li>
              <li>público</li>
              <li>somente leitura</li>
              <li>filtro por categoria</li>
            </ul>
            <p class="guia__dica guia__anim" style="--d:400ms">Use as setas, os botões ou os pontos abaixo. ESC fecha.</p>
          </div>
          <div class="guia__capa-arte guia__anim" style="--d:200ms" aria-hidden="true">
            <svg class="gc-cena" viewBox="0 -20 244 192" fill="none">
              <path class="gl-chao" d="M14 158 H230"/>

              <rect class="gc-card" pathLength="100" x="46" y="12" width="152" height="138" rx="13"/>

              <circle class="gc-peca" pathLength="100" cx="122" cy="62" r="23.5"/>
              <circle class="gc-peca-2" cx="122" cy="62" r="16.5"/>
              <path class="gc-pts" d="M106 57.5l6.5 6.5M112.5 57.5L106 64M119.5 57.5l6.5 6.5M126 57.5l-6.5 6.5M133 57.5l6.5 6.5M139.5 57.5L133 64M106 66l6.5 6.5M112.5 66L106 72.5M119.5 66l6.5 6.5M126 66l-6.5 6.5M133 66l6.5 6.5M139.5 66L133 72.5"/>

              <path class="gc-linha gc-linha--1" pathLength="100" d="M72 108h100"/>
              <path class="gc-linha gc-linha--2" pathLength="100" d="M72 121h62"/>
              <path class="gc-preco" pathLength="100" d="M72 136h38"/>

              <g class="gc-pop">
                <circle class="gc-selo" cx="198" cy="12" r="14.5"/>
                <path class="gc-check" d="M191.3 12.4l5 5 8.9-10.2"/>
              </g>
            </svg>
          </div>
        </div>
      </section>

      <!-- 2 VISÃO GERAL -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="2 de 6: os três atos da jornada">
        <p class="guia__eyebrow">Visão geral da jornada</p>
        <h3 class="guia__h">Três atos, uma vitrine</h3>
        <p class="guia__lede">Do bordado pronto ao card na tela: <b>quem cria</b>, <b>quem guarda</b> e <b>quem mostra</b>. Só a gerente escreve; o visitante só lê.</p>

        <div class="atos">
          <article class="ato guia__anim" style="--d:60ms">
            <div class="ato__palco">
              <span class="ato__knot">1</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 8.5l7 7M15.5 8.5l-7 7"/></svg></span>
            </div>
            <small class="ato__fala">Ato 1 · Só a gerente</small>
            <h4 class="ato__titulo">Nasce</h4>
            <code class="ato__rota">/montador?modo=produto</code>
            <ul class="ato__linhas">
              <li>monta matriz, peça e posição</li>
              <li>o POST grava o bordado com tipo <code>'produto'</code></li>
              <li>o cadastro completa nome, preço e foto</li>
            </ul>
            <span class="ato__selo">INSERT em <code>bordados</code> e <code>produtos</code></span>
          </article>

          <span class="ato__seta guia__anim" style="--d:150ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:240ms">
            <div class="ato__palco">
              <span class="ato__knot">2</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.8" rx="7.4" ry="2.8"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.6 5.8v12.4c0 1.6 3.3 2.8 7.4 2.8s7.4-1.2 7.4-2.8V5.8"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.6 12c0 1.6 3.3 2.8 7.4 2.8s7.4-1.2 7.4-2.8"/></svg></span>
            </div>
            <small class="ato__fala">Ato 2 · O banco</small>
            <h4 class="ato__titulo">Guarda</h4>
            <code class="ato__rota">tabela produtos</code>
            <ul class="ato__linhas">
              <li><code>bordado_id</code> aponta para <code>bordados(id)</code></li>
              <li><code>nome · preco_base · destaque · ativo</code></li>
              <li><code>foto_path</code> só com a foto própria do produto</li>
            </ul>
            <span class="ato__selo"><code>23505</code> se o bordado já tem produto</span>
          </article>

          <span class="ato__seta guia__anim" style="--d:330ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

          <article class="ato guia__anim" style="--d:420ms">
            <div class="ato__palco">
              <span class="ato__knot">3</span>
              <span class="ato__figura"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="est2-desenha" pathLength="100" d="M4 9.2l1.5-4.7h13L20 9.2"/><path class="est2-desenha" pathLength="100" d="M4 9.2h16"/><path class="est2-desenha est2-d2" pathLength="100" d="M5 9.2v10.3h14V9.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.7 19.5v-4.6h4.6v4.6"/></svg></span>
            </div>
            <small class="ato__fala">Ato 3 · Qualquer visitante</small>
            <h4 class="ato__titulo">Aparece</h4>
            <code class="ato__rota">GET /catalogo</code>
            <ul class="ato__linhas">
              <li><code>listar()</code> devolve só <code>p.ativo</code></li>
              <li>filtro <code>?categoria=slug</code> por parâmetro</li>
              <li>grade de cards com <code>peca_bordada</code></li>
            </ul>
            <span class="ato__selo">1 SELECT com 4 JOINs</span>
          </article>
        </div>

        <p class="guia__nota"><span><b>O caminho até aqui está no guia da home.</b> Rotas, CSRF e o render pela Visao são os mesmos; aqui o miolo é o produto.</span></p>
      </section>

      <!-- 3 CRIAÇÃO ------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="3 de 6: como um produto nasce">
        <p class="guia__eyebrow">Ato 1 · Gerente · Criação</p>
        <h3 class="guia__h">Como um produto nasce</h3>
        <p class="guia__lede">Quatro paradas do clique da gerente até a linha na tabela. <b>Nenhuma tela pública escreve aqui.</b></p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 8L4 12.5 8.5 17M15.5 8l4.5 4.5-4.5 4.5"/></svg></span>
            <code>GET /montador?modo=produto</code>
            <p>Só a gerente entra no modo produto: escolhe a matriz ou envia a imagem, posiciona na peça e o <code>POST /montador/salvar</code> grava um bordado com <code>tipo 'produto'</code>, sem publicar no Hub.</p>
            <span class="pipeline__chips"><code>MontadorServico</code><code>tipo 'produto'</code><code>transação</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect class="est2-desenha est2-d2" pathLength="100" x="9" y="3" width="6" height="4" rx="1.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.5 13.5l2 2 3.5-4"/></svg></span>
            <code>/gerente/produtos/form?bordado=ID</code>
            <p>O form já chega com <b>nome e descrição</b> do que a gerente escreveu no montador. Falta preço (máscara de R$), foto opcional, destaque e ativo. O select lista só combinações <code>tipo 'produto'</code> livres.</p>
            <span class="pipeline__chips"><code>combinacoes()</code><code>?bordado=</code><code>velhos</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="8" cy="12" r="4.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M12.5 12H21M17.5 12v3.2M21 12v2.2"/></svg></span>
            <code>ProdutoServico::salvar()</code>
            <p><code>"R$ 1.234,56"</code> vira <code>1234.56</code>; nome até 120; preço acima de zero; combinação precisa ser <code>tipo 'produto'</code> e livre de outro produto; foto só PNG, JPEG ou WebP de até 2 MB, com tipo lido do <b>conteúdo</b>.</p>
            <span class="pipeline__chips"><code>numeroDeReais</code><code>combinacaoDisponivel</code><code>getimagesize</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.6" rx="7.6" ry="2.9"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/></svg></span>
            <code>ProdutoDados::inserir() RETURNING id</code>
            <p><code>INSERT … RETURNING id</code> com <code>ativo</code> e <code>destaque</code> como <code>'t'</code>/<code>'f'</code>; bordado duplicado estoura <code>23505</code> e vira mensagem amigável; flash de sucesso e de volta para <code>/gerente/produtos</code>.</p>
            <span class="pipeline__chips"><code>RETURNING id</code><code>23505</code><code>flash</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Sem combinação, não há produto.</b> O produto é sempre um bordado <code>tipo 'produto'</code> com nome e preço; a peça e a matriz continuam sendo do bordado.</span></p>
      </section>

      <!-- 4 SQL ----------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="4 de 6: a consulta que monta o catálogo">
        <p class="guia__eyebrow">Ato 2 · Banco · SQL</p>
        <h3 class="guia__h">Como o SQL busca os produtos</h3>
        <p class="guia__lede">Um único SELECT resolve tudo: <b>o produto, a peça, a matriz e a categoria</b> chegam juntos, já com a posição do bordado.</p>

        <div class="ref__papel sqlp2 guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">ProdutoDados::listar(?categoria, true)</span><span class="ref__tag">SQL 04 · 1 SELECT · 4 JOINs</span></div>
          <div class="sqlp2__tabela" role="table" aria-label="Cada comando do SQL e o que ele faz">

            <div class="sqlp2__head" role="row" aria-hidden="true">
              <span role="columnheader">Comando</span>
              <span></span>
              <span role="columnheader">O que faz</span>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">produtos · categorias</small>
                <code class="sqlp2__sql">SELECT p.*, c.nome AS categoria, c.slug, c.mockup</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">O produto com o <b>nome</b>, o <b>slug</b> e o <b>mockup</b> da categoria de cada linha, com <code>p.*</code> inclusive a foto própria do produto (<code>foto_path</code>).</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">bordados</small>
                <code class="sqlp2__sql">b.cor_peca, b.pos_x, b.pos_y, b.escala, b.rotacao</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">A <b>cor da peça</b> e a <b>posição do bordado</b> nela: posição, escala e giro.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">bordados · pecas</small>
                <code class="sqlp2__sql">COALESCE(b.foto_path, pc.imagem_path) AS foto_peca
(b.foto_path IS NULL) AS foto_tingir</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">A <b>foto da peça</b>: a que o cliente enviou, senão a peça base do catálogo. <b>foto_tingir</b> vale <code>'t'</code> só em peça base, que aceita tinta; foto do cliente não aceita.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">matrizes</small>
                <code class="sqlp2__sql">COALESCE(m.svg_path, m.svg) AS svg
m.largura, m.altura</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">A <b>matriz</b> vem de dois mundos: <code>m.svg_path</code>, o arquivo novo, ou <code>m.svg</code>, o SVG cru legado embutido no banco.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">4 tabelas</small>
                <code class="sqlp2__sql">JOIN bordados b ON b.id = p.bordado_id
JOIN categorias c ON c.id = b.categoria_id
JOIN matrizes m ON m.id = b.matriz_id
LEFT JOIN pecas pc ON pc.id = b.peca_id</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">De onde tudo vem. <b>LEFT</b> porque a peça base é opcional; sem ela, <code>pc.imagem_path</code> vem nulo e o card usa o mockup.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">produtos · categorias</small>
                <code class="sqlp2__sql">WHERE p.ativo
  [AND c.slug = :categoria] <em>só com filtro</em></code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">O catálogo só mostra <b>ativos</b>; a gerente chama <code>listar(null, false)</code> e o WHERE vira <code>1=1</code>. O filtro entra como parâmetro, validado antes por <code>preg_match</code>.</p>
            </div>

            <div class="sqlp2__row" role="row">
              <div class="sqlp2__cmd">
                <small class="sqlp2__origem">produtos</small>
                <code class="sqlp2__sql">ORDER BY p.criado_em DESC</code>
              </div>
              <span class="sqlp2__seta" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <p class="sqlp2__card">O <b>mais recente</b> aparece primeiro na grade.</p>
            </div>

          </div>
        </div>

        <ul class="guia__chips guia__chips--rumo guia__anim" style="--d:240ms">
          <li>o filtro entra só como parâmetro: bindValue(':categoria')</li>
          <li>o serviço valida o slug com preg_match antes da consulta</li>
          <li>slug fora do padrão vira "sem filtro", nunca vai para o SQL</li>
        </ul>
      </section>

      <!-- 5 EXIBIÇÃO ------------------------------------------------------ -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="5 de 6: da string à tela">
        <p class="guia__eyebrow">Ato 3 · Visitante · Exibição</p>
        <h3 class="guia__h">Como a página é gerada</h3>
        <p class="guia__lede">O controlador não tem regra: <b>chama, trata o erro e renderiza</b>. Quem monta o card é a tela, com a parcial da peça.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M8.5 8L4 12.5 8.5 17M15.5 8l4.5 4.5-4.5 4.5"/></svg></span>
            <code>CatalogoControlador::listar()</code>
            <p>Lê <code>$_GET['categoria']</code>, chama <code>catalogo()</code> e <code>categorias()</code>. Qualquer Throwable vira <code>error_log</code> e listas vazias: a vitrine abre vazia, nunca um erro 500.</p>
            <span class="pipeline__chips"><code>$_GET</code><code>try/catch</code><code>error_log</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3v3M12 18v3M3 12h3M18 12h3M6 6l2 2M16 16l2 2M18 6l-2 2M8 16l-2 2"/><circle class="est2-desenha est2-d2" pathLength="100" cx="12" cy="12" r="4.2"/></svg></span>
            <code>Visao::render('catalogo/lista', ...)</code>
            <p>Recebe <code>$produtos</code>, <code>$categorias</code>, <code>$categoriaAtual</code> e <code>$pagina</code>. O buffer, o extra e o layout estão no guia da home; aqui interessa o que a tela faz com eles.</p>
            <span class="pipeline__chips"><code>EXTR_SKIP</code><code>$conteudo</code><code>layout publico</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg></span>
            <code>catalogo/lista.php</code>
            <p>Filtros: <b>Todos</b> + um chip por slug (link GET, ativo pelo <code>$categoriaAtual</code>). Grade de cards com link <code>/produto?id=ID</code>, categoria, nome e "A partir de" com <code>moeda()</code>. Tudo passa por <code>e()</code>; os ids saem com <code>(int)</code>.</p>
            <span class="pipeline__chips"><code>foreach</code><code>e()</code><code>moeda()</code><code>empty-state</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4.5 9.3L5.9 4.6h12.2l1.4 4.7"/><path class="est2-desenha est2-d2" pathLength="100" d="M5 9.3v9.4A1.5 1.5 0 0 0 6.5 20h11a1.5 1.5 0 0 0 1.5-1.3V9.3"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.6 20v-4.3h4.8V20"/></svg></span>
            <code>peca_bordada.php decide a imagem</code>
            <p><b>Foto própria do produto</b> &gt; foto da peça (tingida se for peça base) &gt; mockup com a matriz SVG por cima. SVG novo vira URL pública do Storage; matriz antiga vira <code>data:</code> URI base64.</p>
            <span class="pipeline__chips"><code>foto_produto &gt; foto_peca &gt; mockup</code><code>svg_data_uri</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Produto inativo ou inexistente devolve 404.</b> A página <code>/produto?id=</code> repete a consulta com <code>buscarAtivo</code> (WHERE p.ativo AND p.id = :id) e cai na tela de não encontrado. Sem produtos, o catálogo mostra o empty-state.</span></p>
      </section>

      <!-- 6 ANEXO ---------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="6 de 6: os arquivos do caminho do produto">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A árvore do produto</h3>
        <p class="guia__lede">Onze arquivos, um único caminho: do clique da gerente no montador ao clique do visitante no card.</p>

        <div class="ref__papel arvore guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">$ tree app | grep -E "Montador|Produto|Catalogo|peca_bordada"</span><span class="ref__tag">REF 06 · 11 arquivos · 3 camadas</span></div>
          <div class="arvore__cols">

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/config/</code><small>as rotas</small></div>
                <p class="arvore__file"><code>rotas.php</code><span class="arvore__lider"></span><span class="arvore__role">/catalogo · /produto · /montador · /gerente/produtos</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/controladores/</code><small>a gerência</small></div>
                <p class="arvore__file"><code>MontadorControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">o modo produto (só gerente)</span></p>
                <p class="arvore__file"><code>ProdutoControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">lista, form, salvar, ativar, excluir</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/processamento/</code><small>as regras</small></div>
                <p class="arvore__file"><code>MontadorServico.php</code><span class="arvore__lider"></span><span class="arvore__role">grava o bordado tipo 'produto'</span></p>
                <p class="arvore__file"><code>ProdutoServico.php</code><span class="arvore__lider"></span><span class="arvore__role">preço, foto, validação, INSERT</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/dados/</code><small>o SQL</small></div>
                <p class="arvore__file"><code>ProdutoDados.php</code><span class="arvore__lider"></span><span class="arvore__role">listar · inserir · buscarAtivo · combinacoes</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/controladores/</code><small>o público</small></div>
                <p class="arvore__file"><code>CatalogoControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">listar e produto, sem regra</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/</code><small>as telas</small></div>
                <p class="arvore__file"><code>produtos/form.php</code><span class="arvore__lider"></span><span class="arvore__role">o cadastro (Arthur)</span></p>
                <p class="arvore__file"><code>catalogo/lista.php</code><span class="arvore__lider"></span><span class="arvore__role">a vitrine e este guia ficam ao lado (Arthur)</span></p>
                <p class="arvore__file"><code>catalogo/produto.php</code><span class="arvore__lider"></span><span class="arvore__role">a página, com 404 se não achar (Arthur)</span></p>
                <p class="arvore__file"><code>parciais/peca_bordada.php</code><span class="arvore__lider"></span><span class="arvore__role">a peça renderizada, usada em todo card</span></p>
              </section>
            </div>

          </div>
        </div>

        <p class="guia__nota"><span><b>O padrão se repete.</b> Controlador decide o fluxo, serviço valida, dados escreve o SQL. Para saber onde um dado nasce, comece pelo controlador.</span></p>
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
