# Desenrola: Hub e Mostruário de Bordados

Trabalho Prático 2 de Back-End: sistema web em PHP com banco de dados.

**Desenrola** é uma plataforma de bordados, com o mostruário do ateliê **Cris Bordados e Bolsas**.
Ela não tem restrição territorial.

O sistema une três coisas:

- **Hub de bordados:** qualquer pessoa cria uma conta, transforma uma imagem em matriz de bordado,
  posiciona na peça, escolhe a cor e publica no feed, onde todos podem curtir e comentar.
- **Mostruário:** vitrine das peças do ateliê, com orçamento pelo WhatsApp.
- **Área da gerente:** cadastro de insumos, clientes, matrizes e produtos.

**Entrega e apresentação: 19/11/2026, em aula presencial.** Sem apresentação, a nota é zero.

## Regras obrigatórias

Valem para todas as pessoas do grupo e para qualquer IA usada no projeto:

1. **Não usar travessões** em código, comentários, documentos, textos do site ou mensagens de commit.
   Use vírgula, dois-pontos, parênteses ou ponto final.
2. **Não fazer commits sem avisar.** Uma IA só faz commit ou push depois de avisar quem a está usando
   e receber autorização nesse momento.
3. **Comentar seguindo o modelo.** Todo arquivo tem cabeçalho e todo método público tem um comentário
   explicativo, no formato da [seção 6.1](#61-modelo-de-comentários). Um arquivo só está pronto
   quando os comentários estão no modelo.

## Sumário

1. [Decisões do projeto](#1-decisões-do-projeto)
2. [Requisitos do enunciado](#2-requisitos-do-enunciado)
3. [Tecnologias](#3-tecnologias)
4. [Perfis e funcionalidades](#4-perfis-e-funcionalidades)
5. [Arquitetura MVC2](#5-arquitetura-mvc2)
6. [Estrutura de pastas](#6-estrutura-de-pastas)
7. [Banco de dados](#7-banco-de-dados)
8. [Rotas](#8-rotas)
9. [Conversor e montador](#9-conversor-e-montador)
10. [IA com Gemini](#10-ia-com-gemini)
11. [Guia de CSS](#11-guia-de-css)
12. [Responsabilidades](#12-responsabilidades)
13. [Como rodar no seu computador](#13-como-rodar-no-seu-computador)
14. [Como usar o Git na main](#14-como-usar-o-git-na-main)
15. [Publicação](#15-publicação)
16. [Checklist da entrega](#16-checklist-da-entrega)

## 1. Decisões do projeto

| Assunto | Decisão |
|---|---|
| **Banco** | PostgreSQL no Supabase, com autorização do professor no lugar do MySQL |
| **Grupo** | Três integrantes, com autorização do professor |
| **Hospedagem** | Render, com deploy automático a partir do GitHub e PHP em Docker |
| **Sessão PHP** | Fica no banco, na tabela `sessoes`, porque arquivos do container não são duráveis |
| **Arquivos** | Sem upload. Matrizes ficam no banco como grade de pontos e SVG gerado no servidor |
| **IA** | Gemini, com cada tutorial salvo no banco e um texto reserva para falhas |
| **Contas** | Todo e-mail do sistema usa `@example.com`; a pessoa digita só o prefixo no formulário |
| **Divisão** | Cada arquivo tem um único dono, e Guilherme entrega os arquivos comentados |

O Render executa PHP por Docker. Por isso, o projeto tem um `Dockerfile` na raiz.

## 2. Requisitos do enunciado

| Exigência | Como atendemos |
|---|---|
| Front-end em HTML, CSS e JS | Telas PHP geram o HTML, com o CSS e o JS atuais reaproveitados |
| Back-end em PHP | PHP 8.5 com arquitetura MVC2 |
| Método POST | Todo formulário e toda ação que altera dados usa POST |
| Variáveis de sessão | `$_SESSION` guarda login, perfil e mensagens, com armazenamento no banco |
| Banco de dados | PostgreSQL no Supabase, autorizado pelo professor |
| Git e GitHub | Repositório com commits dos integrantes. **Sem Git, a nota é zero** |
| Login com persistência | Tabela `usuarios`, senha com `password_hash` |
| Recuperar senha | Pergunta de segurança e criação de nova senha |
| 4 telas de cadastro | Usuário, Cliente, Insumo, Matriz, Produto |
| 4 telas de visualização | Usuários, Clientes, Insumos, Matrizes, Catálogo, Hub |
| 2 funcionalidades da temática | **1.** Conversor de imagem em matriz com montador na peça. **2.** Hub com feed, curtidas e comentários |
| Desafio de IA com Gemini | Tutorial de como fazer o bordado à mão |
| Entrega no Teams | ZIP ou RAR do código, backup do banco e link do GitHub |

Fora do escopo: os módulos de ERP da ERS (pedidos, agenda, pagamentos e relatórios), pagamento
on-line, envio de e-mail e upload de arquivos para o servidor.

## 3. Tecnologias

| Camada | Tecnologia |
|---|---|
| Interface | HTML gerado por PHP, CSS puro, JavaScript com ES Modules |
| Servidor | PHP 8.5 com FrankenPHP em Docker |
| Banco | PostgreSQL no Supabase, acessado por PDO |
| Hospedagem | Render, com deploy pelo GitHub |
| IA | API do Gemini, chamada por cURL no PHP |
| Versionamento | Git e GitHub, branch `main` |

**Sem Composer e sem framework.** O projeto só usa recursos nativos do PHP, para que todo o
grupo consiga explicar qualquer arquivo na apresentação.

## 4. Perfis e funcionalidades

**Visitante**

- Vê a home, o catálogo, a página do produto e o Hub.
- Solicita orçamento pelo WhatsApp.
- Cria conta e recupera senha.

**Usuário**

- Tudo o que o visitante faz.
- Converte imagem em matriz, monta o bordado e publica no Hub.
- Curte e comenta bordados.
- Vê e exclui os próprios bordados e comentários.
- Pede à IA o tutorial de um bordado.

**Gerente**

- Tudo o que o usuário faz.
- Vê o painel com os totais do sistema.
- Cadastra, edita, lista e exclui insumos, clientes, matrizes e produtos.
- Cria matrizes padrão e combinações com o mesmo montador dos usuários.
- Lista usuários e oculta posts e comentários do Hub.

`usuarios` e `clientes` são coisas diferentes. **Usuário** tem login. **Cliente** é um registro
comercial que a gerente mantém, e não entra no sistema.

## 5. Arquitetura MVC2

Toda requisição entra por um único arquivo, `public/index.php`, e segue este caminho:

```text
Navegador
   │  GET / POST
   ▼
public/index.php ──► Roteador
                        │
                        ▼
                 CONTROLADORES ──────► TELAS
                        │
                        ▼
                 PROCESSAMENTO
                        │
                        ▼
                     DADOS
                        │  PDO
                        ▼
              Banco de dados (Supabase)
```

Cada camada só conversa com a vizinha:

| Camada | Pasta | Pode | Não pode |
|---|---|---|---|
| **Telas** | `app/telas/` | Exibir as variáveis recebidas | Fazer consulta ou regra de negócio |
| **Controladores** | `app/controladores/` | Ler `$_POST`, `$_GET` e `$_SESSION`, chamar o Processamento, escolher a tela | Conter SQL |
| **Processamento** | `app/processamento/` | Validar, aplicar regras, chamar Dados | Ler `$_POST`, usar `$_SESSION`, gerar HTML |
| **Dados** | `app/dados/` | Executar SQL com PDO | Validar regra de negócio ou gerar HTML |

Exemplo, ao salvar um insumo:

1. O roteador valida o CSRF e chama `InsumoControlador::salvar()`.
2. O controlador pega o `$_POST` e chama `InsumoServico::salvar($campos)`.
3. O serviço valida e chama `InsumoDados::inserir($campos)`.
4. O `InsumoDados` executa o `INSERT`.
5. O controlador grava a mensagem de sucesso e redireciona para a lista.

Regras gerais:

- Toda saída de dados nas telas passa por `e()`, que escapa o HTML.
- Todo SQL usa parâmetros. Nunca concatene valor do usuário em SQL.
- Todo formulário POST tem `<?= Csrf::campo() ?>`.
- Depois de um POST bem-sucedido, sempre redirecione.
- Senhas, chaves e dados do banco ficam apenas em variáveis de ambiente.

## 6. Estrutura de pastas

Letra entre colchetes indica o dono do arquivo: **[A]** Arthur, **[E]** Eduardo, **[G]** Guilherme.
Só o dono altera o arquivo.

```text
/
├── Dockerfile, Caddyfile, .dockerignore           [G]
├── .env.example, .gitignore                       [G]
├── rodar-local.bat                                [G]
├── README.md, INSTRUCOES_*.md                     [G]
│
├── database/
│   └── schema.sql, seed.sql, backup.sql           [G]
│
├── public/                                        raiz do site
│   ├── index.php                                  [G]
│   └── assets/
│       ├── css/
│       │   ├── main.css, responsive.css           [G]
│       │   ├── base/, components/                 [G]
│       │   └── pages/
│       │       ├── home.css, painel.css           [G]
│       │       ├── montador.css, cadastros.css    [G]
│       │       ├── catalog.css, produto.css       [A]
│       │       └── auth.css, hub.css              [E]
│       ├── js/
│       │   ├── main.js, utils/, components/       [G]
│       │   └── pages/
│       │       ├── conversor.js, montador.js      [G]
│       │       ├── tutorial.js                    [G]
│       │       └── hub.js                         [E]
│       └── img/                                   [G]
│
└── app/
    ├── config/, core/                             [G]
    ├── controladores/
    │   ├── InsumoControlador, ClienteControlador  [A]
    │   ├── AuthControlador, UsuarioControlador    [E]
    │   ├── ComentarioControlador                  [E]
    │   └── todos os demais                        [G]
    ├── processamento/
    │   ├── InsumoServico, ClienteServico          [A]
    │   ├── AuthServico, ComentarioServico         [E]
    │   └── todos os demais                        [G]
    ├── dados/
    │   ├── InsumoDados, ClienteDados              [A]
    │   ├── UsuarioDados, ComentarioDados          [E]
    │   └── todos os demais                        [G]
    └── telas/
        ├── layouts/, parciais/, erros/            [G]
        ├── home/, painel/, montador/              [G]
        ├── insumos/, clientes/, produtos/         [A]
        ├── matrizes/, catalogo/                   [A]
        └── auth/, usuarios/, hub/                 [E]
```

Guilherme coloca **todos** esses arquivos no repositório antes de os colegas começarem. Cada arquivo
abre com um comentário informando dono, responsabilidade e um `TODO` do que falta.

### 6.1 Modelo de comentários

Os comentários são em português, sem travessões, e explicam **o que** o código faz e **por que**,
nunca repetem linha por linha o que o código já diz. São quatro níveis.

**1. Cabeçalho do arquivo.** Logo depois de `<?php` (ou no topo do CSS e do JS), em um bloco `/** */`.

Classe PHP (controlador, processamento ou dados):

```php
<?php
/**
 * Camada: Dados. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: SQL da tabela insumos. Sem validação e sem HTML. Todo valor vai em parâmetro.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
```

Tela:

```php
<?php
/**
 * Tela: lista de insumos
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/insumos
 * Recebe: $titulo, $busca, $insumos
 * Campos de $insumos: id, tipo, nome, cor_hex, quantidade, unidade, custo_unitario,
 *                     fornecedor, estoque_minimo
 * Envia POST para ... (só em formulário, seguido de "Campos: ...")
 * Responsabilidade: listar insumos com busca por nome, situação de estoque e ações de editar/excluir.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
```

CSS e JavaScript:

```css
/*
 * Arquivo: catalog.css
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: filtros do catálogo público (.catalog-filters e .filter-chip).
 * Veja INSTRUCOES_ARTHUR.md, seção 7.
 */
```

| Linha | Obrigatória em | Conteúdo |
|---|---|---|
| `Camada` / `Tela` / `Arquivo` | Todos | Camada da classe, nome da tela ou nome do arquivo |
| `Dono` | Todos | Uma única pessoa, com a frase "Só X altera este arquivo." |
| `Rota` | Telas | Método e caminho que abrem a tela |
| `Recebe` e `Campos de $x` | Telas | Variáveis do contrato e campos de cada lista |
| `Envia POST para` e `Campos` | Telas com formulário | Destino e nomes dos campos |
| `Responsabilidade` | Todos | O que o arquivo faz e o que ele **não** faz |
| `Veja` | Todos | Documento e seção com as regras |

**2. Comentário de método.** Todo método público tem um `/** */` logo acima, com uma frase que diz o
que ele faz e o que devolve. Use uma linha só quando couber; se não couber, use várias.

```php
/** GET /gerente/insumos: lista os insumos, com busca por nome em ?busca=. */
public function listar(): void

/** Um insumo, ou null se não existir. */
public function buscar(int $id): ?array

/**
 * Valida e grava o insumo: cadastra com id 0 e atualiza com id maior que 0.
 * Devolve a lista de erros. Lista vazia significa que salvou.
 */
public function salvar(int $id, array $c): array
```

- **Controlador:** comece com a rota (`GET /caminho:` ou `POST /caminho:`) e diga para onde redireciona.
- **Processamento:** diga as regras validadas e o que devolve (lista de erros, `null`, `bool`).
- **Dados:** diga o que a consulta busca ou grava, e o que devolve quando não acha.

**3. Comentário de linha.** Use `//` só para explicar uma decisão que não é óbvia, por exemplo
`// Prepares emulados: booleano vai como 't' ou 'f'.` Não comente o óbvio.

**4. TODO.** `TODO(Nome): o que falta` marca trabalho pendente e tem sempre o nome do dono. Quando o
trabalho estiver pronto, **troque o TODO por um comentário do nível 2** (ou apague a lista de TODO do
cabeçalho, mantendo as demais linhas). Arquivo entregue não tem `TODO` do próprio dono.

## 7. Banco de dados

O SQL completo fica em `database/schema.sql`, criado a partir de
[INSTRUCOES_GUILHERME.md](INSTRUCOES_GUILHERME.md).

| Tabela | Finalidade | Campos principais |
|---|---|---|
| `usuarios` | Contas com login | nome, email, senha_hash, perfil, pergunta_seguranca, resposta_hash |
| `sessoes` | Sessões PHP | id, dados, expira_em |
| `clientes` | Clientes do ateliê | nome, cpf_cnpj, email, telefone, endereco, cep |
| `insumos` | Materiais e estoque | tipo, nome, cor_hex, quantidade, unidade, custo_unitario, fornecedor |
| `categorias` | Tipos de peça | nome, slug, mockup |
| `matrizes` | Desenhos de bordado | nome, grade, svg, largura, altura, total_pontos, padrao, autor_id |
| `bordados` | Matriz aplicada na peça | titulo, autor_id, matriz_id, categoria_id, cor_peca, pos_x, pos_y, escala, tipo, oculto, tutorial_ia |
| `produtos` | Itens do mostruário | bordado_id, nome, descricao, preco_base, destaque, ativo |
| `curtidas` | Likes do Hub | usuario_id, bordado_id, chave primária nos dois campos |
| `comentarios` | Comentários do Hub | bordado_id, usuario_id, texto, oculto |

Relações:

```text
usuarios 1──N bordados N──1 matrizes
usuarios 1──N matrizes
categorias 1──N bordados
bordados 1──0..1 produtos
usuarios N──N bordados (por curtidas)
usuarios 1──N comentarios N──1 bordados
```

## 8. Rotas

Nas colunas, a lógica indica quem escreve controlador, processamento e dados. A tela indica quem
preenche a interface.

| Acesso | Método | Rota | Lógica | Tela |
|---|---|---|---|---|
| Público | GET | `/` | G | G |
| Público | GET | `/catalogo`, `/produto?id=` | G | A |
| Público | GET | `/hub`, `/hub/bordado?id=` | G | E |
| Público | GET, POST | `/login`, `/cadastro`, `/recuperar-senha` | E | E |
| Público | POST | `/recuperar-senha/redefinir` | E | E |
| Login | POST | `/sair` | E | G |
| Login | GET | `/meus-bordados` | G | E |
| Login | POST | `/hub/curtir` | G | E |
| Login | POST | `/hub/comentar`, `/hub/comentario/excluir` | E | E |
| Login | POST | `/bordado/excluir` | G | E |
| Login | GET | `/montador`, inclusive `?modo=matriz` e `?modo=produto` | G | G |
| Login | POST | `/montador/salvar`, `/bordado/tutorial` | G | G |
| Gerente | GET | `/gerente` | G | G |
| Gerente | GET | `/gerente/usuarios` | E | E |
| Gerente | GET | `/gerente/insumos`, `/gerente/insumos/form` | A | A |
| Gerente | POST | `/gerente/insumos/salvar`, `/gerente/insumos/excluir` | A | A |
| Gerente | GET | `/gerente/clientes`, `/gerente/clientes/form` | A | A |
| Gerente | POST | `/gerente/clientes/salvar`, `/gerente/clientes/excluir` | A | A |
| Gerente | GET | `/gerente/produtos`, `/gerente/produtos/form` | G | A |
| Gerente | POST | `/gerente/produtos/salvar`, `/excluir`, `/ativar` | G | A |
| Gerente | GET | `/gerente/matrizes`, `/gerente/matrizes/form` | G | A |
| Gerente | POST | `/gerente/matrizes/salvar`, `/gerente/matrizes/excluir` | G | A |
| Gerente | POST | `/gerente/hub/ocultar` | G | E |
| Gerente | POST | `/gerente/hub/comentario/ocultar` | E | E |

## 9. Conversor e montador

**Conversor.** A pessoa escolhe um PNG, JPG ou WebP e o navegador faz a conversão em um
`<canvas>`:

1. Reduz a imagem para 32, 48 ou 64 pontos de largura.
2. Ignora os pixels transparentes.
3. Reduz a imagem para 4 a 16 cores, como linhas de bordado.
4. Mostra a matriz na hora, com controles de tamanho e quantidade de cores.

**A imagem original nunca sai do navegador.** Só a grade de pontos vai ao servidor, em um POST:

```json
{ "largura": 48, "altura": 48, "paleta": ["#691818", "#d9a7a0"], "pontos": "..01..." }
```

Em `pontos`, cada caractere é um ponto: `.` significa vazio, e de `0` a `f` indica a posição da
cor na paleta. O PHP valida a grade e **gera o SVG no servidor**. Nunca salvamos um SVG enviado
pelo navegador, porque SVG pode carregar script.

**Montador.** Tem quatro etapas, com a aparência do fluxo atual de `produto.html`:

1. **Matriz:** escolher uma matriz padrão ou enviar uma imagem.
2. **Peça:** escolher toalha, bolsa, avental ou personalizado, e a cor.
3. **Posição:** arrastar e redimensionar, com mouse ou toque.
4. **Publicar:** informar título e descrição.

O mesmo montador atende a gerente, com `/montador?modo=matriz` para criar matriz padrão e
`/montador?modo=produto` para criar a combinação usada em um produto.

Vantagens: não há upload, tudo entra no backup do banco, e a resolução baixa reduz o risco de
imagem inadequada no telão. Mesmo assim, a gerente pode ocultar qualquer post.

## 10. IA com Gemini

O enunciado exige especificamente o **Gemini**, que tem plano gratuito. Na página de um bordado,
o botão **Como bordar à mão** envia ao PHP o título, a peça, a cor do tecido, o tamanho, os
pontos e as cores. O Gemini devolve materiais, preparação, passo a passo e dicas.

Para aguentar a turma toda:

- A chave fica apenas no servidor, na variável `GEMINI_API_KEY`.
- O texto é salvo em `bordados.tutorial_ia`, e a IA é chamada uma só vez por bordado.
- O modelo fica em `GEMINI_MODEL`, para ser trocado sem mexer no código.
- Se a IA falhar ou responder 429, o PHP monta um tutorial simples com os dados do bordado.
- Os limites reais devem ser conferidos no [Google AI Studio](https://aistudio.google.com/).

## 11. Guia de CSS

O CSS atual foi mantido e passa para `public/assets/css/`. A ordem de importação fica em
`main.css`: base, componentes, páginas e responsivo.

### Variáveis atuais

| Grupo | Variáveis |
|---|---|
| Vinho | `--color-wine-900: #4a1420`, `--color-wine-700: #6b1f2e`, `--color-wine-500: #8c3a48` |
| Rosa | `--color-rose-300: #d9a7a0`, `--color-rose-100: #f3ded9` |
| Areia | `--color-sand-50: #fbf7f1`, `--color-sand-100: #f5ede2`, `--color-sand-200: #eaddcb` |
| Texto | `--color-ink: #2e2321`, `--color-ink-soft: #6a5b57`, `--color-white: #ffffff` |
| Fontes | `--font-display: "Cormorant Garamond"`, `--font-body: "Jost"` |
| Espaços | `--space-1: .25rem` até `--space-8: 4rem`, `--section-y` |
| Layout | `--container: 1180px`, `--container-pad` |
| Cantos | `--radius-sm: 8px`, `--radius-md: 16px`, `--radius-lg: 24px`, `--radius-pill: 999px` |
| Sombras | `--shadow-sm`, `--shadow-md`, `--shadow-lg` |

### Cores medidas no `Modelo.png`

| Elemento | Cor no modelo | CSS atual |
|---|---|---|
| Faixa do topo e botões | `#691818` | `#6b1f2e` |
| Fundo da página | `#fbf7f4` | `#fbf7f1` |
| Borda clara | `#d6bfbd` | não existe |
| Ícones das redes | `#8c0d1c` | não existe |

As diferenças são pequenas e ficam concentradas em `variables.css`. O modelo também usa botões
com cantos arredondados e sem caixa alta, enquanto o site atual usa botões em pílula e caixa
alta. O ajuste final é uma tarefa de Guilherme.

### Classes prontas

| Uso | Classes |
|---|---|
| Layout | `.container`, `.section`, `.section--tinted`, `.section__head`, `.visually-hidden` |
| Textos | `.eyebrow`, `.lead` |
| Botões | `.btn`, `.btn--primary`, `.btn--ghost`, `.btn--block`, `.btn--sm`, `.link-button` |
| Cards | `.product-grid`, `.product-card`, `.product-card__media`, `.product-card__body`, `.product-card__name`, `.product-card__price`, `.product-card__badge` |
| Campos | `.text-field`, `.field-hint`, `.form-hint` |
| Filtros | `.catalog-filters`, `.filter-chip`, `.chip`, `.chip-group` |
| Etapas | `.stepper`, `.stepper__step`, `.stepper__number`, `.step-nav` |
| Estado vazio | `.empty-state` |

### Classes novas da base

Guilherme cria estas classes na base, para deixar todas as telas padronizadas:

| Uso | Classes |
|---|---|
| Formulário | `.form`, `.form__grid`, `.form__field`, `.form__actions`, `.form__error` |
| Tabela | `.table-wrap`, `.table`, `.table__actions` |
| Avisos | `.alert`, `.alert--sucesso`, `.alert--erro` |
| Selos | `.badge`, `.badge--ok`, `.badge--alerta` |
| Área da gerente | `.admin-layout`, `.admin-nav`, `.page-head` |
| Peça com bordado | `.peca`, `.peca__mockup`, `.peca__matriz` |

Regras: use variáveis e não escreva cores fixas. Não crie CSS em `style=""`, salvo a posição e a
cor do bordado. Antes de criar uma classe, procure uma pronta. Layout de telas novas deve
funcionar em celular, porque a turma vai usar o sistema pelo telefone.

## 12. Responsabilidades

**Regra principal: duas pessoas nunca alteram o mesmo arquivo.**

| Pessoa | Telas | Lógica em PHP | Documento |
|---|---|---|---|
| **Arthur** | Insumos, Clientes, Produtos, Matrizes, Catálogo | Cadastro e listagem de Insumos e Clientes | [INSTRUCOES_ARTHUR.md](INSTRUCOES_ARTHUR.md) |
| **Eduardo** | Contas, Usuários, Hub, comentários, curtidas no JS | Autenticação, recuperação de senha, usuários e comentários | [INSTRUCOES_EDUARDO.md](INSTRUCOES_EDUARDO.md) |
| **Guilherme** | Home, painel, montador, header e footer | Base, banco, deploy, Matrizes, Produtos, Hub, curtidas, conversor, Gemini e integração | [INSTRUCOES_GUILHERME.md](INSTRUCOES_GUILHERME.md) |

Como o trabalho começa:

1. Guilherme publica a estrutura completa, com arquivos comentados e dados de exemplo.
2. Arthur e Eduardo seguem o próprio documento e os comentários `TODO` de cada arquivo.
3. Guilherme liga cada tela à sua lógica e cuida da integração.

Cada documento de instruções pode ser colado inteiro em uma IA generativa como contexto.

## 13. Como rodar no seu computador

Requisitos: Git e PHP 8.4 ou mais novo. O Docker só é necessário para reproduzir o ambiente do Render.

1. Clone o repositório:

```bash
git clone <url-do-repositorio>
```

2. Entre na pasta e copie o modelo de configuração:

```bash
cp .env.example .env
```

3. Preencha o `.env` com os dados que Guilherme enviar por mensagem privada. O `.env` nunca vai
   para o GitHub.

4. No Windows, dê dois cliques em `rodar-local.bat`.

O `rodar-local.bat` procura o PHP mais novo instalado, inclusive o do winget, mesmo que um PHP
antigo como o do XAMPP venha antes no PATH. Ele liga `pdo_pgsql`, `pgsql`, `curl`, `mbstring` e
`openssl` só durante a execução, avisa se faltar algo e abre <http://localhost:8000>. Ele não instala
nada. Se nenhum PHP 8.4 ou mais novo existir, mostra o comando para instalar.

Instale o PHP no Windows com:

```bash
winget install --id PHP.PHP.8.5 -e
```

**Gemini em localhost:** baixe <https://curl.se/ca/cacert.pem> e salve na raiz do projeto. O arquivo
fica fora do Git. Sem ele, chamadas HTTPS podem falhar neste ambiente. O banco não é afetado.

Fora do Windows, habilite as extensões no `php.ini` e rode:

```bash
php -S localhost:8000 -t public public/index.php
```

Todo o grupo usa o mesmo banco do Supabase. Alterações em tabelas passam sempre por Guilherme.

## 14. Como usar o Git na main

Todos trabalham direto na `main`, e por isso a regra principal é **cada um mexe só nos seus
arquivos**, que já estarão na pasta depois do `git pull`.

Antes de começar a trabalhar:

```bash
git pull --rebase origin main
```

Depois de testar, envie suas alterações:

```bash
git status
```

```bash
git add app/telas/insumos/lista.php
```

```bash
git commit -m "feat(insumos): monta a tela de listagem"
```

```bash
git pull --rebase origin main
```

```bash
git push origin main
```

Regras:

- Faça commits pequenos, várias vezes por dia. O histórico faz parte da nota.
- Adicione seus arquivos pelo nome. Evite `git add -A`.
- Rode `git status` antes do commit e confira a lista.
- Nunca use `git push --force`.
- Nunca faça commit de `.env`, senha ou chave.
- Não envie código que quebra a página inicial: cada push na `main` publica o site.
- Nunca altere arquivo de outra pessoa. Peça ao dono. Arquivos da base são com Guilherme.

Prefixos de commit: `feat`, `fix`, `style`, `docs`, `refactor`.

Se aparecer conflito durante o rebase, abra o arquivo, escolha o trecho correto, apague as marcas
`<<<<<<<`, `=======` e `>>>>>>>`, e rode:

```bash
git add <arquivo-resolvido>
```

Em caso de dúvida, pare e chame Guilherme antes de tentar comandos destrutivos.

Se você usa IA para programar, os commits continuam com você, e ela deve avisar antes de qualquer commit. Revise o código, teste e faça o commit
com suas próprias mãos.

## 15. Publicação

**Variáveis de ambiente no Render:**
`APP_ENV`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `GEMINI_API_KEY`, `GEMINI_MODEL`.

**Conexão com o Supabase:** use o **Transaction pooler**, na porta `6543`. A conexão direta usa
IPv6 por padrão, e o pooler também funciona por IPv4. Esse modo não aceita prepared statements no
servidor, por isso a conexão usa `PDO::ATTR_EMULATE_PREPARES`.

**Deploy:** Guilherme cria um **Web Service** no [Render](https://render.com/), liga o repositório
do GitHub e escolhe o ambiente Docker. O Render publica a imagem a cada push na `main`.

**Plano B para a apresentação:** se o Render falhar ou ficar lento, rode o projeto em um notebook e use o mesmo
banco do Supabase. Todos entram pelo IP do notebook na mesma rede:

```bash
php -S 0.0.0.0:8000 -t public public/index.php
```

## 16. Checklist da entrega

- [ ] Registro das autorizações do professor guardado
- [ ] Login, cadastro e recuperação de senha funcionando
- [ ] Quatro ou mais cadastros e visualizações funcionando
- [ ] Conversor e montador funcionando no celular
- [ ] Hub com curtidas e comentários funcionando
- [ ] Tutorial do Gemini funcionando, com texto reserva
- [ ] Commits de todos os integrantes no GitHub
- [ ] `.env` e chaves fora do repositório
- [ ] Marca Desenrola, logos e contatos reais aplicados, sem cidade ou região
- [ ] `database/backup.sql` gerado e testado
- [ ] ZIP do código, backup e link do GitHub enviados no Teams
- [ ] Apresentação ensaiada, com plano B pronto
