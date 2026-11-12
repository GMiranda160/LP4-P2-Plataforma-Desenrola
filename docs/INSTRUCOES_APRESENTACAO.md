# Instruções para continuar a apresentação "Desenrola por dentro"

Este documento passa o trabalho para outro agente. Leia tudo antes de editar.

## 1. O que é

`docs/APRESENTACAO_ARQUITETURA.html` é uma apresentação em 22 slides horizontais (16:9) que explica
a arquitetura da plataforma Desenrola: camadas, subsistemas, classes, banco, rotas e segurança.
É um único arquivo HTML, sem build, aberto direto no navegador.

O trabalho atual é **refazer os slides um por um**, trocando os diagramas Mermaid (que pareciam UML
genérica) por desenhos próprios em HTML, CSS e JS, com uma identidade visual única.

## 2. Regras do usuário (obrigatórias)

1. **Nada de cara de UML.** O usuário quer desenhos que expliquem o funcionamento, não diagramas de
   classe ou de sequência padrão. Bibliotecas, CSS e JS são bem-vindos; Mermaid só fica nos slides
   ainda não refeitos.
2. **Não publicar no claude.ai.** Não use a ferramenta Artifact. O arquivo vive no Git, em `docs/`.
3. **Fluxo slide por slide.** Para cada slide: descreva em poucas linhas o desenho proposto, faça,
   confira no navegador, corrija e pergunte se segue para o próximo. Não refaça vários de uma vez.
4. **Sem travessões** (os caracteres de traço longo e médio) em nenhum texto, código ou comentário. Use vírgula, dois-pontos,
   parênteses ou ponto final. Regra do README do projeto.
5. **Sem commit nem push** sem avisar e receber autorização na hora.
6. Todo texto em **português do Brasil**, frases curtas e diretas.
7. Escopo: **só a plataforma Desenrola**. Nada sobre divisão da equipe, prazos ou entrega.
8. Há alterações em `app/` e `public/` no `git status` que não são deste trabalho. Não toque nelas.

## 3. Status dos slides

| # | id | Assunto | Status |
|---|---|---|---|
| 01 | `capa` | Capa com coração em ponto cruz | Original, aceito |
| 02 | `contexto` | Quem usa e com quem conversa | **Refeito** (escada de perfis, bastidor, serviços) |
| 03 | `implantacao` | Onde cada peça roda | **Refeito** (aparelho, contêiner Render, serviços) |
| 04 | `camadas` | Camadas MVC2 | **Refeito** (exemplo "salvar insumo" atravessando 5 camadas) |
| 05 | `requisicao` | Caminho de uma requisição | **Refeito** (linha do tempo com 11 passos e 3 portões) |
| 06 | `nucleo` | Núcleo (core) | **Refeito** (linha do pedido com 2 portões em losango, faixa da memória até o banco) |
| 07 | `subsistemas` | Seis subsistemas | **Refeito e corrigido** (4 colunas, montador no centro, IA ao lado do Hub) |
| 08 | `estado` | O que já existe | HTML próprio antigo, revisar no estilo novo |
| 09 | `login` | Entrar na conta | Pendente, Mermaid |
| 10 | `recuperar` | Recuperar senha | Pendente, Mermaid |
| 11 | `mostruario` | Vitrine e home | Pendente, Mermaid |
| 12 | `hub` | Hub: feed, curtidas, comentários | Pendente, Mermaid |
| 13 | `curtir` | Curtir sem recarregar | Pendente, Mermaid |
| 14 | `conversor` | De imagem a matriz | Pendente, Mermaid |
| 15 | `montador` | Um montador, três destinos | Pendente, Mermaid |
| 16 | `tutorial` | Tutorial com Gemini | Pendente, Mermaid |
| 17 | `gerente` | Área da gerente | Pendente, Mermaid |
| 18 | `prg` | Salvar sem perder o digitado | Pendente, Mermaid |
| 19 | `banco` | Banco de dados | Pendente, Mermaid |
| 20 | `rotas` | Mapa de rotas | HTML próprio antigo, revisar no estilo novo |
| 21 | `frontend` | Telas e scripts | Pendente, Mermaid |
| 22 | `seguranca` | Camadas de defesa | HTML próprio antigo, revisar no estilo novo |

