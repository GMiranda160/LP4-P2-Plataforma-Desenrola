# Instruções de Arthur: Telas, Insumos e Clientes

> **Para a IA que estiver lendo isto:** você ajuda Arthur em um trabalho de faculdade em PHP puro.
> Arthur só altera os arquivos da seção 3. Esses arquivos já existem, e cada um começa com um
> comentário dizendo o que fazer. Em Insumos e Clientes, ele escreve as telas e a lógica simples nas
> três camadas, copiando o padrão da seção 6. Nos demais, altera só as telas e usa as variáveis
> listadas aqui. Não adicione frameworks ou bibliotecas. Se faltar algo, pare e avise. Não use
> travessões em nenhum texto. Não faça commit nem push sem avisar Arthur e receber autorização.

## 1. Contexto

**Desenrola** é uma plataforma de bordados: um Hub onde as pessoas publicam suas criações, com o
mostruário do ateliê Cris Bordados e Bolsas. Stack: PHP 8.4 sem framework, HTML, CSS e JavaScript.
Entrega: 19/11/2026.

**Seu trabalho são as telas de gestão, o catálogo público e os cadastros completos de Insumos e
Clientes.** Guilherme cuida da base, do banco e do restante do back-end. Ele já deixou os arquivos na
pasta, com classes, métodos e comentários prontos para você completar.

Antes de cada arquivo, leia o comentário `TODO(Arthur)` no topo. Depois de concluir a tela, apague
esse `TODO` e mantenha o restante do comentário.

## 2. Como o código é organizado

O controlador recebe o pedido, o processamento valida, a camada de dados fala com o banco, e a tela
só mostra.

| Camada | Pasta | Pode | Não pode |
|---|---|---|---|
| Telas | `app/telas/` | Mostrar variáveis | SQL, regra de negócio |
| Controladores | `app/controladores/` | Ler `$_POST` e `$_GET`, escolher a tela | SQL |
| Processamento | `app/processamento/` | Validar | Ler `$_POST`, usar `$_SESSION`, gerar HTML |
| Dados | `app/dados/` | Executar SQL com parâmetros | Validar regra, gerar HTML |

Na sua tela, as variáveis já chegam prontas:

```php
<h1><?= e($titulo) ?></h1>
```

Ferramentas que você pode usar:

| Função | Uso |
|---|---|
| `e($texto)` | **Obrigatório** ao mostrar qualquer texto vindo de variável |
| `moeda($valor)` | Mostra `39.9` como `R$ 39,90` |
| `data_br($data)` | Mostra a data no formato brasileiro |
| `Csrf::campo()` | Campo obrigatório dentro de todo `<form method="post">` |
| `svg_data_uri($svg)` | Prepara o SVG da matriz para usar em `<img>` |
| `Visao::parcial('peca_bordada', ['item' => $produto])` | Desenha a peça com o bordado |
| `link_whatsapp('Mensagem')` | Monta o link do WhatsApp |

Nos seus controladores e classes de dados, use também:

| Função | Uso |
|---|---|
| `Conexao::pdo()` | Devolve a conexão com o banco |
| `Visao::render('insumos/lista', $dados, 'gerente')` | Mostra uma tela com o layout da gerente |
| `Resposta::redirecionar('/caminho')` | Redireciona e encerra |
| `Sessao::flash('sucesso', 'Texto')` | Mensagem para a próxima tela. Também aceita `erro` |
| `Sessao::guardarVelhos($_POST)` | Guarda o formulário antes de voltar com erro |
| `Sessao::tirarVelhos()` | Recupera esse formulário na tela de edição |

O roteador já valida o CSRF e já bloqueia quem não é gerente nas rotas `/gerente/...`.

O título da página, o menu, o rodapé e as mensagens de sucesso ou erro vêm do layout. Não crie outros.

## 3. Seus arquivos

