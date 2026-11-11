--
-- Arquivo: schema.sql
-- Dono: Guilherme. Só Guilherme altera este arquivo.
-- Responsabilidade: estrutura completa do banco do Desenrola, no PostgreSQL (Supabase).
--
-- Como aplicar: cole no SQL Editor do Supabase e execute.
-- O script pode ser reexecutado sem apagar dados existentes.
--
-- Convenções:
--   id sempre bigint identity, chaves com ON DELETE explícito;
--   booleanos como boolean nativo;
--   grade da matriz como jsonb (só ela precisa ser json);
--   dados da sessão como text (a serialização do PHP quebra em jsonb).

-- Contas com login. Usuário é diferente de Cliente (registro comercial).
CREATE TABLE IF NOT EXISTS usuarios (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nome text NOT NULL,
  email text NOT NULL UNIQUE,
  senha_hash text NOT NULL,
  perfil text NOT NULL DEFAULT 'usuario' CHECK (perfil IN ('usuario', 'gerente')),
  pergunta_seguranca text NOT NULL,
  resposta_hash text NOT NULL,
  ativo boolean NOT NULL DEFAULT true,
  criado_em timestamptz NOT NULL DEFAULT now()
);

-- Sessões do PHP, gravadas pelo SessaoBanco. Cookie fica no navegador.
CREATE TABLE IF NOT EXISTS sessoes (
  id text PRIMARY KEY,
  dados text NOT NULL DEFAULT '',
  expira_em timestamptz NOT NULL
);
CREATE INDEX IF NOT EXISTS sessoes_expira_idx ON sessoes (expira_em);

-- Clientes do ateliê. Não têm login no sistema.
CREATE TABLE IF NOT EXISTS clientes (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nome text NOT NULL,
  cpf_cnpj text NOT NULL UNIQUE,
  email text NOT NULL,
  telefone text NOT NULL,
  endereco text,
  cep text,
  criado_em timestamptz NOT NULL DEFAULT now()
);

-- Materiais e estoque.
CREATE TABLE IF NOT EXISTS insumos (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  tipo text NOT NULL CHECK (tipo IN ('tecido', 'linha', 'aviamento')),
  nome text NOT NULL,
  cor_hex text,
  quantidade numeric(10,2) NOT NULL DEFAULT 0 CHECK (quantidade >= 0),
  unidade text NOT NULL CHECK (unidade IN ('metro', 'carretel', 'peca')),
  custo_unitario numeric(10,2) NOT NULL CHECK (custo_unitario > 0),
  fornecedor text,
  estoque_minimo numeric(10,2) NOT NULL DEFAULT 0,
  criado_em timestamptz NOT NULL DEFAULT now()
);
CREATE UNIQUE INDEX IF NOT EXISTS insumos_tipo_nome_idx ON insumos (tipo, lower(nome));

-- Tipos de peça do mostruário. mockup aponta para public/assets/img/mockups/.
CREATE TABLE IF NOT EXISTS categorias (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nome text NOT NULL,
  slug text NOT NULL UNIQUE,
  mockup text NOT NULL
);

-- Desenhos de bordado. grade guarda os pontos e svg é o desenho gerado no servidor.
CREATE TABLE IF NOT EXISTS matrizes (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nome text NOT NULL,
  descricao text,
  categoria text NOT NULL DEFAULT 'Geral',
  grade jsonb NOT NULL,
  svg text NOT NULL,
  largura int NOT NULL CHECK (largura BETWEEN 8 AND 64),
  altura int NOT NULL CHECK (altura BETWEEN 8 AND 64),
  total_pontos int NOT NULL CHECK (total_pontos > 0),
  tempo_medio_min int NOT NULL DEFAULT 30 CHECK (tempo_medio_min > 0),
  padrao boolean NOT NULL DEFAULT false,
  autor_id bigint REFERENCES usuarios (id) ON DELETE SET NULL,
  criado_em timestamptz NOT NULL DEFAULT now()
);

-- Matriz aplicada numa peça. tipo=hub é o feed; tipo=produto alimenta o mostruário.
CREATE TABLE IF NOT EXISTS bordados (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  titulo text NOT NULL,
  descricao text,
  autor_id bigint NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
  matriz_id bigint NOT NULL REFERENCES matrizes (id) ON DELETE RESTRICT,
  categoria_id bigint NOT NULL REFERENCES categorias (id),
  cor_peca text NOT NULL,
  pos_x int NOT NULL CHECK (pos_x BETWEEN 0 AND 100),
  pos_y int NOT NULL CHECK (pos_y BETWEEN 0 AND 100),
  escala int NOT NULL CHECK (escala BETWEEN 10 AND 90),
  tipo text NOT NULL DEFAULT 'hub' CHECK (tipo IN ('hub', 'produto')),
  oculto boolean NOT NULL DEFAULT false,
  tutorial_ia text,
  criado_em timestamptz NOT NULL DEFAULT now()
);
CREATE INDEX IF NOT EXISTS bordados_feed_idx ON bordados (criado_em DESC) WHERE tipo = 'hub' AND NOT oculto;

-- Itens do mostruário. Cada combinação (bordado tipo=produto) rende no máximo um produto.
CREATE TABLE IF NOT EXISTS produtos (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  bordado_id bigint NOT NULL UNIQUE REFERENCES bordados (id) ON DELETE RESTRICT,
  nome text NOT NULL,
  descricao text,
  preco_base numeric(10,2) NOT NULL CHECK (preco_base > 0),
  destaque boolean NOT NULL DEFAULT false,
  ativo boolean NOT NULL DEFAULT true,
  criado_em timestamptz NOT NULL DEFAULT now()
);

-- Curtidas do Hub. Chave primária composta, sem id próprio.
CREATE TABLE IF NOT EXISTS curtidas (
  usuario_id bigint NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
  bordado_id bigint NOT NULL REFERENCES bordados (id) ON DELETE CASCADE,
  criado_em timestamptz NOT NULL DEFAULT now(),
  PRIMARY KEY (usuario_id, bordado_id)
);
CREATE INDEX IF NOT EXISTS curtidas_bordado_idx ON curtidas (bordado_id);

-- Comentários do Hub.
CREATE TABLE IF NOT EXISTS comentarios (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  bordado_id bigint NOT NULL REFERENCES bordados (id) ON DELETE CASCADE,
  usuario_id bigint NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
  texto text NOT NULL CHECK (char_length(texto) BETWEEN 1 AND 280),
  oculto boolean NOT NULL DEFAULT false,
  criado_em timestamptz NOT NULL DEFAULT now()
);
CREATE INDEX IF NOT EXISTS comentarios_bordado_idx ON comentarios (bordado_id, criado_em);

-- RLS sem políticas: o PHP conecta como dono das tabelas e passa por fora.
-- Isso fecha a API pública automática do Supabase para estas tabelas.
ALTER TABLE usuarios ENABLE ROW LEVEL SECURITY;
ALTER TABLE sessoes ENABLE ROW LEVEL SECURITY;
ALTER TABLE clientes ENABLE ROW LEVEL SECURITY;
ALTER TABLE insumos ENABLE ROW LEVEL SECURITY;
ALTER TABLE categorias ENABLE ROW LEVEL SECURITY;
ALTER TABLE matrizes ENABLE ROW LEVEL SECURITY;
ALTER TABLE bordados ENABLE ROW LEVEL SECURITY;
ALTER TABLE produtos ENABLE ROW LEVEL SECURITY;
ALTER TABLE curtidas ENABLE ROW LEVEL SECURITY;
ALTER TABLE comentarios ENABLE ROW LEVEL SECURITY;