O conteúdo técnico de cada slide pendente já está correto dentro do próprio slide (o texto e o
código Mermaid em `<script type="text/plain" class="src">`). Use isso como fonte, e confira no código
em `app/` quando tiver dúvida. Não invente classes, métodos ou números.

## 4. A identidade visual

O tema vem do próprio domínio: bordado.

**Papéis fixos das cores** (tokens em `:root`, com versões escuras nos dois blocos de tema):

| Papel | Tokens | Uso |
|---|---|---|
| Pessoas | `--rose`, `--rose-soft`, `--rose-mid`, `--rose-ink` | Perfis, navegador, tudo que é humano |
| Plataforma | `--accent`, `--accent-soft`, `--hoop`, `--on-hoop` | A Desenrola e suas camadas |
| De fora | `--surface-2`, `--border`, `--line` | Supabase, Gemini, WhatsApp, GitHub |
| Certo e errado | `--ok`, `--no` | "Pode" e "não pode", portões, saídas de erro |

**Símbolos recorrentes:**

- **Bastidor:** círculo vinho com textura de tecido (`radial-gradient` com `--fabric-dot` sobre
  `--hoop`), aro rosa e presilha no topo. Representa a plataforma. Classe `.bastidor`; a mesma
  textura aparece no Banco do slide 04 (`.lay.l4`).
- **Linha de costura:** ligação tracejada com nó redondo nas pontas.
  - `thread`: integração real, vinho, anima devagar (ponto sendo costurado).
  - `people`: pessoas usando, rosa.
  - `loose`: alinhavo pontilhado, só um link ou chamada opcional.
- **Escurecer ao descer:** no slide 04 a cor vai de clara (entrada) a vinho (banco).
- **Portão em losango** para decisões, **nó redondo** para passos (slide 05).
- **Ícones** da biblioteca Lucide, sempre dentro de um quadrado ou círculo de fundo claro.

**Tipografia:** Cormorant Garamond para títulos e números grandes, Jost para texto,
JetBrains Mono para código. Todas pelo Google Fonts.

## 5. Molde de um slide refeito

```html
<section class="slide slide--draw" id="ID" data-sec="Seção" data-title="Título curto">
  <div>
    <p class="eyebrow">Seção</p>
    <h2>Título</h2>
    <p class="lede">Uma ou duas frases. <b>Destaque</b> o essencial.</p>
  </div>

  <div class="MEU-DESENHO" data-wires='[ ... ]'>
    ... colunas com <p class="colcap">Título da coluna</p> ...
  </div>

  <div class="footrow">
    <p class="note"><i data-lucide="info"></i><span><b>Ideia-chave.</b> Explicação curta.</span></p>
    <div class="keys"><span><i></i>legenda</span></div>
  </div>
</section>
```

- `.slide--draw` tem três linhas: cabeçalho, desenho (ocupa o resto) e rodapé.
- **O slide tem altura fixa e `overflow: hidden`.** O que passa da altura some sem aviso, e o
  rodapé cobre o que sobra. Por isso a captura sozinha engana: meça (seção 9).
- **Orçamento de altura.** Em um deck de 1280px de largura, a área do desenho tem uns 430px
  (cerca de 33cqw), já descontados cabeçalho com lede de duas linhas, `colcap` e rodapé.
  Um cartão com cabeçalho, 3 linhas de código e um chip mede uns 170px (13cqw). Conta prática:
  **no máximo 2 cartões empilhados por coluna**, com espaço entre eles para o rótulo da linha.
  Precisa de mais? Crie outra coluna ou corte conteúdo. Nunca reduza fontes abaixo de `.72cqw`
  para caber: isso foi tentado no 07 e não resolveu.
- **O desenho também não pode ficar pequeno.** Se o conteúdo ocupa menos da metade da área, os
  cartões estão pequenos demais ou falta desenho (veja o erro do 06 na seção 12).