```text
app/controladores/InsumoControlador.php
app/controladores/ClienteControlador.php
app/processamento/InsumoServico.php
app/processamento/ClienteServico.php
app/dados/InsumoDados.php
app/dados/ClienteDados.php
app/telas/insumos/lista.php
app/telas/insumos/form.php
app/telas/clientes/lista.php
app/telas/clientes/form.php
app/telas/produtos/lista.php
app/telas/produtos/form.php
app/telas/matrizes/lista.php
app/telas/matrizes/form.php
app/telas/catalogo/lista.php
app/telas/catalogo/produto.php
public/assets/css/pages/catalog.css
public/assets/css/pages/produto.css
```

**Qualquer outro arquivo é de outra pessoa.** Precisa de uma variável nova ou de uma mudança na
base? Peça a Guilherme.

## 4. Contrato das telas

### Insumos

**`insumos/lista.php`**: rota `/gerente/insumos`

- Recebe `$titulo`, `$busca`, `$insumos`.
- Cada insumo: `id`, `tipo`, `nome`, `cor_hex`, `quantidade`, `unidade`, `custo_unitario`,
  `fornecedor`, `estoque_minimo`.
- Mostra busca, tabela, botão **Novo insumo**, editar e excluir.
- Mostra **Estoque baixo** quando `quantidade <= estoque_minimo`.

**`insumos/form.php`**: rota `/gerente/insumos/form`

- Recebe `$titulo` e `$insumo`. Em cadastro novo, `$insumo` vem vazio.
- Envia POST para `/gerente/insumos/salvar`.
- Campos: `id` (oculto), `tipo`, `nome`, `cor_hex`, `quantidade`, `unidade`, `custo_unitario`,
  `fornecedor`, `estoque_minimo`.
- `tipo` aceita `tecido`, `linha`, `aviamento`.
- `unidade` aceita `metro`, `carretel`, `peca`.
- `cor_hex` usa `<input type="color">`.

Excluir: POST para `/gerente/insumos/excluir`, com o campo `id`.

### Clientes

**`clientes/lista.php`**: rota `/gerente/clientes`

- Recebe `$titulo`, `$busca`, `$clientes`.
- Cada cliente: `id`, `nome`, `cpf_cnpj`, `email`, `telefone`, `endereco`, `cep`, `criado_em`.
- Mostra busca por nome ou CPF/CNPJ, tabela, novo, editar e excluir.

**`clientes/form.php`**: rota `/gerente/clientes/form`

- Recebe `$titulo` e `$cliente`.
- Envia POST para `/gerente/clientes/salvar`.
- Campos: `id` (oculto), `nome`, `cpf_cnpj`, `email`, `telefone`, `endereco`, `cep`.

Excluir: POST para `/gerente/clientes/excluir`, com o campo `id`.

### Produtos

**`produtos/lista.php`**: rota `/gerente/produtos`

- Recebe `$titulo`, `$produtos`.
- Cada produto: `id`, `nome`, `categoria`, `preco_base`, `destaque`, `ativo`.
- Mostra situação, novo, editar, excluir e **Ativar/Desativar**.

**`produtos/form.php`**: rota `/gerente/produtos/form`

- Recebe `$titulo`, `$produto`, `$combinacoes`.
- Cada combinação: `id`, `titulo`.
- Envia POST para `/gerente/produtos/salvar`.
- Campos: `id` (oculto), `bordado_id`, `nome`, `descricao`, `preco_base`, `destaque` e `ativo`.
- `bordado_id` é um `<select>` com as combinações.
- `destaque` e `ativo` são checkboxes.
- Ao lado do `<select>`, coloque um link para `/montador?modo=produto`, com o texto
  **Criar combinação**.

Excluir: POST para `/gerente/produtos/excluir`, com `id`.
Ativar ou desativar: POST para `/gerente/produtos/ativar`, com `id`.

### Matrizes

**`matrizes/lista.php`**: rota `/gerente/matrizes`

