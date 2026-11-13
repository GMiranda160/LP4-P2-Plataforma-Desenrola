# Instruções de Eduardo: Autenticação, Comentários e Telas do Hub

> **Para a IA que estiver lendo isto:** você ajuda Eduardo em um trabalho de faculdade em PHP puro.
> Eduardo só altera os arquivos da seção 3. Esses arquivos já existem, e cada um começa com um
> comentário dizendo o que fazer. Ele escreve as telas, o JavaScript do Hub e a lógica simples de
> autenticação, usuários e comentários nas três camadas, seguindo a seção 7. No feed e nas curtidas,
> a lógica é de Guilherme. Use as variáveis e rotas listadas aqui. Não adicione frameworks ou
> bibliotecas. Se faltar algo, pare e avise. Não use travessões em nenhum texto. Não faça commit
> nem push sem avisar Eduardo e receber autorização.

## 1. Contexto

**Desenrola** é uma plataforma de bordados: um Hub onde as pessoas publicam suas criações, com o
mostruário do ateliê Cris Bordados e Bolsas. Stack: PHP 8.4 sem framework, HTML, CSS e JavaScript.
Entrega: 19/11/2026.

**Seu trabalho é a experiência das pessoas na plataforma:** entrar, criar conta, recuperar senha,
navegar no feed, curtir e comentar. Na apresentação, a turma inteira vai usar essas telas pelo
celular, ao mesmo tempo.

Você também escreve a lógica de login, cadastro, recuperação de senha, lista de usuários e
comentários. Guilherme cuida da base, do banco e do restante do back-end. Ele já deixou seus arquivos
na pasta, com classes, métodos e comentários. Antes de cada um, leia o comentário `TODO(Eduardo)` no topo. Depois de concluir, apague esse `TODO` e
mantenha o restante do comentário.

## 2. Como o código é organizado

| Camada | Pasta | Pode | Não pode |
|---|---|---|---|
| Telas | `app/telas/` | Mostrar variáveis | SQL, regra de negócio |
| Controladores | `app/controladores/` | Ler `$_POST` e `$_GET`, usar `Sessao` e `Auth`, escolher a tela | SQL |
| Processamento | `app/processamento/` | Validar, usar `password_hash` | Ler `$_POST`, usar `$_SESSION`, gerar HTML |
| Dados | `app/dados/` | Executar SQL com parâmetros | Validar regra, gerar HTML |

| Função | Uso |
|---|---|
| `e($texto)` | **Obrigatório** ao mostrar texto, inclusive nome e título vindos de pessoas |
| `data_br($data)` | Mostra a data no formato brasileiro |
| `Csrf::campo()` | Campo obrigatório em todo `<form method="post">` |
| `Auth::id()` | Id do usuário logado ou `null` |
| `Auth::usuario()` | Dados do usuário logado ou `null` |
| `Auth::eGerente()` | Indica se quem está logado é gerente |
| `Visao::parcial('peca_bordada', ['item' => $b])` | Desenha a peça com o bordado |
| `Visao::parcial('tutorial', ['bordado' => $bordado])` | Bloco do tutorial com IA |

Nos controladores e nas classes de dados, use também:

| Função | Uso |
|---|---|
| `Conexao::pdo()` | Conexão com o banco |
| `Visao::render('auth/login', $dados)` | Tela com layout público |
| `Visao::render('usuarios/lista', $dados, 'gerente')` | Tela com layout da gerente |
| `Resposta::redirecionar('/hub')` | Redireciona e encerra |
| `Sessao::flash('sucesso', 'Texto')` | Mensagem. Também aceita `erro` |
| `Sessao::guardarVelhos($_POST)`, `Sessao::tirarVelhos()` | Mantém o formulário após um erro |
| `Sessao::definir`, `Sessao::obter`, `Sessao::remover` | Dados temporários de sessão |
| `Auth::entrar(array $usuario)` | Grava o login na sessão e troca o id da sessão |
| `Auth::sair()` | Encerra a sessão |

O roteador valida o CSRF e as permissões. Na tela, `Auth` serve só para mostrar ou esconder botões. A
regra real fica no servidor.

**Booleanos:** a conexão usa prepares emulados. Envie booleanos como `'t'` ou `'f'`.