- O texto lateral antigo some. O desenho explica; a `lede` só orienta a leitura.
- Use `colcap` em **todas** as colunas, para os títulos ficarem na mesma linha. As colunas usam
  `display: grid; grid-template-rows: auto 1fr` com o conteúdo centralizado no `1fr`.
- `footrow`: à esquerda um `.note` com a ideia que mais confunde; à direita `.keys` com a legenda das
  linhas usadas no slide (`<i>` vinho, `<i class="p">` rosa, `<i class="l">` pontilhado).
  A `.keys` é **só para as linhas de costura**. Não use para explicar chips (eles já têm texto)
  nem números de etapa. Slide sem linhas não tem `.keys`.

### Unidades

O deck é um container (`container-type: inline-size`). **Tudo dentro do slide usa `cqw`**, para
escalar com o tamanho da tela. Referências: título de coluna `.82cqw`, texto de cartão `.9cqw` a
`1cqw`, título de cartão `1.2cqw`, ícone `2.6cqw`. Nenhum texto abaixo de `.72cqw`.

### Celular (`@media (max-width: 760px)`)

Os slides viram uma página contínua. Para cada desenho novo, acrescente no bloco do celular:
grade em uma coluna, tamanhos em `rem`/`px`, `.wires` e `.wlabel` escondidos (já é global), setas
de CSS escondidas. Os cartões precisam continuar se explicando sem as linhas.

## 6. Linhas de costura (`data-wires`)

Qualquer elemento com `data-wires` vira um palco. O script lê o JSON e desenha um SVG por baixo do
conteúdo, mais os rótulos em HTML.

```json
[
  {"from": "#id-a", "to": "#id-b", "kind": "thread", "label": "texto", "code": true, "at": 0.5}
]
```

- `from` e `to`: seletores **dentro do palco**.
- `kind`: `thread`, `people` ou `loose`.
- `label` (opcional): rótulo no meio da linha. `code: true` usa fonte mono. `at` é a posição de 0 a 1
  (padrão 0.55).
- Âncoras: se o alvo está bem acima ou abaixo (diferença vertical maior que 1,5 vez a horizontal), a
  linha sai pela borda de cima ou de baixo; senão, pela lateral. Elemento com `border-radius: 50%`
  recebe a linha no ponto do círculo voltado para o outro elemento.
- Para escolher a borda à mão: `fromSide` e `toSide` (`top`, `bottom`, `left`, `right`), e
  `fromAt` e `toAt` (0 a 1 ao longo dessa borda, padrão 0.5). Use quando dois fios chegariam no
  mesmo ponto. Exemplo no 06: Csrf e Auth descem até a Sessao em pontos diferentes
  (`"fromSide":"bottom","toSide":"top","toAt":0.84`).
- O desenho refaz ao trocar de slide, ao redimensionar e quando as fontes carregam.
- Deixe **espaço entre as colunas** (`column-gap` de 7 a 10 `cqw`) para os rótulos não encostarem.
  Um rótulo como "matriz padrão" mede uns 6,5cqw; com 5,5cqw ele já invade os cartões.
- Entre cartões empilhados ligados por uma linha (como Mostruário e Hub no 07), deixe uns
  4cqw de espaço vertical, ou o rótulo fica em cima dos cartões.
- Dois fios não devem chegar no mesmo ponto do mesmo cartão. Se isso acontecer, mude a
  posição dos cartões (foi o que levou a IA para uma coluna própria no 07) ou use
  `toSide`/`toAt`.
- Rótulo que não cabe no vão: tire o rótulo e ponha a informação dentro do cartão (no 06, o
  "read · write" virou a linha `read() · write() · gc()` da SessaoBanco).

**Cuidado:** nunca escreva regras como `.palco > *`. Isso pega o `<svg class="wires">` e os
`.wlabel`, que precisam de `position: absolute`. Mire nas classes dos cartões.