- Recebe `$titulo`, `$matrizes`.
- Cada matriz: `id`, `nome`, `categoria`, `largura`, `altura`, `total_pontos`, `tempo_medio_min`,
  `svg`.
- Mostra miniatura, nome, categoria, tamanho, pontos, tempo, editar e excluir.
- O botão **Nova matriz** leva para `/montador?modo=matriz`.

Mostre a miniatura sempre em uma tag `img`:

```php
<img src="<?= e(svg_data_uri($matriz['svg'])) ?>" alt="<?= e($matriz['nome']) ?>">
```

**`matrizes/form.php`**: rota `/gerente/matrizes/form`

- Recebe `$titulo`, `$matriz`.
- Envia POST para `/gerente/matrizes/salvar`.
- Campos: `id` (oculto), `nome`, `descricao`, `categoria`, `tempo_medio_min`.

Excluir: POST para `/gerente/matrizes/excluir`, com `id`.

### Catálogo público

**`catalogo/lista.php`**: rota `/catalogo`

- Recebe `$titulo`, `$categorias`, `$categoriaAtual`, `$produtos`.
- Cada categoria: `nome`, `slug`.
- Mostra filtros com link `?categoria=slug` e a grade de produtos.
- Sem produtos, mostra `.empty-state`.

**`catalogo/produto.php`**: rota `/produto?id=`

- Recebe `$titulo`, `$produto`.
- Produto: `id`, `nome`, `descricao`, `preco_base`, `categoria`, mais os dados da peça.
- Mostra peça, nome, descrição, preço e botão **Solicitar orçamento**.

```php
<?php Visao::parcial('peca_bordada', ['item' => $produto]); ?>

<a class="btn btn--primary btn--block" target="_blank" rel="noopener noreferrer"
   href="<?= e(link_whatsapp("Olá! Quero um orçamento de: {$produto['nome']}")) ?>">
  Solicitar orçamento
</a>
```

## 5. Exemplos para copiar

### Lista

```php
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/gerente/insumos/form">Novo insumo</a>
</div>

<form method="get" class="form">
  <label class="visually-hidden" for="busca">Buscar</label>
  <input class="text-field" id="busca" name="busca" value="<?= e($busca) ?>" placeholder="Buscar por nome">
</form>

<?php if (!$insumos): ?>
  <p class="empty-state">Nenhum insumo encontrado.</p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Nome</th>
          <th>Quantidade</th>
          <th>Custo</th>
          <th>Situação</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($insumos as $i): ?>
          <tr>
            <td><?= e($i['tipo']) ?></td>
            <td><?= e($i['nome']) ?></td>
            <td><?= e($i['quantidade']) ?> <?= e($i['unidade']) ?></td>
            <td><?= moeda($i['custo_unitario']) ?></td>
            <td>
              <?php if ((float) $i['quantidade'] <= (float) $i['estoque_minimo']): ?>
                <span class="badge badge--alerta">Estoque baixo</span>
              <?php else: ?>
                <span class="badge badge--ok">Ok</span>
              <?php endif; ?>
            </td>
            <td class="table__actions">
              <a class="btn btn--ghost btn--sm" href="/gerente/insumos/form?id=<?= (int) $i['id'] ?>">Editar</a>
              <form method="post" action="/gerente/insumos/excluir"
                    onsubmit="return confirm('Tem certeza que deseja excluir este insumo?')">
                <?= Csrf::campo() ?>
                <input type="hidden" name="id" value="<?= (int) $i['id'] ?>">
                <button class="btn btn--ghost btn--sm" type="submit">Excluir</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
```

### Formulário