**Comentários:** siga o modelo da seção 6.1 do `README.md`. Em resumo:

- Cabeçalho em todo arquivo, com `Camada` ou `Tela`, `Dono`, `Responsabilidade` e `Veja`. Nas telas,
  inclua também `Rota`, `Recebe`, `Campos de $x` e, nos formulários, `Envia POST para` e `Campos`.
- Um `/** */` acima de todo método público, dizendo o que ele faz e o que devolve. No controlador,
  comece pela rota: `/** POST /sair: encerra a sessão e volta para a home. */`.
- `//` só para decisões que não são óbvias.
- Ao terminar um método, troque o `TODO(Eduardo)` por esse comentário explicativo. Método pronto
  com o `TODO` antigo em cima não conta como pronto.

O título da página, o menu, o rodapé e as mensagens de sucesso ou erro vêm do layout. Não crie outros.

## 3. Seus arquivos

```text
app/controladores/AuthControlador.php
app/controladores/UsuarioControlador.php
app/controladores/ComentarioControlador.php
app/processamento/AuthServico.php
app/processamento/ComentarioServico.php
app/dados/UsuarioDados.php
app/dados/ComentarioDados.php
app/telas/auth/login.php
app/telas/auth/cadastro.php
app/telas/auth/recuperar.php
app/telas/auth/redefinir.php
app/telas/usuarios/lista.php
app/telas/hub/feed.php
app/telas/hub/_card.php
app/telas/hub/detalhe.php
app/telas/hub/_comentarios.php
app/telas/hub/meus.php
public/assets/css/pages/auth.css
public/assets/css/pages/hub.css
public/assets/js/pages/hub.js
```

**Qualquer outro arquivo é de outra pessoa.** Precisa de uma variável nova ou de uma mudança na
base? Peça a Guilherme.

## 4. Contrato das telas de conta

**Convenção de e-mail do projeto:** no Desenrola, todo e-mail de conta usa o domínio
`@example.com`. A pessoa digita só o que vem antes do @; o sistema monta `usuario@example.com`.
Não existe envio de e-mail no projeto, então não faz diferença para a entrega. Nas telas, mostre
um campo `usuario` com o sufixo fixo `@example.com` (somente leitura), por exemplo com a classe
`.input-group`. No servidor, o e-mail é montado pelo controlador e pelo serviço.

**Exemplo de referência:** `auth/cadastro.php` foi montada por Guilherme como exemplo de tela
para o Eduardo. O arquivo continua sendo dele; use como modelo para `login.php`, `recuperar.php`
e `redefinir.php`.

**`auth/login.php`**: rota `/login`

- Recebe `$titulo`, `$velhos`.
- Envia POST para `/login`, com `usuario` e `senha`. O e-mail completo é montado no servidor como `usuario@example.com`.
- Tem links para `/cadastro` e `/recuperar-senha`.

**`auth/cadastro.php`**: rota `/cadastro`

- Recebe `$titulo`, `$perguntas`, `$velhos`.
- Envia POST para `/cadastro`.
- Campos: `nome`, `usuario`, `senha`, `confirmar_senha`, `pergunta_seguranca`, `resposta`.
- `usuario` é o que vem antes do @; o servidor monta `usuario@example.com`.
- Mostre o sufixo `@example.com` ao lado do campo `usuario` (classe `.input-group`).
- `pergunta_seguranca` é um `<select>` com os valores de `$perguntas`.

**`auth/recuperar.php`**: rota `/recuperar-senha`

- Recebe `$titulo`.
- Envia POST para `/recuperar-senha`, com `usuario`. O servidor monta `usuario@example.com`.

**`auth/redefinir.php`**: aparece depois de informar o e-mail

- Recebe `$titulo`, `$pergunta`.
- Envia POST para `/recuperar-senha/redefinir`.
- Campos: `resposta`, `senha`, `confirmar_senha`.

Use `autocomplete` nos campos: `email`, `current-password` e `new-password`. Nos formulários,
`$velhos` devolve o que a pessoa digitou, exceto as senhas.

**`usuarios/lista.php`**: rota `/gerente/usuarios`

