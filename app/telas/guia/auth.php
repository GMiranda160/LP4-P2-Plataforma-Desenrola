<?php
/**
 * Guia: como o acesso funciona (Auth).
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Popup em formato de deck: 5 slides, um por vez, com navegação por setas,
 * botões, pontos e teclado. Explica o ciclo completo de autenticação:
 * cadastro, login e recuperação de senha. O que a home já explica
 * (portão de rota e CSRF, sessão na tabela sessoes, renderização pela
 * Visao) fica no guia da home; aqui só o que muda entre uma porta e outra.
 * Segue os fatos do código; se o fluxo mudar, atualize aqui.
 */
?>
<div class="guia guia--deck guia--auth" id="guia-auth" role="dialog" aria-modal="true" aria-labelledby="guia-auth-titulo" hidden data-guia-deck>
  <div class="guia__dialog guia__deck-frame">

    <header class="guia__deck-head">
      <div>
        <p class="guia__eyebrow">Guia · Auth</p>
        <h2 class="guia__deck-title" id="guia-auth-titulo">Como o acesso funciona</h2>
      </div>
      <div class="guia__deck-meta">
        <span class="guia__contador" data-guia-contador aria-hidden="true">1 / 5</span>
        <button class="guia__fechar" type="button" data-guia-fechar aria-label="Fechar guia">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </header>

    <div class="guia__trilho">

      <!-- 1 CAPA ------------------------------------------------------- -->
      <section class="guia__slide is-active" role="group" aria-roledescription="slide" aria-label="1 de 5: capa">
        <div class="guia__capa">
          <div class="guia__capa-texto">
            <p class="guia__eyebrow guia__anim" style="--d:0ms">Guia da plataforma</p>
            <h3 class="guia__capa-h"><span class="guia__anim" style="--d:60ms">Três portas,</span>
              <span class="guia__anim guia__capa-h-sub" style="--d:160ms">um corredor só</span></h3>
            <p class="guia__lede guia__anim" style="--d:240ms">
              Os três POSTs pousam no mesmo bastidor: o <code>AuthServico</code>
              confere e carimba o check. Você quer ver o que acontece com cada
              um deles?
            </p>
            <ul class="guia__chips guia__anim" style="--d:320ms">
              <li>POST /cadastro</li>
              <li>POST /login</li>
              <li>POST /recuperar-senha</li>
              <li>sessão</li>
            </ul>
            <p class="guia__dica guia__anim" style="--d:400ms">Use as setas, os botões ou os pontos abaixo. ESC fecha.</p>
          </div>
          <div class="guia__capa-arte guia__anim" style="--d:200ms" aria-hidden="true">
            <svg class="gl-cena" viewBox="0 0 244 172" fill="none" role="img" aria-label="Os três POSTs voando para o bastidor; o AuthServico carimba o check">
              <path class="gl-chao" d="M14 156 H230"/>

              <circle class="gl-hoop" cx="180" cy="80" r="42"/>
              <circle class="gl-stitch" cx="180" cy="80" r="31"/>
              <rect class="gl-hoop-top" x="172" y="25" width="16" height="10" rx="3"/>
              <text class="gl-hoop-txt" x="180" y="140" text-anchor="middle">AuthServico</text>

              <g class="gl-arq gl-arq-1">
                <path class="gl-arq-doc" d="M0 0 h13 l7 7 v18 a3 3 0 0 1 -3 3 H3 a3 3 0 0 1 -3 -3 V3 a3 3 0 0 1 3 -3 z"/>
                <path class="gl-arq-doc" d="M13 0 v7 h7"/>
                <path class="gl-arq-linha" d="M6 14 h11 M6 20 h8"/>
                <text class="gl-arq-txt" x="10" y="42" text-anchor="middle">cadastro</text>
              </g>

              <g class="gl-arq gl-arq-2">
                <path class="gl-arq-doc" d="M0 0 h13 l7 7 v18 a3 3 0 0 1 -3 3 H3 a3 3 0 0 1 -3 -3 V3 a3 3 0 0 1 3 -3 z"/>
                <path class="gl-arq-doc" d="M13 0 v7 h7"/>
                <path class="gl-arq-linha" d="M6 14 h11 M6 20 h8"/>
                <text class="gl-arq-txt" x="10" y="42" text-anchor="middle">login</text>
              </g>

              <g class="gl-arq gl-arq-3">
                <path class="gl-arq-doc" d="M0 0 h13 l7 7 v18 a3 3 0 0 1 -3 3 H3 a3 3 0 0 1 -3 -3 V3 a3 3 0 0 1 3 -3 z"/>
                <path class="gl-arq-doc" d="M13 0 v7 h7"/>
                <path class="gl-arq-linha" d="M6 14 h11 M6 20 h8"/>
                <text class="gl-arq-txt" x="10" y="42" text-anchor="middle">recuperação</text>
              </g>

              <path class="gl-check" d="M163 78 l11 11 23 -27"/>
            </svg>
          </div>
        </div>
      </section>

      <!-- 2 GERAL ------------------------------------------------------ -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="2 de 5: visão geral do ciclo">
        <p class="guia__eyebrow">Visão geral do ciclo</p>
        <h3 class="guia__h">Quem fala com quem</h3>
        <p class="guia__lede">Dos três POSTs ao destino: quem recebe, quem confere, quem responde e <b>onde cada coisa mora</b>.</p>

        <svg class="gl-grafo" viewBox="0 0 800 384" fill="none" role="img" aria-label="Grafo do Auth: POST /cadastro, POST /login e POST /recuperar-senha entram no AuthServico; as respostas saem por Auth::entrar() para /montador ou /gerente ou /hub, e por atualizarSenha() para /login; tudo conversa com o banco pela UsuarioDados">
          <!-- linhas: ida (esquerda → bastidor) -->
          <path class="gl-grafo__linha" d="M186 60 C 250 60, 315 92, 350 137"/>
          <path class="gl-grafo__linha" d="M186 180 H 336"/>
          <path class="gl-grafo__linha" d="M186 300 C 250 300, 315 268, 350 223"/>

          <!-- linhas: volta (bastidor → destinos) -->
          <path class="gl-grafo__linha" d="M450 137 C 485 92, 550 60, 614 60"/>
          <path class="gl-grafo__linha" d="M464 180 H 614"/>
          <path class="gl-grafo__linha" d="M450 223 C 485 268, 550 300, 614 300"/>

          <!-- ida e volta com o banco -->
          <path class="gl-grafo__linha" d="M400 244 V 330"/>

          <!-- nós da esquerda: os três POSTs -->
          <g>
            <rect class="gl-grafo__no" x="6" y="28" width="180" height="64" rx="12"/>
            <text class="gl-grafo__titulo" x="96" y="55" text-anchor="middle">POST /cadastro</text>
            <text class="gl-grafo__cap" x="96" y="73" text-anchor="middle">nome · senha · pergunta</text>
          </g>
          <g>
            <rect class="gl-grafo__no" x="6" y="148" width="180" height="64" rx="12"/>
            <text class="gl-grafo__titulo" x="96" y="175" text-anchor="middle">POST /login</text>
            <text class="gl-grafo__cap" x="96" y="193" text-anchor="middle">usuário · senha</text>
          </g>
          <g>
            <rect class="gl-grafo__no" x="6" y="268" width="180" height="64" rx="12"/>
            <text class="gl-grafo__titulo" x="96" y="295" text-anchor="middle">POST /recuperar-senha</text>
            <text class="gl-grafo__cap" x="96" y="313" text-anchor="middle">pergunta de segurança</text>
          </g>

          <!-- nós da direita: os destinos -->
          <g>
            <rect class="gl-grafo__no" x="614" y="28" width="180" height="64" rx="12"/>
            <text class="gl-grafo__titulo" x="704" y="55" text-anchor="middle">/montador</text>
            <text class="gl-grafo__cap" x="704" y="73" text-anchor="middle">Auth::entrar() · conta nova</text>
          </g>
          <g>
            <rect class="gl-grafo__no" x="614" y="148" width="180" height="64" rx="12"/>
            <text class="gl-grafo__titulo" x="704" y="175" text-anchor="middle">/gerente · /hub</text>
            <text class="gl-grafo__cap" x="704" y="193" text-anchor="middle">Auth::entrar() · perfil decide</text>
          </g>
          <g>
            <rect class="gl-grafo__no" x="614" y="268" width="180" height="64" rx="12"/>
            <text class="gl-grafo__titulo" x="704" y="295" text-anchor="middle">/login</text>
            <text class="gl-grafo__cap" x="704" y="313" text-anchor="middle">atualizarSenha() · senha nova</text>
          </g>

          <!-- o bastidor: o AuthServico -->
          <circle class="gl-grafo__hoop" cx="400" cy="180" r="64"/>
          <circle class="gl-grafo__stitch" cx="400" cy="180" r="47"/>
          <text class="gl-grafo__hoop-txt" x="400" y="176" text-anchor="middle">Auth</text>
          <text class="gl-grafo__hoop-txt" x="400" y="196" text-anchor="middle">Servico</text>

          <!-- rótulos da conexão com o banco -->
          <text class="gl-grafo__rotulo" x="354" y="341" text-anchor="end">UsuarioDados</text>
          <text class="gl-grafo__rotulo2" x="354" y="356" text-anchor="end">SELECT · INSERT · UPDATE</text>

          <!-- o banco -->
          <path class="gl-grafo__db" d="M366 336 a34 8 0 0 1 68 0 a34 8 0 0 1 -68 0"/>
          <path class="gl-grafo__db" d="M366 336 v18 a34 8 0 0 0 68 0 v-18"/>
          <path class="gl-grafo__db" d="M366 347 a34 8 0 0 0 68 0"/>
          <text class="gl-grafo__rotulo" x="446" y="341">usuarios · sessoes</text>
          <text class="gl-grafo__rotulo2" x="446" y="356">Supabase · PostgreSQL</text>
        </svg>

        <p class="guia__nota"><span><b>Sem setas escondidas.</b> Cada requisição passa pelo Roteador (CSRF) antes de chegar ao AuthServico, e cada resposta atravessa o mesmo caminho de volta. O Roteador e a Visao estão no <b>guia da home</b>; aqui o miolo é o Auth.</span></p>
      </section>

      <!-- 3 CADASTRO ---------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="3 de 5: criar a conta">
        <p class="guia__eyebrow">Fluxo 1 · Cadastro</p>
        <h3 class="guia__h">Criar a conta</h3>
        <p class="guia__lede">Quatro perguntas antes do INSERT. <b>Se qualquer uma falhar, a tela volta com o erro</b> e só o usuário fica preenchido.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect class="est2-desenha est2-d2" pathLength="100" x="9" y="3" width="6" height="4" rx="1.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.5 13.5l2 2 3.5-4"/></svg></span>
            <code>nome · usuário · senha · pergunta</code>
            <p>Nome obrigatório; usuário de 3 a 30 (<code>a-z 0-9 . _ -</code>) e único; senha 6+; confirmação igual; pergunta das três e resposta preenchida.</p>
            <span class="pipeline__chips"><code>preg_match</code><code>mb_strlen</code><code>buscarPorEmail</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><rect class="est2-desenha" pathLength="100" x="4" y="10" width="16" height="10" rx="2"/><path class="est2-desenha est2-d2" pathLength="100" d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></span>
            <code>password_hash($senha, PASSWORD_DEFAULT)</code>
            <p>A senha vira hash <b>antes</b> de tocar no banco. A resposta da pergunta também.</p>
            <span class="pipeline__chips"><code>PASSWORD_DEFAULT</code><code>bcrypt</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.6" rx="7.6" ry="2.9"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/></svg></span>
            <code>inserir(['perfil' => 'usuario', ...]) RETURNING id</code>
            <p>INSERT com <code>ativo 't'</code> e perfil <code>usuario</code>: nenhuma tela cria gerente; gerente só nasce no seed.</p>
            <span class="pipeline__chips"><code>RETURNING id</code><code>ativo 't'</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M4 12.5l5.2 5.2L20 6.8"/></svg></span>
            <code>Auth::entrar() → /montador</code>
            <p>A conta nasce e a pessoa já entra logada, com o flash "Conta criada com sucesso!".</p>
            <span class="pipeline__chips"><code>regenerate_id</code><code>flash</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>A senha nunca volta preenchida.</b> Só o usuário fica salvo nos velhos; a senha, nunca.</span></p>
      </section>

      <!-- 4 LOGIN -------------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="4 de 5: entrar na conta">
        <p class="guia__eyebrow">Fluxo 2 · Login</p>
        <h3 class="guia__h">Entrar na conta</h3>
        <p class="guia__lede">A senha nunca é comparada, só verificada. <b>O banco só tem hash, o PHP só tem password_verify().</b></p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="8.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M3.5 12h17M12 3.5c-5 5-5 12 0 17M12 3.5c5 5 5 12 0 17"/></svg></span>
            <code>$email = mb_strtolower(trim($usuario)) . '@example.com'</code>
            <p>O e-mail completo é montado <b>no servidor</b>; o navegador manda só o usuário.</p>
            <span class="pipeline__chips"><code>trim</code><code>mb_strtolower</code><code>@example.com</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><ellipse class="est2-desenha" pathLength="100" cx="12" cy="5.6" rx="7.6" ry="2.9"/><path class="est2-desenha est2-d2" pathLength="100" d="M4.4 5.6v12.8c0 1.6 3.4 2.9 7.6 2.9s7.6-1.3 7.6-2.9V5.6"/></svg></span>
            <code>UsuarioDados::buscarPorEmail($email)</code>
            <p><b>WHERE email = :email LIMIT 1</b> com prepare: o usuário com os hashes, ou null.</p>
            <span class="pipeline__chips"><code>PDO</code><code>prepare</code><code>:email</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="8" cy="12" r="4.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M12.5 12H21M17.5 12v3.2M21 12v2.2"/></svg></span>
            <code>password_verify($senha, $hash) + conta ativa</code>
            <p>Ativo vale <code>'t'</code>, <code>1</code> ou <code>true</code>. Usuário inexistente ou senha errada recebem <b>a mesma mensagem</b>.</p>
            <span class="pipeline__chips"><code>PASSWORD_DEFAULT</code><code>mensagem genérica</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M14 4h6v16h-6M10 8l-4 4 4 4M6 12h9"/></svg></span>
            <code>Auth::entrar() → /gerente · /hub</code>
            <p><code>regenerate_id(true)</code> e sessão só com id, nome, email e perfil. O perfil decide a porta.</p>
            <span class="pipeline__chips"><code>regenerate_id</code><code>perfil</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>Hash nunca entra na sessão.</b> "Usuário ou senha inválidos." não revela se o usuário existe.</span></p>
      </section>

      <!-- 5 RECUPERAÇÃO -------------------------------------------------- -->
      <section class="guia__slide" role="group" aria-roledescription="slide" aria-label="5 de 5: esquecer a senha">
        <p class="guia__eyebrow">Fluxo 3 · Recuperação</p>
        <h3 class="guia__h">Esqueci a senha</h3>
        <p class="guia__lede">Não há e-mail: <b>a pergunta de segurança é o canal</b>. Cinco tentativas erradas e tudo recomeça.</p>

        <ol class="pipeline pipeline--auth">
          <li class="pipeline__passo guia__anim" style="--d:40ms">
            <span class="pipeline__tag">PASSO 1</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="12" cy="12" r="9.2"/><path class="est2-desenha est2-d2" pathLength="100" d="M9.5 9.3a2.6 2.6 0 0 1 5 .9c0 1.7-2.5 2-2.5 3.6"/><path class="est2-desenha est2-d2" pathLength="100" d="M12 16.8h.01"/></svg></span>
            <code>POST /recuperar-senha → perguntaDe($email)</code>
            <p>Guarda <code>recuperar_id</code>, <code>recuperar_pergunta</code> e zera <code>recuperar_tentativas</code> na sessão; usuário desconhecido recebe a mesma mensagem de sempre.</p>
            <span class="pipeline__chips"><code>Sessao::definir</code><code>mensagem genérica</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:150ms">
            <span class="pipeline__tag">PASSO 2</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha" pathLength="100" d="M12 3l7.5 3v6c0 4.4-3.2 7.6-7.5 9-4.3-1.4-7.5-4.6-7.5-9v-6L12 3z"/></svg></span>
            <code>password_verify($resposta, $resposta_hash)</code>
            <p>A resposta é comparada como hash, sem diferença de maiúsculas e espaços (<code>mb_strtolower(trim())</code>).</p>
            <span class="pipeline__chips"><code>resposta_hash</code><code>mb_strtolower</code></span>
          </li>

          <li class="pipeline__passo guia__anim" style="--d:260ms">
            <span class="pipeline__tag">PASSO 3</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><path class="est2-desenha est2-d2" pathLength="100" d="M12 8v5"/><path class="est2-desenha est2-d2" pathLength="100" d="M12 16.5h.01"/><path class="est2-desenha" pathLength="100" d="M10.3 3.9L2.5 17a2 2 0 0 0 1.7 3h15.6a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg></span>
            <code>recuperar_tentativas &gt;= 5 → recomeça</code>
            <p>Cada erro soma uma tentativa; no quinto, a recuperação é descartada e a pessoa volta ao início.</p>
            <span class="pipeline__chips"><code>5 tentativas</code><code>contagem na sessão</code></span>
          </li>

          <li class="pipeline__passo pipeline__passo--final guia__anim" style="--d:370ms">
            <span class="pipeline__tag">PASSO 4</span>
            <span class="pipeline__icone"><svg viewBox="0 0 24 24"><circle class="est2-desenha" pathLength="100" cx="8" cy="12" r="4.5"/><path class="est2-desenha est2-d2" pathLength="100" d="M12.5 12H21M17.5 12v3.2M21 12v2.2"/></svg></span>
            <code>atualizarSenha() → /login</code>
            <p>Senha nova com 6 ou mais caracteres e confirmação igual; troca o hash e devolve para a entrada.</p>
            <span class="pipeline__chips"><code>atualizarSenha</code><code>senha ≥ 6</code></span>
          </li>
        </ol>

        <p class="guia__nota"><span><b>A pergunta nasce no cadastro.</b> As contas de gerente do seed usam pergunta placeholder; a recuperação é o caminho das contas de usuário.</span></p>
      </section>

    </div><!-- /trilho -->

    <footer class="guia__deck-foot">
      <button class="guia__nav guia__nav--anterior" type="button" data-guia-nav="-1" aria-label="Slide anterior">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        <span>Anterior</span>
      </button>

      <div class="guia__progresso" aria-hidden="true"><span class="guia__progresso-fio" data-guia-progresso></span></div>

      <div class="guia__pontos" role="tablist" aria-label="Ir para o slide">
        <?php for ($i = 1; $i <= 5; $i++): ?>
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