```php
<?php
$tipos = ['tecido' => 'Tecido', 'linha' => 'Linha', 'aviamento' => 'Aviamento'];
?>
<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="form" method="post" action="/gerente/insumos/salvar">
  <?= Csrf::campo() ?>
  <input type="hidden" name="id" value="<?= (int) ($insumo['id'] ?? 0) ?>">

  <div class="form__grid">
    <div class="form__field">
      <label for="tipo">Tipo</label>
      <select class="text-field" id="tipo" name="tipo" required>
        <option value="">Selecione</option>
        <?php foreach ($tipos as $valor => $rotulo): ?>
          <option value="<?= $valor ?>" <?= ($insumo['tipo'] ?? '') === $valor ? 'selected' : '' ?>>
            <?= $rotulo ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form__field">
      <label for="nome">Nome</label>
      <input class="text-field" id="nome" name="nome" value="<?= e($insumo['nome'] ?? '') ?>" required>
    </div>
  </div>

  <div class="form__actions">
    <button class="btn btn--primary" type="submit">Salvar</button>
    <a class="btn btn--ghost" href="/gerente/insumos">Cancelar</a>
  </div>
</form>
```

Repita o padrão nos outros campos. Todo `<input>` deve ter um `<label>` com `for` igual ao seu `id`.

### Card do catálogo

```php
<div class="product-grid">
  <?php foreach ($produtos as $produto): ?>
    <article class="product-card">
      <a class="product-card__media" href="/produto?id=<?= (int) $produto['id'] ?>">
        <?php Visao::parcial('peca_bordada', ['item' => $produto]); ?>
      </a>
      <div class="product-card__body">
        <h2 class="product-card__name"><?= e($produto['nome']) ?></h2>
        <p class="product-card__price">A partir de <?= moeda($produto['preco_base']) ?></p>
      </div>
    </article>
  <?php endforeach; ?>
</div>
```

## 6. Lógica de Insumos e Clientes

Aqui você escreve as três camadas. Comece por Insumos, com este exemplo, e repita o padrão em Clientes.

### Tabelas

```sql
insumos (
  id bigint, tipo text, nome text, cor_hex text,
  quantidade numeric(10,2), unidade text, custo_unitario numeric(10,2),
  fornecedor text, estoque_minimo numeric(10,2), criado_em timestamptz
)

clientes (
  id bigint, nome text, cpf_cnpj text,
  email text, telefone text, endereco text, cep text, criado_em timestamptz
)
```

### Dados: `app/dados/InsumoDados.php`

```php
<?php

class InsumoDados
{
    public function listar(string $busca = ''): array
    {
        $sql = 'SELECT * FROM insumos WHERE nome ILIKE :busca ORDER BY tipo, nome';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['busca' => "%{$busca}%"]);
        return $consulta->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $consulta = Conexao::pdo()->prepare('SELECT * FROM insumos WHERE id = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    public function existeNome(string $tipo, string $nome, int $ignorarId = 0): bool
    {
        $sql = 'SELECT 1 FROM insumos WHERE tipo = :tipo AND lower(nome) = lower(:nome) AND id <> :id';
        $consulta = Conexao::pdo()->prepare($sql);
        $consulta->execute(['tipo' => $tipo, 'nome' => $nome, 'id' => $ignorarId]);
        return (bool) $consulta->fetchColumn();
    }

    public function inserir(array $d): void
    {
        $sql = 'INSERT INTO insumos
                (tipo, nome, cor_hex, quantidade, unidade, custo_unitario, fornecedor, estoque_minimo)
                VALUES
                (:tipo, :nome, :cor_hex, :quantidade, :unidade, :custo_unitario, :fornecedor, :estoque_minimo)';
        Conexao::pdo()->prepare($sql)->execute($d);
    }

    public function atualizar(int $id, array $d): void
    {
        $sql = 'UPDATE insumos SET tipo = :tipo, nome = :nome, cor_hex = :cor_hex,
                quantidade = :quantidade, unidade = :unidade, custo_unitario = :custo_unitario,
                fornecedor = :fornecedor, estoque_minimo = :estoque_minimo
                WHERE id = :id';
        Conexao::pdo()->prepare($sql)->execute($d + ['id' => $id]);
    }

    public function excluir(int $id): void
    {
        Conexao::pdo()->prepare('DELETE FROM insumos WHERE id = :id')->execute(['id' => $id]);
    }

    public function contar(): int
    {
        return (int) Conexao::pdo()->query('SELECT count(*) FROM insumos')->fetchColumn();
    }
}
```

