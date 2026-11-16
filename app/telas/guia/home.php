<?php
/**
 * Guia: como a home é montada.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Popup em formato de deck: 10 slides, um por vez, com navegação por setas,
 * botões, pontos e teclado. Explica o caminho do pedido GET / até o HTML na
 * tela: classes, métodos, variáveis, arquivos e comunicações externas.
 * Segue os fatos do código; se o fluxo mudar, atualize aqui.
 */
?>
<div class="guia guia--deck" id="guia-home" role="dialog" aria-modal="true" aria-labelledby="guia-home-titulo" hidden data-guia-deck>
  <div class="guia__dialog guia__deck-frame">

    <header class="guia__deck-head">
      <div>
        <p class="guia__eyebrow">Guia · Página inicial</p>
        <h2 class="guia__deck-title" id="guia-home-titulo">Como a home é montada</h2>
      </div>
      <div class="guia__deck-meta">
        <span class="guia__contador" data-guia-contador aria-hidden="true">1 / 10</span>
        <button class="guia__fechar" type="button" data-guia-fechar aria-label="Fechar guia">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </header>

    <div class="guia__trilho">

      <!-- 1 CAPA ------------------------------------------------------- -->
      <section class="guia__slide is-active" role="group" aria-roledescription="slide" aria-label="1 de 10: capa">
        <div class="guia__capa">
          <div class="guia__capa-texto">
            <p class="guia__eyebrow guia__anim" style="--d:0ms">Guia da plataforma</p>
            <h3 class="guia__capa-h"><span class="guia__anim" style="--d:60ms">Do pedido até o HTML</span>
              <span class="guia__anim guia__capa-h-sub" style="--d:160ms">bordado na tela</span></h3>
            <p class="guia__lede guia__anim" style="--d:240ms">
              Onze passos, as classes que agem em cada um, os arquivos por onde o pedido
              passa e o que sai da plataforma. Tudo na ordem em que acontece.
            </p>
            <ul class="guia__chips guia__anim" style="--d:320ms">
              <li>GET /</li>
              <li>publico</li>
              <li>somente leitura</li>
              <li>sem POST</li>
            </ul>
            <p class="guia__dica guia__anim" style="--d:400ms">Use as setas, os botões ou os pontos abaixo. ESC fecha.</p>
          </div>
          <div class="guia__capa-arte guia__anim" style="--d:200ms" aria-hidden="true">
            <div class="bs">
              <div class="bs__in">
                <b class="bs__nome">Desenrola</b>
                <small class="bs__sub">uma aplicação PHP</small>
                <div class="bs__mods">
                  <span><svg viewBox="0 0 24 24"><path d="M4.5 9.3L5.9 4.6h12.2l1.4 4.7"/><path d="M5 9.3v9.4A1.5 1.5 0 0 0 6.5 20h11a1.5 1.5 0 0 0 1.5-1.3V9.3"/><path d="M9.6 20v-4.3h4.8V20"/></svg>Vitrine</span>
                  <span><svg viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.7-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>Hub</span>
                  <span><svg viewBox="0 0 24 24"><circle cx="6.7" cy="6.7" r="2.1"/><circle cx="6.7" cy="17.3" r="2.1"/><path d="M8.6 8.1L20 19.2M8.6 15.9L20 4.8"/></svg>Montador</span>
                  <span><svg viewBox="0 0 24 24"><rect x="6" y="4.8" width="12" height="15.2" rx="1.6"/><path d="M9.2 4.8a2.8 2.8 0 0 1 5.6 0"/><path d="M9.5 11.4h5"/><path d="M9.5 15.4h5"/></svg>Gerência</span>
                </div>
              </div>
            </div>

            <svg class="guia__fluxo" viewBox="0 0 132 60" fill="none" aria-hidden="true">
              <path class="guia__fluxo-fio" pathLength="100" d="M4 32 H124.5" stroke="var(--color-wine-700)" stroke-width="2.1" stroke-linecap="round"/>
              <path class="guia__fluxo-seta" d="M114 26l10.5 6-10.5 6" stroke="var(--color-wine-700)" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
              <circle class="guia__fluxo-no" r="2.7" fill="var(--color-wine-700)"/>
              <text class="guia__fluxo-tag" x="64" y="18" text-anchor="middle">$conteudo</text>
            </svg>

            <div class="guia__render">
              <div class="guia__render-bar"><span></span><span></span><span></span><code>home/index</code></div>
              <div class="guia__render-body">
                <div class="r-hero wf" style="--d:0ms"><i class="r-hero__titulo"></i><i class="r-hero__linha"></i><i class="r-hero__linha r-hero__linha--meia"></i></div>
                <div class="r-cats wf" style="--d:170ms"><span></span><span></span><span></span><span></span></div>
                <div class="r-vit wf" style="--d:340ms">
                  <div class="r-card"><svg viewBox="0 0 24 24"><path d="M4 17l5-6 4 4.5L16 11l4 6"/><circle cx="9" cy="7" r="1.6"/></svg></div>
                  <div class="r-card"><svg viewBox="0 0 24 24"><path d="M4 17l5-6 4 4.5L16 11l4 6"/><circle cx="9" cy="7" r="1.6"/></svg></div>
                  <div class="r-card"><svg viewBox="0 0 24 24"><path d="M4 17l5-6 4 4.5L16 11l4 6"/><circle cx="9" cy="7" r="1.6"/></svg></div>
                </div>
                <div class="r-feed wf" style="--d:510ms">
                  <div class="r-item"><i></i><div class="r-lines"><b></b><b></b></div></div>
                  <div class="r-item"><i></i><div class="r-lines"><b></b><b></b></div></div>
                  <div class="r-item"><i></i><div class="r-lines"><b></b><b></b></div></div>
                </div>
                <div class="r-cta wf" style="--d:680ms">Pedir orçamento →</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 2 MAPA ------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="2 de 10: a jornada em 11 passos">
        <p class="guia__eyebrow">Visão geral</p>
        <h3 class="guia__h">A jornada em <span class="guia__onze">11</span> passos</h3>
        <p class="guia__lede">Quatro zonas: o <b>pedido entra</b>, a <b>portaria</b> confere a rota, o <b>trabalho</b> acontece e a <b>resposta</b> sai. O home não tem portão de CSRF nem de login.</p>

        <div class="mapa3">
          <aside class="mapa3__rail" aria-label="As quatro camadas da jornada">
            <div class="mapa3__zona z-entrada guia__anim" style="--d:40ms">
              <b>Entrada</b>
              <p>O pedido chega e o ambiente acende: PHP, .env, autoloader e sessão.</p>
              <code>public/index.php → bootstrap.php → sessoes</code>
              <small>Passos 1 a 3</small>
            </div>
            <div class="mapa3__zona z-portaria guia__anim" style="--d:110ms">
              <b>Portaria</b>
              <p>O Roteador confere se a rota existe e qual acesso ela exige.</p>
              <code>Roteador::despachar() · rotas.php</code>
              <small>Passo 4</small>
            </div>
            <div class="mapa3__zona z-trabalho guia__anim" style="--d:180ms">
              <b>Trabalho</b>
              <p>Controlador, serviços e dados agem: as três consultas da home.</p>
              <code>HomeControlador → ProdutoServico · HubServico → Dados</code>
              <small>Passos 5 a 8</small>
            </div>
            <div class="mapa3__zona z-saida guia__anim" style="--d:250ms">
              <b>Saída</b>
              <p>Visao renderiza a tela num buffer e o layout monta o head.</p>
              <code>Visao::render() → publico.php → HTML</code>
              <small>Passos 9 a 11</small>
            </div>
          </aside>

          <div class="mapa3__board">
            <section class="mapa3__banda z-entrada" aria-label="Camada de entrada">
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:80ms">1</span><span class="mapa3__rot guia__anim" style="--d:90ms">O pedido<small>index.php</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:150ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:180ms">2</span><span class="mapa3__rot guia__anim" style="--d:190ms">Ambiente<small>bootstrap.php</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:250ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:300ms">3</span><span class="mapa3__rot guia__anim" style="--d:310ms">Sessão<small>SessaoBanco</small></span></div>
            </section>

            <section class="mapa3__banda z-portaria" aria-label="Camada da portaria">
              <div class="mapa3__passo mapa3__passo--fim"><span class="mapa3__knot mapa3__knot--portao guia__anim" style="--d:360ms">4</span><span class="mapa3__rot guia__anim" style="--d:370ms">A rota existe?<small>rotas.php</small></span></div>
            </section>

            <section class="mapa3__banda z-trabalho mapa3__banda--back" aria-label="Camada do trabalho">
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:420ms">5</span><span class="mapa3__rot guia__anim" style="--d:430ms">Controlador<small>HomeControlador</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:480ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:520ms">6</span><span class="mapa3__rot guia__anim" style="--d:530ms">Destaques<small>ProdutoDados</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:580ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:630ms">7</span><span class="mapa3__rot guia__anim" style="--d:640ms">+Curtidos<small>HubDados</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:690ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:740ms">8</span><span class="mapa3__rot guia__anim" style="--d:750ms">Categorias<small>ProdutoDados</small></span></div>
            </section>

            <section class="mapa3__banda z-saida" aria-label="Camada de saída">
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:810ms">9</span><span class="mapa3__rot guia__anim" style="--d:820ms">Render<small>Visao</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:870ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:910ms">10</span><span class="mapa3__rot guia__anim" style="--d:920ms">Layout<small>publico.php</small></span></div>
              <span class="mapa3__hseta guia__anim" style="--d:970ms" aria-hidden="true"><span class="mapa3__hseta-fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>
              <div class="mapa3__passo"><span class="mapa3__knot guia__anim" style="--d:1020ms">11</span><span class="mapa3__rot guia__anim" style="--d:1030ms">Tela<small>home/index</small></span></div>
            </section>

            <span class="mapa3__varr mapa3__varr--a" aria-hidden="true"><span class="mapa3__varr-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>
            <span class="mapa3__varr mapa3__varr--b" aria-hidden="true"><span class="mapa3__varr-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>
            <span class="mapa3__varr mapa3__varr--c" aria-hidden="true"><span class="mapa3__varr-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>
          </div>
        </div>

        <p class="guia__nota"><span><b>Nenhum POST aqui.</b> O CSRF de Csrf::valido() só roda em formulários; na home ele nem é chamado.</span></p>
      </section>

      <!-- 3 ENTRADA ---------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="3 de 10: entrada e ambiente">
        <p class="guia__eyebrow">Passos 1 a 3 · Camada Entrada</p>
        <h3 class="guia__h">Entrada e ambiente</h3>
        <p class="guia__lede">Os três primeiros passos da jornada: <b>o pedido entra</b>, <b>o ambiente acende</b> e <b>a sessão nasce</b>, antes de qualquer regra de negócio existir.</p>

        <div class="trilha3">
          <aside class="trilha3__rail" aria-label="Você está na camada Entrada">
            <div class="trilha3__mapa" aria-hidden="true">
              <span class="trilha3__mini is-atual">Entrada <small>1 a 3</small></span>
              <span class="trilha3__mini">Portaria <small>4</small></span>
              <span class="trilha3__mini">Trabalho <small>5 a 8</small></span>
              <span class="trilha3__mini">Saída <small>9 a 11</small></span>
            </div>
            <div class="trilha3__zona">
              <b>Entrada</b>
              <p>O PHP atende o pedido e prepara tudo que as outras camadas usam depois.</p>
              <code>index.php → bootstrap.php → sessoes</code>
              <small>Passos 1 a 3</small>
            </div>
          </aside>

          <div class="trilha3__trilha">
            <article class="pass3 pass3--1 guia__anim" style="--d:60ms">
              <span class="pass3__knot">1</span>
              <span class="pass3__icone"><svg viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M4 9h16"/></svg></span>
              <span class="pass3__texto">
                <h4>O pedido chega</h4>
                <code>public/index.php</code>
                <p>Arquivo que existe em public/ sai direto, sem passar pelo PHP. O resto entra na aplicação.</p>
              </span>
              <span class="pass3__chips">
                <code>REQUEST_URI</code>
                <code>cli-server</code>
                <code>Caddy</code>
              </span>
            </article>

            <span class="pass3__seta guia__anim" style="--d:150ms" aria-hidden="true"><span class="pass3__seta-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>

            <article class="pass3 pass3--2 guia__anim" style="--d:230ms">
              <span class="pass3__knot">2</span>
              <span class="pass3__icone"><svg viewBox="0 0 24 24"><path d="M12 3v3M12 18v3M3 12h3M18 12h3M6 6l2 2M16 16l2 2M18 6l-2 2M8 16l-2 2"/><circle cx="12" cy="12" r="4.2"/></svg></span>
              <span class="pass3__texto">
                <h4>Prepara o ambiente</h4>
                <code>app/config/bootstrap.php</code>
                <p>Define RAIZ, lê o .env, registra o autoloader e as constantes, e liga o tratador de exceções.</p>
              </span>
              <span class="pass3__chips">
                <code>RAIZ</code>
                <code>.env</code>
                <code>autoloader</code>
                <code>exceções → 500</code>
              </span>
            </article>

            <span class="pass3__seta guia__anim" style="--d:340ms" aria-hidden="true"><span class="pass3__seta-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>

            <article class="pass3 pass3--3 guia__anim" style="--d:420ms">
              <span class="pass3__knot">3</span>
              <span class="pass3__icone"><svg viewBox="0 0 24 24"><ellipse cx="12" cy="5.6" rx="7.6" ry="2.9"/><path d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/><path d="M4.4 12c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9"/></svg></span>
              <span class="pass3__texto">
                <h4>A sessão nasce</h4>
                <code>Sessao::iniciar() → SessaoBanco</code>
                <p>A memória do visitante fica na tabela sessoes do Supabase, não em arquivo.</p>
              </span>
              <span class="pass3__chips">
                <code>sessoes</code>
                <code>HttpOnly</code>
                <code>SameSite=Lax</code>
                <code>Secure em HTTPS</code>
              </span>
            </article>
          </div>
        </div>

        <p class="guia__nota"><span><b>Nada disso se repete.</b> Uma rota nova ganha sessão e tratamento de erro só por existir.</span></p>
      </section>

      <!-- 4 PORTÃO ------------------------------------------------------ -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="4 de 10: o portão da rota">
        <p class="guia__eyebrow">Passo 4 · Portaria</p>
        <h3 class="guia__h">O portão da rota</h3>
        <p class="guia__lede">O Roteador compara <b>método e caminho exatos</b>. Não há regra dinâmica, filtro ou coringa: cada rota está escrita em <code>rotas.php</code>.</p>

        <div class="trilha3 portao__colunas">
          <aside class="trilha3__rail">
            <div class="trilha3__mapa" aria-hidden="true">
              <span class="trilha3__mini">Entrada <small>1 a 3</small></span>
              <span class="trilha3__mini is-atual">Portaria <small>4</small></span>
              <span class="trilha3__mini">Trabalho <small>5 a 8</small></span>
              <span class="trilha3__mini">Saída <small>9 a 11</small></span>
            </div>
            <div class="trilha3__zona">
              <small>Camada · Portaria</small>
              <b>Roteador::despachar()</b>
              <p>Compara método e caminho exatos. Sem rota, 404; achou, chama o método do controlador.</p>
              <code>rotas.php · erros/404</code>
            </div>
          </aside>

          <div class="portao">
            <article class="portao__saida portao__saida--no guia__anim" style="--d:180ms">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></svg>
              <h4>Não achou: 404</h4>
              <p>Nenhuma rota bate com método e caminho. Cai na tela de página não encontrada.</p>
              <code class="portao__mono">http_response_code(404)</code>
              <span class="portao__status">HTTP 404 · erros/404</span>
            </article>

            <span class="portao__seta portao__seta--no guia__anim" style="--d:120ms" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 6l-6 6 6 6"/><path d="M4 12h16"/></svg><span class="portao__seta-fio"></span></span>

            <div class="portao__centro">
              <div class="portao__circulo guia__anim" style="--d:0ms"><span>A rota<br>existe?</span></div>
              <code class="guia__anim" style="--d:130ms">Roteador::despachar()</code>
              <code class="guia__anim" style="--d:200ms">['GET', '/',<br>[HomeControlador::class, 'index'], 'publico']</code>
            </div>

            <span class="portao__seta portao__seta--ok guia__anim" style="--d:260ms" aria-hidden="true"><span class="portao__seta-fio"></span><svg viewBox="0 0 24 24"><path d="M14 6l6 6-6 6"/><path d="M20 12H4"/></svg></span>

            <article class="portao__saida portao__saida--ok guia__anim" style="--d:300ms">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.4 2.4 4.6-5"/></svg>
              <h4>GET / → publico</h4>
              <p>Chama (new HomeControlador())-&gt;index(). Acesso publico: nem login, nem gerente.</p>
              <code class="portao__mono">HomeControlador::index()</code>
              <span class="portao__status">HTTP 200 · home/index</span>
            </article>
          </div>
        </div>

        <ul class="guia__chips guia__chips--rumo guia__anim" style="--d:420ms">
          <li>acesso login: exige sessão</li>
          <li>acesso gerente: Auth::eGerente() ou 403</li>
          <li>POST: Csrf::valido() antes do acesso</li>
        </ul>
      </section>

      <!-- 5 CONTROLE E BANCO -------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="5 de 10: o controlador e as três consultas">
        <p class="guia__eyebrow">Passos 5 a 8 · Trabalho</p>
        <h3 class="guia__h">O controlador e as três consultas</h3>
        <p class="guia__lede">O controlador não decide regra: só <b>monta os dados</b> e manda renderizar. Quem fala com o banco são os serviços, que falam com os dados.</p>

        <div class="trilha3 trab5">
          <aside class="trilha3__rail" aria-label="Você está na camada Trabalho">
            <div class="trilha3__mapa" aria-hidden="true">
              <span class="trilha3__mini">Entrada <small>1 a 3</small></span>
              <span class="trilha3__mini">Portaria <small>4</small></span>
              <span class="trilha3__mini is-atual">Trabalho <small>5 a 8</small></span>
              <span class="trilha3__mini">Saída <small>9 a 11</small></span>
            </div>
            <div class="trilha3__zona">
              <small>Camada · Trabalho</small>
              <b>Monta, sem decidir</b>
              <p>O controlador não decide regra nem escreve SQL: zera os três arrays, chama as consultas e manda renderizar.</p>
              <code>HomeControlador → ProdutoServico → ProdutoDados</code>
              <small>Passos 5 a 8</small>
            </div>
          </aside>

          <div class="trab5__trilha">
            <div class="trab5__faixa trab5__faixa--ctl guia__anim" style="--d:60ms">
              <span class="trab5__knot">5</span>
              <span class="trab5__icone trab5__icone--ctl"><svg viewBox="0 0 24 24"><circle cx="6.5" cy="8" r="2.1"/><path d="M12.5 8h7M3 8h1.4M3 12.5h14M20.5 12.5H21M3 17h6.5M13.5 17h7.5"/><circle cx="11" cy="17" r="2.1"/><circle cx="18.5" cy="12.5" r="2.1"/></svg></span>
              <span class="trab5__texto">
                <h4>O controlador monta o pedido</h4>
                <code>HomeControlador::index()</code>
                <p>try/catch: em erro, <b>error_log</b> e a home abre com as seções vazias. Sem regra, sem SQL.</p>
              </span>
              <span class="trab5__chips">
                <code>$destaques</code>
                <code>$maisCurtidos</code>
                <code>$categorias</code>
              </span>
            </div>

            <span class="trab5__seta guia__anim" style="--d:150ms" aria-hidden="true"><span class="trab5__seta-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>

            <div class="trab5__consultas">
              <article class="trab5__consulta guia__anim" style="--d:200ms">
                <span class="trab5__knot trab5__knot--md">6</span>
                <span class="trab5__icone"><svg viewBox="0 0 24 24"><path d="M12 3l2.7 5.5 6 .9-4.3 4.2 1 6-5.4-2.8-5.4 2.8 1-6L4.3 9.4l6-.9L12 3z"/></svg></span>
                <h4>Destaques</h4>
                <code>ProdutoServico::destaques(6)</code>
                <span class="trab5__chain">→ ProdutoDados::destaques(6)</span>
                <small>p.ativo AND p.destaque · JOIN bordados, categorias, matrizes e pecas</small>
              </article>

              <article class="trab5__consulta guia__anim" style="--d:280ms">
                <span class="trab5__knot trab5__knot--md">7</span>
                <span class="trab5__icone"><svg viewBox="0 0 24 24"><path d="M12 20s-6.2-3.8-8.5-8C2 8.5 4.5 5.5 7.5 5.5c1.8 0 3.4 1 4.5 2.4 1.1-1.4 2.7-2.4 4.5-2.4 3 0 5.5 3 4 6.5-2.3 4.2-8.5 8-8.5 8z"/></svg></span>
                <h4>Mais curtidos</h4>
                <code>HubServico::maisCurtidos(6, Auth::id() ?? 0)</code>
                <span class="trab5__chain">→ HubDados::maisCurtidos(6, $usuario)</span>
                <small>hub sem oculto · curtidas e comentários por subconsulta</small>
              </article>

              <article class="trab5__consulta guia__anim" style="--d:360ms">
                <span class="trab5__knot trab5__knot--md">8</span>
                <span class="trab5__icone"><svg viewBox="0 0 24 24"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg></span>
                <h4>Categorias</h4>
                <code>ProdutoServico::categorias()</code>
                <span class="trab5__chain">→ ProdutoDados::categorias()</span>
                <small>nome · slug · mockup de cada linha</small>
              </article>
            </div>

            <span class="trab5__seta guia__anim" style="--d:440ms" aria-hidden="true"><span class="trab5__seta-fio"></span><svg viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M7 13l5 5 5-5"/></svg></span>

            <div class="trab5__faixa trab5__faixa--pdo guia__anim" style="--d:500ms">
              <span class="trab5__icone trab5__icone--pdo"><svg viewBox="0 0 24 24"><ellipse cx="12" cy="5.6" rx="7.6" ry="2.9"/><path d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/><path d="M4.4 12c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9"/></svg></span>
              <span class="trab5__texto trab5__texto--inline">
                <h4>Só agora o banco escuta</h4>
                <code>Conexao::pdo()</code>
              </span>
              <span class="trab5__chips trab5__chips--pdo">
                <code>pgsql :6543</code>
                <code>sslmode=require</code>
                <code>prepares emulados</code>
                <code>SELECT 1 na 1ª conexão</code>
              </span>
            </div>
          </div>
        </div>

        <p class="guia__nota guia__nota--no"><span><b>Banco fora do ar não derruba a vitrine.</b> O catch grava no log e a home abre com as seções vazias.</span></p>
      </section>

      <!-- 6 RENDER -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="6 de 10: da tela ao HTML final">
        <p class="guia__eyebrow">Passos 9 e 10 · Saída</p>
        <h3 class="guia__h">Da tela ao HTML final</h3>
        <p class="guia__lede">A Visao renderiza a tela em um <b>buffer</b>, entrega o HTML como <b>$conteudo</b> e só então o layout monta a página completa.</p>

        <div class="trilha3">
          <aside class="trilha3__rail" aria-label="Você está na camada Saída">
            <div class="trilha3__mapa" aria-hidden="true">
              <span class="trilha3__mini">Entrada <small>1 a 3</small></span>
              <span class="trilha3__mini">Portaria <small>4</small></span>
              <span class="trilha3__mini">Trabalho <small>5 a 8</small></span>
              <span class="trilha3__mini is-atual">Saída <small>9 a 11</small></span>
            </div>
            <div class="trilha3__zona">
              <small>Camada · Saída</small>
              <b>Tudo em memória</b>
              <p>A tela não imprime: roda dentro de um buffer e devolve uma string; só o layout monta o HTML final.</p>
              <code>Visao::render() → ob_start() → ob_get_clean()</code>
              <small>Passos 9 e 10</small>
            </div>
          </aside>

          <ol class="pipeline pipeline--auth">
            <li class="pipeline__passo guia__anim" style="--d:40ms">
              <span class="pipeline__tag">PASSO 9</span>
              <span class="pipeline__icone"><svg viewBox="0 0 24 24" class="fx fx--render"><rect class="fx__moldura" pathLength="100" x="3.5" y="4" width="17" height="16" rx="2.2"/><circle class="fx__dot" cx="7.4" cy="7.8" r="0.85" fill="currentColor" stroke="none"/><path class="fx__linha fx__linha--1" pathLength="100" d="M6.8 12.2h5.6"/><path class="fx__linha fx__linha--2" pathLength="100" d="M6.8 15.6h9.6"/></svg></span>
              <code>Visao::render('home/index', $dados)</code>
              <p>Aceita o nome da tela e os dados, e resolve o caminho dentro de <b>app/telas</b>.</p>
              <span class="pipeline__chips"><code>resolver()</code><code>'../' recusado</code></span>
            </li>

            <li class="pipeline__passo guia__anim" style="--d:150ms">
              <span class="pipeline__tag">PASSO 9</span>
              <span class="pipeline__icone"><svg viewBox="0 0 24 24" class="fx fx--buffer"><path class="fx__gota" d="M12 2.3c-1.2 1.6-2 2.7-2 3.8a2 2 0 0 0 4 0c0-1.1-.8-2.2-2-3.8z" fill="currentColor" stroke="none"/><path class="fx__poco" d="M4.4 10.5h15.2v6.6a2.3 2.3 0 0 1-2.3 2.3H6.7a2.3 2.3 0 0 1-2.3-2.3z"/><path class="fx__nivel fx__nivel--1" pathLength="100" d="M8 13.6h8"/><path class="fx__nivel fx__nivel--2" pathLength="100" d="M8 16.8h5.2"/></svg></span>
              <code>buffer: extract($dados, EXTR_SKIP)</code>
              <p><b>ob_start()</b> antes do require: a tela roda com as variáveis, sem sobrescrever nada interno.</p>
              <span class="pipeline__chips"><code>ob_start()</code><code>ob_get_clean()</code></span>
            </li>

            <li class="pipeline__passo guia__anim" style="--d:260ms">
              <span class="pipeline__tag">PASSO 9</span>
              <span class="pipeline__icone"><svg viewBox="0 0 24 24" class="fx fx--conteudo"><path class="fx__moldura" pathLength="100" d="M6 3.5h8.5l3.5 3.5v13.5H6z"/><path class="fx__dobra" pathLength="100" d="M14.5 3.5v3.5H18"/><path class="fx__teclada fx__teclada--1" pathLength="100" d="M9 10.6h6"/><path class="fx__teclada fx__teclada--2" pathLength="100" d="M9 13.6h6"/><path class="fx__caret" d="M9 16.6h3.2"/></svg></span>
              <code>$conteudo = HTML da tela</code>
              <p>O output vira uma string única, pronta para o layout montar a página.</p>
              <span class="pipeline__chips"><code>$conteudo</code><code>$titulo ?? PLATAFORMA</code></span>
            </li>

            <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
              <span class="pipeline__tag">PASSO 10</span>
              <span class="pipeline__icone"><svg viewBox="0 0 24 24" class="fx fx--ar"><rect class="fx__tela" pathLength="100" x="2.5" y="3.8" width="19" height="13.4" rx="1.8"/><path class="fx__suporte" d="M8.5 20.8h7"/><g class="fx__folha"><rect x="8.8" y="7.4" width="6.4" height="7.6" rx="1.1" fill="currentColor" fill-opacity="0.15"/><path d="M10.8 10.5h2.6"/><path d="M10.8 13h1.7"/></g></svg></span>
              <code>app/telas/layouts/publico.php</code>
              <p>Só então o layout fecha o head, encaixa <b>header</b> e <b>footer</b> e entrega tudo de uma vez.</p>
              <span class="pipeline__chips"><code>main.js?v=filemtime</code><code>meta csrf</code></span>
            </li>
          </ol>
        </div>

        <ul class="guia__chips guia__chips--rumo guia__anim" style="--d:240ms">
          <li>layout padrão: publico</li>
          <li>título: $titulo da tela, ou PLATAFORMA</li>
          <li>main.css e main.js com filemtime furam o cache</li>
        </ul>
      </section>

      <!-- 7 ARQUIVOS ------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="7 de 10: arquivos por onde o pedido passa">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A árvore dos arquivos</h3>
        <p class="guia__lede">Vinte e um arquivos, sete pastas. Se algo aqui mudar de nome, o pedido muda de caminho.</p>

        <div class="ref__papel arvore guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">$ tree public app</span><span class="ref__tag">REF 07 · 21 arquivos · 7 pastas</span></div>
          <div class="arvore__cols">

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>public/</code><small>a entrada</small></div>
                <p class="arvore__file"><code>index.php</code><span class="arvore__lider"></span><span class="arvore__role">o pedido entra aqui</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/config/</code><small>a configuração</small></div>
                <p class="arvore__file"><code>bootstrap.php</code><span class="arvore__lider"></span><span class="arvore__role">RAIZ, .env, autoloader</span></p>
                <p class="arvore__file"><code>rotas.php</code><span class="arvore__lider"></span><span class="arvore__role">a lista das rotas</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/core/</code><small>o núcleo</small></div>
                <p class="arvore__file"><code>Sessao.php</code><span class="arvore__lider"></span><span class="arvore__role">liga a sessão</span></p>
                <p class="arvore__file"><code>SessaoBanco.php</code><span class="arvore__lider"></span><span class="arvore__role">sessão na tabela sessoes</span></p>
                <p class="arvore__file"><code>Roteador.php</code><span class="arvore__lider"></span><span class="arvore__role">compara método e caminho</span></p>
                <p class="arvore__file"><code>Conexao.php</code><span class="arvore__lider"></span><span class="arvore__role">PDO para o Supabase</span></p>
                <p class="arvore__file"><code>Visao.php</code><span class="arvore__lider"></span><span class="arvore__role">renderiza no buffer</span></p>
                <p class="arvore__file"><code>Auth.php</code><span class="arvore__lider"></span><span class="arvore__role">quem está logado</span></p>
                <p class="arvore__file"><code>Csrf.php</code><span class="arvore__lider"></span><span class="arvore__role">o token de formulário</span></p>
                <p class="arvore__file"><code>ajudantes.php</code><span class="arvore__lider"></span><span class="arvore__role">e(), link_whatsapp()</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/controladores/</code><small>o maestro</small></div>
                <p class="arvore__file"><code>HomeControlador.php</code><span class="arvore__lider"></span><span class="arvore__role">monta a home</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/processamento/</code><small>as regras</small></div>
                <p class="arvore__file"><code>ProdutoServico.php</code><span class="arvore__lider"></span><span class="arvore__role">destaques, categorias</span></p>
                <p class="arvore__file"><code>HubServico.php</code><span class="arvore__lider"></span><span class="arvore__role">mais curtidos</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/dados/</code><small>o SQL</small></div>
                <p class="arvore__file"><code>ProdutoDados.php</code><span class="arvore__lider"></span><span class="arvore__role">o SQL da vitrine</span></p>
                <p class="arvore__file"><code>HubDados.php</code><span class="arvore__lider"></span><span class="arvore__role">o SQL do Hub</span></p>
              </section>
            </div>

            <div class="arvore__col">
              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/home/</code><small>a tela</small></div>
                <p class="arvore__file"><code>index.php</code><span class="arvore__lider"></span><span class="arvore__role">a home</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/layouts/</code><small>o molde</small></div>
                <p class="arvore__file"><code>publico.php</code><span class="arvore__lider"></span><span class="arvore__role">o layout padrão</span></p>
              </section>

              <section class="arvore__patch">
                <div class="arvore__patch-head"><code>app/telas/parciais/</code><small>as peças</small></div>
                <p class="arvore__file"><code>header.php</code><span class="arvore__lider"></span><span class="arvore__role">cabeçalho e guia</span></p>
                <p class="arvore__file"><code>footer.php</code><span class="arvore__lider"></span><span class="arvore__role">rodapé e scripts</span></p>
                <p class="arvore__file"><code>peca_bordada.php</code><span class="arvore__lider"></span><span class="arvore__role">a peça renderizada</span></p>
              </section>
            </div>

          </div>
        </div>

        <p class="guia__nota"><span><b>O padrão se repete.</b> Outra rota pública, outro controlador, o mesmo caminho: bootstrap, sessão, roteador, serviço, dados, tela.</span></p>
      </section>

      <!-- 8 VARIÁVEIS ------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="8 de 10: as variáveis que circulam">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A tabela de símbolos</h3>
        <p class="guia__lede">O que entra, o que atravessa e o que nasce no caminho. Nenhuma delas carrega senha.</p>

        <div class="ref__papel tabela guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">$ print_r(get_defined_vars())</span><span class="ref__tag">REF 08 · 8 símbolos · 3 movimentos</span></div>

          <div class="tabela__legenda" aria-hidden="true">
            <span><i class="mov--entra"></i>entra</span>
            <span><i class="mov--atravessa"></i>atravessa</span>
            <span><i class="mov--nasce"></i>nasce</span>
          </div>

          <div class="tabela__head" aria-hidden="true">
            <span>variável</span>
            <span>nasce em</span>
            <span>carrega</span>
          </div>

          <div class="tabela__rows">
            <div class="tabela__row"><span class="tabela__var"><i class="mov--entra"></i>$_ENV</span><span class="tabela__onde">bootstrap.php · Conexao.php</span><span class="tabela__o">DB_HOST · DB_PORT · DB_NAME · DB_USER · DB_PASS · APP_ENV</span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--entra"></i>$_SESSION</span><span class="tabela__onde">Sessao::iniciar()</span><span class="tabela__o">usuario_id, flash e formulários guardados; lidos por <code>Sessao::obter()</code></span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--atravessa"></i>$destaques</span><span class="tabela__onde">HomeControlador · ProdutoServico::destaques(6)</span><span class="tabela__o">até 6 produtos em destaque, com bordado, categoria e matriz</span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--atravessa"></i>$maisCurtidos</span><span class="tabela__onde">HomeControlador · HubServico::maisCurtidos(6, $usuario)</span><span class="tabela__o">6 posts públicos do Hub, com contadores e a flag <code>curtiu</code></span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--atravessa"></i>$categorias</span><span class="tabela__onde">HomeControlador · ProdutoServico::categorias()</span><span class="tabela__o">nome, slug e mockup de cada linha de produtos</span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--atravessa"></i>$titulo</span><span class="tabela__onde">o fixo do HomeControlador</span><span class="tabela__o">&quot;Desenrola: Hub e Mostruário de Bordados&quot;, virando o <code>&lt;title&gt;</code></span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--nasce"></i>$item</span><span class="tabela__onde">o foreach da home/index.php</span><span class="tabela__o">em peca_bordada.php: pos_x · pos_y · escala · svg · mockup · cor_peca</span></div>
            <div class="tabela__row"><span class="tabela__var"><i class="mov--nasce"></i>$conteudo</span><span class="tabela__onde">Visao::render() · ob_get_clean()</span><span class="tabela__o">o HTML da tela, montado pela Visao no buffer</span></div>
          </div>
        </div>

        <p class="guia__nota"><span><b>É este caminho.</b> Quem quiser saber onde cada dado nasce, começa pelo controlador e termina no <code>peca_bordada.php</code>.</span></p>
      </section>

      <!-- 9 EXTERNAS -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="9 de 10: o que sai da plataforma">
        <p class="guia__eyebrow">Anexo de referência</p>
        <h3 class="guia__h">A alfândega</h3>
        <p class="guia__lede">Tudo que a home mostra mora no Supabase: o banco com os dados e os arquivos das peças.</p>

        <div class="ref__papel alfande guia__anim" style="--d:60ms">
          <div class="ref__barra"><span class="ref__cmd">banco e arquivos · o que sai do servidor</span><span class="ref__tag">REF 09 · 2 destinos · 0 obrigatórios</span></div>

          <div class="alfande__cena">
            <div class="alfande__dentro">
              <b>A plataforma</b>
              <small>uma aplicação PHP</small>
              <div class="alfande__mods">
                <span class="alfande__mod">vitrine · tela</span>
                <span class="alfande__mod">hub · comunidade</span>
                <span class="alfande__mod">montador · matrizes</span>
                <span class="alfande__mod">gerência · painéis</span>
              </div>
              <span class="alfande__ped">GET / · no momento do pedido</span>
            </div>

            <span class="alfande__cabo" aria-hidden="true"></span>
            <span class="alfande__cabo" aria-hidden="true"></span>

            <div class="alfande__row alfande__row--supa">
              <svg viewBox="0 0 24 24" aria-hidden="true"><ellipse cx="12" cy="5.6" rx="7.6" ry="2.9"/><path d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/><path d="M4.4 12c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9"/></svg>
              <div class="alfande__txt">
                <b>Supabase Postgres</b>
                <code>Conexao::pdo() · pgsql :6543</code>
                <p>A cada visita, o servidor busca no banco as três listas que a tela mostra: os destaques, os mais curtidos e as categorias. O banco fica no Supabase, um Postgres gerenciado, e a conversa vai cifrada pela porta 6543.</p>
                <p><b>Se o banco cair, a página abre vazia, mas não quebra:</b> o erro vira log e o visitante vê a vitrine sem produtos.</p>
                <div class="alfande__specs">
                  <span>banco gerenciado</span>
                  <span>porta 6543</span>
                  <span>conexão cifrada (SSL)</span>
                  <span>1 conexão por thread</span>
                </div>
                <small class="alfande__onde">app/core/Conexao.php</small>
              </div>
              <span class="alfande__selo">Supabase</span>
            </div>

            <div class="alfande__row alfande__row--supa">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8.2h16l-1.5 10.6a2.2 2.2 0 0 1-2.2 2H7.7a2.2 2.2 0 0 1-2.2-2L4 8.2z"/><path d="M8.5 8V6.4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2V8"/></svg>
              <div class="alfande__txt">
                <b>Supabase Storage</b>
                <code>StorageServico::urlPublica()</code>
                <p>As fotos das peças e os desenhos das matrizes também moram no Supabase, num bucket público chamado <b>mostruario</b>. O servidor não baixa nada: ele só monta o endereço e o navegador do visitante busca direto de lá.</p>
                <p>Matrizes antigas continuam como <b>SVG embutido (data URI)</b>, sem nenhuma viagem de rede.</p>
                <div class="alfande__specs">
                  <span>bucket público 'mostruario'</span>
                  <span>URL direta, sem baixar</span>
                  <span>fotos e matrizes</span>
                </div>
                <small class="alfande__onde">app/processamento/StorageServico.php</small>
              </div>
              <span class="alfande__selo">Supabase</span>
            </div>
          </div>
        </div>

        <p class="guia__nota"><span><b>Só isso sai do servidor: o banco e os arquivos.</b> As fontes vêm do Google Fonts e o orçamento abre no WhatsApp, ambos na tela; do backend, só o Supabase.</span></p>
      </section>

      <!-- 10 CSS -------------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="10 de 10: como o CSS e o JS entram na página">
        <p class="guia__eyebrow">Saída · Estilo</p>
        <h3 class="guia__h">Como o CSS e o JS entram na página</h3>
        <p class="guia__lede">Dois arquivos, sempre os mesmos, nem um a mais. Siga o caminho de cada um, do botão que atende o pedido até a tela pintar.</p>

        <div class="est2">

          <div class="est2__banda est2__banda--css">
            <div class="est2__carga">
              <span class="est2__sigla">CSS</span>
              <b>main.css</b>
              <small>entra no &lt;head&gt;<br>entra sempre</small>
            </div>

            <div class="est2__fluxo">
              <div class="est2__passo guia__anim" style="--d:60ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="4.5" y="3.5" width="15" height="17" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M8.5 9h7M8.5 12.5h7M8.5 16h4.5"/></svg></span>
                <span class="est2__knot">1</span>
                <span class="est2__txt"><h4>O layout</h4><p>o <b>publico.php</b> decide onde tudo entra</p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:120ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo guia__anim" style="--d:180ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9.5 10.5a4 4 0 0 1 5.6 0l2.4 2.4a4 4 0 0 1-5.6 5.6l-1.2-1.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M14.5 13.5a4 4 0 0 1-5.6 0l-2.4-2.4a4 4 0 0 1 5.6-5.6l1.2 1.2"/></svg></span>
                <span class="est2__knot">2</span>
                <span class="est2__txt"><h4>O único link</h4><p>no head: <code>assets/css/main.css?v=mtime</code></p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:240ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo guia__anim" style="--d:300ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M19 12a7 7 0 1 1-2.05-4.95"/><path class="est2-desenha est2-d2" pathLength="100" d="M19 5v4.5h-4.5"/></svg></span>
                <span class="est2__knot">3</span>
                <span class="est2__txt"><h4>O cache</h4><p><code>?v=</code> do <code>filemtime</code>: mudou o arquivo, muda o número</p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:360ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo est2__passo--camadas guia__anim" style="--d:420ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M5 7.5h14M5 12h14M5 16.5h14"/></svg></span>
                <span class="est2__knot">4</span>
                <span class="est2__txt">
                  <h4>A folha única</h4>
                  <span class="est2__chips"><code>reset</code><code>variables</code><code>typography</code><code>utilities</code><code>components</code><code>pages</code><code>responsive</code></span>
                </span>
              </div>

              <span class="est2__seta guia__anim" style="--d:480ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo guia__anim" style="--d:540ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3.2s5.6 6.1 5.6 10.2a5.6 5.6 0 0 1-11.2 0C6.4 9.3 12 3.2 12 3.2z"/></svg></span>
                <span class="est2__knot">5</span>
                <span class="est2__txt"><h4>A tela vestida</h4><p>última regra vence; o <b>responsive</b> fecha</p></span>
              </div>
            </div>
          </div>

          <div class="est2__banda est2__banda--js">
            <div class="est2__carga est2__carga--js">
              <span class="est2__sigla est2__sigla--js">JS</span>
              <b>main.js</b>
              <small>antes do &lt;/body&gt;<br>type="module"</small>
            </div>

            <div class="est2__fluxo">
              <div class="est2__passo guia__anim" style="--d:100ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M8.5 8L4 12.5 8.5 17"/><path class="est2-desenha est2-d2" pathLength="100" d="M15.5 8l4.5 4.5-4.5 4.5"/></svg></span>
                <span class="est2__knot">1</span>
                <span class="est2__txt"><h4>O módulo único</h4><p>um <code>type="module"</code>, nada mais</p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:160ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo guia__anim" style="--d:220ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M13 2.5L4.8 14h5.7l-1 7.5 8.2-11.5h-5.7l1-7.5z"/></svg></span>
                <span class="est2__knot">2</span>
                <span class="est2__txt"><h4>A versão</h4><p><code>?v=</code> do <code>filemtime</code>: mudou o arquivo, muda o número</p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:280ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo guia__anim" style="--d:340ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 7h9M4 12h9M4 17h9"/><path class="est2-desenha est2-d2" pathLength="100" d="M17 9.5l3.5 2.5L17 14.5"/></svg></span>
                <span class="est2__knot">3</span>
                <span class="est2__txt"><h4>Os imports</h4><p><code>main.js</code> traz header, showcase, guia, moeda…</p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:400ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo guia__anim" style="--d:460ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="2.5" y="9" width="19" height="6" rx="3"/><circle class="est2-desenha est2-d2" pathLength="100" cx="16.8" cy="12" r="1.7"/></svg></span>
                <span class="est2__knot">4</span>
                <span class="est2__txt"><h4>Liga sempre</h4><p>header · menu · reveal · toasts · guia</p></span>
              </div>

              <span class="est2__seta guia__anim" style="--d:520ms" aria-hidden="true"><span class="est2__fio"></span><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></span>

              <div class="est2__passo est2__passo--ganchos guia__anim" style="--d:580ms">
                <span class="est2__ic"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M3 11V4h7l9 9-7 7-9-9z"/><circle class="est2-desenha est2-d2" pathLength="100" cx="6.8" cy="7.8" r="1.3"/></svg></span>
                <span class="est2__knot">5</span>
                <span class="est2__txt">
                  <h4>Liga por gancho</h4>
                  <p>só onde existe <code>[data-…]</code></p>
                  <span class="est2__chips"><code>showcase</code><code>montador</code><code>tutorial</code><code>combinacao</code><code>moeda</code><code>mascara</code></span>
                </span>
              </div>
            </div>
          </div>

        </div>

        <p class="guia__nota"><span><b>Sem JS, tudo aparece normal.</b> O CSS só esconde o <code>[data-reveal]</code> quando o <code>&lt;html&gt;</code> ganha a classe <code>js</code>; e o <code>IntersectionObserver</code> revela conforme a rolagem.</span></p>
      </section>

    </div><!-- /trilho -->

    <footer class="guia__deck-foot">
      <button class="guia__nav guia__nav--anterior" type="button" data-guia-nav="-1" aria-label="Slide anterior">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        <span>Anterior</span>
      </button>

      <div class="guia__progresso" aria-hidden="true"><span class="guia__progresso-fio" data-guia-progresso></span></div>

      <div class="guia__pontos" role="tablist" aria-label="Ir para o slide">
        <?php for ($i = 1; $i <= 10; $i++): ?>
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