Para ligações simples dentro de uma grade fixa, CSS basta (setas com `::after`, linha tracejada com
`border-top: dashed`). Os slides 03 (`.stop`) e 05 (`.stations::before`, `.exit::before`) fazem assim.

## 7. Bibliotecas

- **Lucide** `https://cdn.jsdelivr.net/npm/lucide@0.344.0/dist/umd/lucide.min.js`. Use
  `<i data-lucide="nome"></i>`; `lucide.createIcons()` roda no início. A versão 0.263.1 não existe,
  não volte para ela. Nomes já testados: shield-check, user, eye, store, heart, scissors,
  clipboard-list, database, sparkles, message-circle, info, smartphone, file-code-2,
  mouse-pointer-click, lock, github, container, key-round, hard-drive, laptop, layout-template,
  chevron-right, check, x, cog, package-open, ban, undo-2, bug, refresh-ccw.
- **Mermaid** 10.9.1 continua carregado só para os slides pendentes. Quando o último slide Mermaid
  for refeito, remova o `<script>` do Mermaid e a função `renderAll`.
- Qualquer outra biblioteca: só de `cdn.jsdelivr.net`, `cdnjs.cloudflare.com` ou `unpkg.com`, com
  versão exata. Teste se a URL responde antes de usar.

## 8. Como editar com segurança

- Para trocar um slide inteiro: escreva o `<section>` novo em um arquivo temporário (na pasta de
  rascunho, nunca no projeto) e troque o bloco entre os comentários `<!-- N NOME -->` e
  `<!-- N+1 NOME -->` com `awk`. Depois confira com `grep` que o id novo existe uma vez e que o
  conteúdo antigo sumiu.
- CSS novo vai antes de `.footrow { display: flex;` (ou depois do bloco do slide anterior), e as
  regras do celular vão dentro do `@media (max-width: 760px)`, no fim dele.
- A ordem do CSS importa: uma regra antiga mais abaixo vence uma nova mais acima com a mesma
  força. Edite a regra original em vez de sobrescrever. Não crie blocos "de compressão" que
  sobrescrevem tamanhos do slide anterior (o 07 tinha um, com fontes de `.66cqw`).
- **Moldura do deck (não mexer sem motivo).** O tamanho do slide vem do CSS: `.stage` é um
  contêiner de tamanho e o `.deck` usa `width: min(100cqw, 100cqh * 16 / 9)`, dentro de um
  `@media (min-width: 761px)` que fica **depois** das regras base de `.deck` e `.controls`.
  O `body` tem `margin: 0`. Não volte a usar `100vh` com altura fixa das barras (o antigo
  `--chrome: 152px`), nem tire o `margin: 0`: os 8px de margem padrão empurravam a barra de
  controles para fora da tela, e o `overflow: hidden` escondia o erro.
- Em grades com linhas de cima e de baixo (como o slide 04), posicione cada item com
  `grid-column` e `grid-row` explícitos. O posicionamento automático embaralha.
- No fim, rode no Bash `grep -c $'—\\|–' docs/APRESENTACAO_ARQUITETURA.html` e confirme `0`.

## 9. Como conferir

1. Abra `file:///.../Desenrola/docs/APRESENTACAO_ARQUITETURA.html` no navegador embutido.
2. Emule 1440 por 860 (`resize_window`).
3. Espere uns 3 segundos (fontes, ícones), clique em "próximo" até o slide, espere **2 segundos**
   e tire a captura. Captura antes disso pega a animação de entrada e o slide parece desbotado.
4. Verifique: títulos de coluna na mesma altura, rótulos sem encostar nos cartões, nenhum texto
   cortado, linhas saindo dos lugares certos.
5. Se algo estranho aparecer, inspecione com JS (`getBoundingClientRect` do palco e dos rótulos)
   antes de chutar uma correção.