### Processamento: `app/processamento/InsumoServico.php`

```php
<?php

class InsumoServico
{
    private const TIPOS = ['tecido', 'linha', 'aviamento'];
    private const UNIDADES = ['metro', 'carretel', 'peca'];

    public function __construct(private InsumoDados $dados = new InsumoDados()) {}

    public function listar(string $busca = ''): array
    {
        return $this->dados->listar($busca);
    }

    public function buscar(int $id): ?array
    {
        return $this->dados->buscar($id);
    }

    /** Devolve a lista de erros. Lista vazia significa que salvou. */
    public function salvar(int $id, array $c): array
    {
        $d = [
            'tipo' => trim($c['tipo'] ?? ''),
            'nome' => trim($c['nome'] ?? ''),
            'cor_hex' => trim($c['cor_hex'] ?? ''),
            'quantidade' => (float) str_replace(',', '.', $c['quantidade'] ?? '0'),
            'unidade' => trim($c['unidade'] ?? ''),
            'custo_unitario' => (float) str_replace(',', '.', $c['custo_unitario'] ?? '0'),
            'fornecedor' => trim($c['fornecedor'] ?? ''),
            'estoque_minimo' => (float) str_replace(',', '.', $c['estoque_minimo'] ?? '0'),
        ];

        $erros = [];
        if (!in_array($d['tipo'], self::TIPOS, true)) $erros[] = 'Selecione o tipo.';
        if ($d['nome'] === '') $erros[] = 'Informe o nome.';
        if (!in_array($d['unidade'], self::UNIDADES, true)) $erros[] = 'Selecione a unidade.';
        if ($d['quantidade'] < 0) $erros[] = 'A quantidade não pode ser negativa.';
        if ($d['custo_unitario'] <= 0) $erros[] = 'O custo deve ser maior que zero.';
        if ($d['cor_hex'] !== '' && !preg_match('/^#[0-9a-f]{6}$/i', $d['cor_hex'])) {
            $erros[] = 'Cor inválida.';
        }
        if ($this->dados->existeNome($d['tipo'], $d['nome'], $id)) {
            $erros[] = 'Já existe um insumo com este nome neste tipo.';
        }

        if ($erros) return $erros;

        $id ? $this->dados->atualizar($id, $d) : $this->dados->inserir($d);
        return [];
    }

    public function excluir(int $id): void
    {
        $this->dados->excluir($id);
    }
}
```

### Controlador: `app/controladores/InsumoControlador.php`

```php
<?php

class InsumoControlador
{
    public function __construct(private InsumoServico $servico = new InsumoServico()) {}

    public function listar(): void
    {
        $busca = trim($_GET['busca'] ?? '');
        Visao::render('insumos/lista', [
            'titulo' => 'Insumos',
            'busca' => $busca,
            'insumos' => $this->servico->listar($busca),
        ], 'gerente');
    }

    public function form(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $insumo = $id ? $this->servico->buscar($id) : [];

        if ($id && !$insumo) {
            Sessao::flash('erro', 'Insumo não encontrado.');
            Resposta::redirecionar('/gerente/insumos');
        }

        Visao::render('insumos/form', [
            'titulo' => $id ? 'Editar insumo' : 'Novo insumo',
            'insumo' => Sessao::tirarVelhos() ?: $insumo,
        ], 'gerente');
    }

    public function salvar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $erros = $this->servico->salvar($id, $_POST);

        if ($erros) {
            Sessao::flash('erro', implode(' ', $erros));
            Sessao::guardarVelhos($_POST);
            Resposta::redirecionar('/gerente/insumos/form' . ($id ? "?id={$id}" : ''));
        }

        Sessao::flash('sucesso', $id ? 'Insumo atualizado com sucesso!' : 'Insumo cadastrado com sucesso!');
        Resposta::redirecionar('/gerente/insumos');
    }

    public function excluir(): void
    {
        $this->servico->excluir((int) ($_POST['id'] ?? 0));
        Sessao::flash('sucesso', 'Insumo excluído com sucesso!');
        Resposta::redirecionar('/gerente/insumos');
    }
}
```