- Recebe `$titulo`, `$busca`, `$usuarios`.
- Cada usuário: `id`, `nome`, `email`, `perfil`, `criado_em`, `total_bordados`.
- Mostra busca e tabela, sem ações de edição.

## 5. Contrato das telas do Hub

**`hub/feed.php`**: rota `/hub`

- Recebe `$titulo`, `$bordados`, `$categorias`, `$categoriaAtual`, `$ordem`, `$pagina`, `$temMais`.
- Cada categoria: `nome`, `slug`.
- Filtros por link: `?categoria=slug`.
- Ordenação por link: `?ordem=recentes` ou `?ordem=curtidos`.
- Paginação com `?pagina=`. Mostre **Anterior** se `$pagina > 1`, e **Próxima** se `$temMais`.
- Preserve categoria e ordem nos links de paginação.
- Sem posts, mostre `.empty-state` com link para `/montador`.

**`hub/_card.php`**

Recebe `$b`, com: `id`, `titulo`, `autor`, `categoria`, `mockup`, `cor_peca`, `pos_x`, `pos_y`,
`escala`, `svg`, `curtidas`, `comentarios`, `curtiu`, `oculto`, `criado_em`.

```php
<article class="product-card">
  <a class="product-card__media" href="/hub/bordado?id=<?= (int) $b['id'] ?>">
    <?php Visao::parcial('peca_bordada', ['item' => $b]); ?>
  </a>

  <div class="product-card__body">
    <h2 class="product-card__name"><?= e($b['titulo']) ?></h2>
    <p>por <?= e($b['autor']) ?></p>

    <div class="hub-card__acoes">
      <button class="btn btn--ghost btn--sm js-curtir" type="button"
              data-id="<?= (int) $b['id'] ?>"
              aria-pressed="<?= $b['curtiu'] ? 'true' : 'false' ?>">
        ♥ <span class="js-curtidas"><?= (int) $b['curtidas'] ?></span>
      </button>

      <a class="btn btn--ghost btn--sm" href="/hub/bordado?id=<?= (int) $b['id'] ?>#comentarios">
        Comentários (<?= (int) $b['comentarios'] ?>)
      </a>
    </div>
  </div>
</article>
```

**`hub/detalhe.php`**: rota `/hub/bordado?id=`

- Recebe `$titulo`, `$bordado`, `$comentarios`, `$podeExcluir`.
- `$bordado` tem os campos do card, mais `descricao`, `autor_id`, `largura`, `altura`,
  `total_pontos`, `paleta` e `tipo`.
- `paleta` é uma lista de cores hexadecimais, para mostrar amostras das linhas.
- Mostra peça grande, dados, curtir, tutorial e comentários.
- Excluir, se `$podeExcluir`: POST para `/bordado/excluir`, com `id`.
- Ocultar, se `Auth::eGerente()`: POST para `/gerente/hub/ocultar`, com `id`.
- Se `oculto`, mostre o selo **Oculto**.

Inclua nesta ordem:

```php
<?php Visao::parcial('tutorial', ['bordado' => $bordado]); ?>
<?php require RAIZ . '/app/telas/hub/_comentarios.php'; ?>
```

**`hub/_comentarios.php`**

- Usa `$bordado` e `$comentarios`.
- Cada comentário: `id`, `usuario_id`, `autor`, `texto`, `oculto`, `criado_em`.
- A seção tem `id="comentarios"`.
- Com login, mostra formulário POST para `/hub/comentar`, com `bordado_id` e `texto`.
- `texto` é um `<textarea maxlength="280" required>`.
- Sem login, mostra um link para `/login`.
- Se `Auth::id() === (int) $comentario['usuario_id']`, mostra **Excluir**, com POST para
  `/hub/comentario/excluir` e campo `id`.
- Se `Auth::eGerente()`, mostra **Ocultar** ou **Reexibir**, com POST para
  `/gerente/hub/comentario/ocultar` e campo `id`.
- Comentário oculto só chega para a gerente. Mostre nele o selo **Oculto**.
- Sem comentários: `Seja a primeira pessoa a comentar.`

Mantenha o texto com `e()` e use `white-space: pre-line` no CSS.

**`hub/meus.php`**: rota `/meus-bordados`