6. **Rode a medição abaixo em todo slide que mexer, antes de dizer que está pronto.** Os erros
   do 07 (cartão cortado, rótulos em cima de cartões, fonte pequena) não aparecem bem numa
   captura reduzida, mas aparecem aqui. Tudo precisa dar `0` e `[]`:

   ```js
   const s = document.querySelector('.slide.is-active');
   const ov = (a, b) => { const A = a.getBoundingClientRect(), B = b.getBoundingClientRect();
     return !(A.right <= B.left || B.right <= A.left || A.bottom <= B.top || B.bottom <= A.top); };
   const cards = [...s.querySelectorAll('.place, .mnd, .tool, .lay, .sta, .step, .ext')];
   ({
     transborda: s.scrollHeight - s.clientHeight,
     paginaMaiorQueJanela: document.documentElement.scrollHeight - innerHeight,
     rotulosSobreCartoes: [...s.querySelectorAll('.wlabel')].flatMap(l => cards.filter(c => ov(l, c)).map(c => l.textContent)),
     textoCortado: [...s.querySelectorAll('.lines li, .stop-v, .tool')].filter(e => e.scrollWidth > e.clientWidth + 1).map(e => e.textContent.trim().slice(0, 30)),
     fontePequena: [...s.querySelectorAll('*')].filter(e => [...e.childNodes].some(n => n.nodeType === 3 && n.textContent.trim())
       && parseFloat(getComputedStyle(e).fontSize) < s.offsetWidth * .0072 - .2).map(e => e.className)
   })
   ```

   Confira também se o desenho ocupa a área: a soma das alturas do conteúdo deve passar de
   metade da altura do palco do desenho.
7. Teste também uma janela baixa (1440 por 600) e o celular (`preset: "mobile"`).
8. A prévia local sempre renderiza no tema claro. Para o escuro, defina
   `document.documentElement.setAttribute('data-theme', 'dark')` pelo JS.
9. Volte a emulação para `desktop` ao terminar.

## 10. Ideias para os slides pendentes

Sugestões, não obrigações. Apresente a proposta ao usuário antes de fazer.

- **06 Núcleo:** feito. Use como referência de "desenho que explica": grade de 5 colunas e 4
  linhas com posições explícitas (`.nuc`, `.pc`), portões com ícone em losango (`.ic.gate`),
  saídas de erro tracejadas em `--no` (`.pc .no`), controlador tracejado (`.pc.ctl`) e banco
  com textura de bastidor (`.pc.db`). Essas classes podem ser reaproveitadas nos slides 09, 10 e 18.
- **07 Subsistemas:** feito. Não volte para 3 colunas com 3 cartões empilhados.
- **08 Estado:** barras de progresso costuradas por camada, com os chips pronto, parcial, esqueleto.
- **09 e 10 Contas:** login como trilha curta com portões (usuário existe, senha confere, conta
  ativa); recuperação como dois passos com contador de tentativas de 1 a 5.
- **11 Mostruário:** um produto "desmontado": matriz + peça + cor + posição formando o cartão da
  vitrine, com o botão de WhatsApp no fim.
- **12 e 13 Hub:** um cartão de post de verdade com coração, contador e comentários, e as setas
  saindo de cada botão para a rota que ele chama; o curtir como interruptor (INSERT ou DELETE).
- **14 Conversor:** a mesma imagem passando por 5 estados visuais (foto, grade reduzida,
  transparência, poucas cores, JSON), com a fronteira navegador e servidor marcada.
- **15 Montador:** quatro etapas do formulário e uma bifurcação em três destinos pelo `modo`.
- **16 Tutorial:** três trilhos lado a lado (cache, IA, reserva) saindo do mesmo pedido.
- **17 e 18 Gerente:** quatro fichas de cadastro idênticas com as regras de cada uma; o PRG como
  ida e volta entre formulário e lista.
- **19 Banco:** `bordados` no centro como bastidor, as outras tabelas em volta com as chaves.
- **20 Rotas, 21 Front-end, 22 Segurança:** trazer para o molde novo (lede, colcap, footrow,
  cores por papel).

## 11. Fatos do código usados nos slides

- 13 controladores (5 prontos), 11 serviços (4 prontos, 1 parcial), 8 classes de dados (5 prontas).
- Prontos: core, autenticação, catálogo, home, cadastros de insumos e clientes.
- Esqueleto: Hub, comentários, montador, matrizes, produtos da gerente, painel, tutorial.
- 10 tabelas no Supabase, todas com RLS.