### Regras de Clientes

- Obrigatórios: nome, CPF/CNPJ, e-mail e telefone.
- CPF tem 11 dígitos e CNPJ tem 14. Grave somente os números, com `preg_replace('/\D/', '', $valor)`.
- E-mail válido, com `filter_var($email, FILTER_VALIDATE_EMAIL)`.
- CPF/CNPJ não pode repetir. Mensagem: `Já existe um cliente com este CPF/CNPJ.`
- A lista busca por nome ou CPF/CNPJ, com `ILIKE`.
- Mensagens: `Cliente cadastrado com sucesso!`, `Cliente atualizado com sucesso!` e
  `Cliente excluído com sucesso!`.

Crie `ClienteDados::contar(): int` como no exemplo de Insumos, pois o painel usa esse método.

**Segurança:** todo valor vai em parâmetro, como `:nome`. Nunca monte SQL juntando texto do usuário.

## 7. Guia visual

- Vinho: `var(--color-wine-700)`. Fundo: `var(--color-sand-50)`.
- Texto: `var(--color-ink)` e `var(--color-ink-soft)`.
- Títulos usam `--font-display`. O restante usa `--font-body`.
- Botões: `.btn .btn--primary`, `.btn .btn--ghost`, `.btn--sm`, `.btn--block`.
- Formulários: `.form`, `.form__grid`, `.form__field`, `.form__actions`, `.text-field`.
- Tabelas: `.table-wrap`, `.table`, `.table__actions`.
- Selos: `.badge`, `.badge--ok`, `.badge--alerta`.
- Catálogo: `.product-grid`, `.product-card`, `.catalog-filters`, `.filter-chip`.
- Estado vazio: `.empty-state`. Título com botão: `.page-head`.

Regras: use classes prontas antes de criar outras. Não escreva cores fixas. Não use `style=""`.
Teste tudo na largura de um celular, com a tabela rolando dentro de `.table-wrap`.

## 8. Ordem de trabalho

- [ ] Clonar e rodar o projeto, seguindo o README, seção 13.
- [ ] Entrar com a conta de gerente passada por Guilherme.
- [ ] Insumos: dados, processamento, controlador e telas
- [ ] Clientes: dados, processamento, controlador e telas
- [ ] `matrizes/lista.php` e `matrizes/form.php`
- [ ] `produtos/lista.php` e `produtos/form.php`
- [ ] `catalogo/lista.php` e `catalogo/produto.php`
- [ ] Ajustar `catalog.css` e `produto.css`

Em Matrizes, Produtos e Catálogo, a lógica é de Guilherme. Enquanto ela não estiver ligada ao banco,
a tela recebe dados de exemplo no mesmo formato.

## 9. Git

Faça um commit por tela concluída. Siga o README, seção 14.

```bash
git add app/telas/insumos/lista.php
```

```bash
git commit -m "feat(insumos): monta a tela de listagem"
```

## 10. Pronto significa

- [ ] Insumos e Clientes: listar, criar, editar, excluir e mostrar erros de validação
- [ ] Todas as telas mostram os dados e não exibem erros PHP
- [ ] Todo texto dinâmico passa por `e()`
- [ ] Todo formulário POST tem `Csrf::campo()` e seus campos usam os nomes deste documento
- [ ] Todas as telas funcionam no celular
- [ ] Os `TODO(Arthur)` foram removidos
- [ ] Você consegue explicar o caminho de um cadastro, da tela ao banco