- Recebe `$titulo`, `$bordados`, no formato do card.
- Reaproveite `_card.php`.
- Sem bordados, mostre um convite para `/montador`.

Para usar o card em uma lista:

```php
<div class="product-grid">
  <?php foreach ($bordados as $b): ?>
    <?php require RAIZ . '/app/telas/hub/_card.php'; ?>
  <?php endforeach; ?>
</div>
```

## 6. JavaScript do Hub

Crie as curtidas em `public/assets/js/pages/hub.js`. O servidor responde
`{ "curtiu": true, "curtidas": 12 }`, ou status 401 quando não há login.

```js
document.addEventListener('click', async (evento) => {
  const botao = evento.target.closest('.js-curtir');
  if (!botao) return;

  botao.disabled = true;

  try {
    const resposta = await fetch('/hub/curtir', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-Token': document.querySelector('meta[name="csrf"]').content,
      },
      body: new URLSearchParams({ id: botao.dataset.id }),
    });

    if (resposta.status === 401) {
      window.location.href = '/login';
      return;
    }

    if (!resposta.ok) throw new Error('Falha ao curtir');

    const dados = await resposta.json();
    botao.setAttribute('aria-pressed', String(dados.curtiu));
    botao.querySelector('.js-curtidas').textContent = dados.curtidas;
  } finally {
    botao.disabled = false;
  }
});
```

Em `hub.css`, dê ao botão um estado visual para `[aria-pressed="true"]`. Trate falhas de rede sem
quebrar a página.

## 7. Lógica de contas e comentários

### Tabelas

```sql
usuarios (
  id bigint, nome text, email text,       -- e-mail único, salvo em minúsculas
  senha_hash text, perfil text,           -- 'usuario' ou 'gerente'
  pergunta_seguranca text, resposta_hash text,
  ativo boolean, criado_em timestamptz
)

comentarios (
  id bigint, bordado_id bigint, usuario_id bigint,
  texto text, oculto boolean, criado_em timestamptz
)
```

### Assinaturas que outras partes usam

Mantenha exatamente estes nomes:

```php
// UsuarioDados
buscarPorEmail(string $email): ?array
buscarPorId(int $id): ?array
inserir(array $d): int
atualizarSenha(int $id, string $hash): void
listar(string $busca = ''): array
contar(): int

// ComentarioServico: usado por HubControlador, na página do bordado
listar(int $bordadoId, bool $incluirOcultos = false): array

// ComentarioDados: usado pelo painel
contar(): int
```

### Cadastro

- O formulário envia `usuario`, não e-mail completo. O serviço monta `usuario@example.com`.
- `usuario` aceita letras minúsculas, números, ponto, hífen e sublinhado, de 3 a 30 caracteres.
- E-mail válido e único, composto em minúsculas, salvo com `mb_strtolower(trim($email))`.
- Senha com no mínimo 6 caracteres e confirmação igual.
- Senha: `password_hash($senha, PASSWORD_DEFAULT)`.
- Resposta: `password_hash(mb_strtolower(trim($resposta)), PASSWORD_DEFAULT)`.
- Perfil sempre `usuario`. Nunca aceite o perfil vindo do formulário.
- Depois de criar, chame `Auth::entrar()` e redirecione para `/montador`.

Perguntas fixas: nome do primeiro animal de estimação, cidade onde nasceu, nome da primeira escola.

Mensagens: `Este usuário já está cadastrado.`, `A senha deve ter no mínimo 6 caracteres.` e
`As senhas não coincidem.`

### Login e saída

- `AuthServico::autenticar($email, $senha)` devolve o usuário ou `null`.
- Use `password_verify`.
- Erro sempre igual, sem dizer qual campo errou: `Usuário ou senha inválidos.`
- Bloqueie conta com `ativo` falso.
- Não bloqueie por IP: na demonstração, a turma pode sair pelo mesmo IP.
- `POST /sair` chama `Auth::sair()` e volta para `/`.
- Depois do login, gerente vai para `/gerente`, e usuário vai para `/hub`.

### Recuperar senha

O fluxo usa pergunta de segurança, pois o sistema não envia e-mail.