Se o código mudar, atualize esses números no slide 08 e em qualquer outro que os cite.

## 12. Erros já cometidos (não repetir)

### Slide 06, núcleo

- **Virou uma tabela de cartões.** São 8 cartões iguais numa grade 4 por 2, com bolinhas
  numeradas no título. Nada mostra o pedido passando de uma gaveta para a outra. É o oposto da
  regra 1: o desenho tem que explicar o funcionamento, não listar classes.
- **Ocupa um terço da área.** O `.toolbox` usa `align-content: center` com cartões baixos, então
  sobra uma faixa vazia grande em cima e embaixo do desenho.
- **Fora da paleta de papéis.** Tudo é vinho claro igual; o banco (Conexao, SessaoBanco) não tem o
  bastidor, e o portão do Csrf não usa losango nem `--ok`/`--no`.
- **Legenda errada.** A `.keys` mostra "1-4 ordem do pedido" com um círculo. A legenda é só para
  linhas de costura.
- **Detalhes:** `.tool__meta` com `.68cqw` (abaixo do mínimo), o traço do `colcap` escondido só
  neste slide, e a nota começa com "Por que existirem?" (o certo é "Por que elas existem?").
- **Como foi refeito:** em cima, o pedido em linha (Roteador, Csrf, Auth, controlador, e a saída
  para Visao ou Resposta), com cada erro saindo do seu portão (404, 403, /login ou 401). Embaixo,
  a faixa "Memória, guardada no banco" (Sessao, SessaoBanco, Conexao), consultada pelos portões
  com linhas pontilhadas. Fatos tirados de `public/index.php` e `app/core/`. As classes
  `.toolbox`, `.drawer`, `.tool` e `.k1` foram removidas.
- **Na primeira versão refeita, a faixa de baixo invadiu o rodapé.** A coluna Resposta tinha dois
  cartões com texto e esticava a linha de cima. Corrigido tirando o texto desses dois cartões.
  A medição da seção 9 pegou o erro (conteúdo de 499px num palco de 455px).

### Slide 07, subsistemas

- **Não cabia e ninguém viu.** Eram 3 colunas, e a da direita tinha 3 cartões (Hub, Mostruário,
  Tutorial com IA) somando uns 540px numa área de 430px. O `overflow: hidden` do slide cortava o
  Tutorial e o rodapé ficava por cima dele.
- **Tentou caber encolhendo.** Um bloco "compressão para caber na altura" baixou fontes para
  `.66cqw` e `.68cqw`. Não resolveu e quebrou a regra do tamanho mínimo.
- **Rótulos em cima dos cartões.** "mais curtidos" e "como bordar" caíam sobre os cartões, porque
  não havia espaço entre eles.
- **Legenda errada.** A `.keys` explicava pronto, parcial e esqueleto com tracinhos de linha.
- **Como foi corrigido:** 4 colunas (`19fr 24fr 25fr 16fr`, `column-gap: 7cqw`). O Tutorial com IA
  ganhou coluna própria ("Usa o Hub"), ao lado do Hub que o chama. Cada coluna tem no máximo 2
  cartões, com `justify-content: space-between` para abrir espaço aos rótulos. Fontes de volta a
  `.72cqw`. Legenda trocada por "alimenta" e "consulta".

### Moldura do deck (afetava todos os slides)

- O `body` não tinha `margin: 0`. A página ficava 8px mais alta que a janela e o
  `overflow: hidden` cortava a barra de controles.
- A largura do deck era `calc((100vh - 152px) * 16 / 9)`, com a altura das barras medida por
  script. Trocado por contêiner de tamanho no `.stage` (veja a seção 8).

### Lição geral

Antes de dizer que um slide está pronto: rode a medição da seção 9, olhe a captura em tamanho
real (use `zoom` na região do desenho) e compare com as regras das seções 2, 4 e 5. Uma captura
reduzida esconde corte, sobreposição e fonte pequena.