1. `GET /recuperar-senha` mostra o formulário com o e-mail.
2. `POST /recuperar-senha` busca o usuário. Se existir, grava `Sessao::definir('recuperar_id', $id)`
   e mostra `auth/redefinir` com a pergunta.
3. `POST /recuperar-senha/redefinir` recebe resposta, nova senha e confirmação.
4. Confere a resposta com `password_verify`, troca a senha e apaga `recuperar_id`.
5. Redireciona para `/login`, com `Senha alterada com sucesso!`

Regras:

- Conte as tentativas com `Sessao`. Depois de 5 erros, encerre o fluxo.
- Normalize a resposta como no cadastro.
- E-mail desconhecido recebe: `Não foi possível continuar com esses dados.`

### Usuários

`/gerente/usuarios` só lista. A consulta traz `total_bordados` com uma subconsulta em `bordados`.
**Nunca** envie `senha_hash` ou `resposta_hash` para a tela.

### Comentários

| Rota | Regra |
|---|---|
| `POST /hub/comentar` | Login obrigatório; `trim`; de 1 a 280 caracteres, contados com `mb_strlen`; até 5 comentários por minuto, contados em `Sessao`; redireciona para `/hub/bordado?id=...#comentarios` |
| `POST /hub/comentario/excluir` | Só o autor ou a gerente |
| `POST /gerente/hub/comentario/ocultar` | Inverte `oculto` |

`ComentarioServico::listar` devolve comentários do mais antigo para o mais novo, com o nome do autor
por `JOIN` em `usuarios`. Sem `$incluirOcultos`, filtre com `AND NOT c.oculto`.

Se o bordado não existir ou estiver oculto, o comentário é recusado:
`SELECT 1 FROM bordados WHERE id = :id AND NOT oculto`.

## 8. Guia visual

- Base: `--color-wine-700`, `--color-sand-50`, `--color-ink`.
- Títulos em `--font-display`, demais textos em `--font-body`.
- Hub: reaproveite `.product-grid`, `.product-card` e seus elementos.
- Filtros: `.catalog-filters`, `.filter-chip`.
- Formulários: `.form`, `.form__field`, `.form__actions`, `.text-field`.
- Tabelas: `.table-wrap`, `.table`.
- Selos: `.badge`, `.badge--ok`, `.badge--alerta`.

Regras: CSS próprio somente em `auth.css` e `hub.css`, com prefixos `auth-` e `hub-`. Sem cores
fixas. Área de toque com pelo menos 44px. Priorize o celular.

## 9. Ordem de trabalho

- [ ] Clonar e rodar o projeto, seguindo o README, seção 13.
- [ ] Cadastro e login: dados, processamento, controlador e telas
- [ ] Saída e recuperação de senha
- [ ] Lista de usuários
- [ ] `hub/_card` e `hub/feed`
- [ ] Curtidas em `hub.js`
- [ ] Comentários: dados, processamento, controlador e telas
- [ ] `hub/detalhe` e `hub/meus`
- [ ] Revisar tudo no celular

No feed, no detalhe e nas curtidas, a lógica é de Guilherme. Enquanto ela não estiver ligada ao banco,
a tela recebe dados de exemplo no mesmo formato.

## 10. Git

Faça um commit por tela ou recurso concluído. Siga o README, seção 14.

```bash
git add public/assets/js/pages/hub.js
```

```bash
git commit -m "feat(hub): adiciona curtidas sem recarregar a pagina"
```

## 11. Pronto significa

- [ ] Cadastro, login, saída e recuperação de senha funcionando
- [ ] Sessão mantida entre páginas e depois de atualizar
- [ ] Comentários: publicar, excluir e ocultar funcionando
- [ ] Telas de conta, feed, detalhe e meus bordados prontas
- [ ] Curtir funciona sem recarregar, e sem login leva para `/login`
- [ ] Todo texto dinâmico passa por `e()`
- [ ] Todo formulário POST tem `Csrf::campo()` e usa os nomes deste documento
- [ ] Tudo funciona no celular, sem rolagem lateral
- [ ] Os `TODO(Eduardo)` foram trocados por comentários explicativos no modelo do README, seção 6.1
- [ ] Você consegue explicar sessão, hash de senha, CSRF e o clique em curtir
