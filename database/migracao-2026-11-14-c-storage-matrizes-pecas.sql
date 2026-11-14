-- ==========================================================================
-- Migração: arquivos das matrizes no Storage e tabela de peças base
-- Data: 14/11/2026
--
-- O que muda:
--   1. matrizes ganha svg_path e grade_path (caminho do objeto no bucket
--      'mostruario', ex.: matrizes/3f9c...svg) e paleta (jsonb pequeno, usado
--      no Hub e no tutorial). svg e grade deixam de ser obrigatórias: depois
--      do envio das matrizes antigas para o Storage elas ficam NULL.
--   2. Nova tabela pecas: peças base com foto real (toalhas, avental...),
--      cadastradas pela gerente em /gerente/pecas. A foto fica no bucket.
--      Já entram as 3 peças que estavam fixas no código do montador.
-- Rode no SQL Editor do Supabase (projeto azgbhmxfkpaciqdcfzdl).
-- ==========================================================================

-- 1) Matrizes: arquivos no Storage, só metadados no banco.
ALTER TABLE matrizes ADD COLUMN IF NOT EXISTS svg_path text;
ALTER TABLE matrizes ADD COLUMN IF NOT EXISTS grade_path text;
ALTER TABLE matrizes ADD COLUMN IF NOT EXISTS paleta jsonb NOT NULL DEFAULT '[]'::jsonb;
ALTER TABLE matrizes ALTER COLUMN svg DROP NOT NULL;
ALTER TABLE matrizes ALTER COLUMN grade DROP NOT NULL;
UPDATE matrizes SET paleta = grade->'paleta' WHERE grade IS NOT NULL AND paleta = '[]'::jsonb;
ALTER TABLE matrizes DROP CONSTRAINT IF EXISTS matrizes_arquivo_check;
ALTER TABLE matrizes ADD CONSTRAINT matrizes_arquivo_check CHECK (svg_path IS NOT NULL OR svg IS NOT NULL);

-- 2) Peças base.
CREATE TABLE IF NOT EXISTS pecas (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nome text NOT NULL,
  categoria_id bigint NOT NULL REFERENCES categorias (id),
  imagem_path text NOT NULL,
  ativo boolean NOT NULL DEFAULT true,
  criado_em timestamptz NOT NULL DEFAULT now()
);

INSERT INTO pecas (nome, categoria_id, imagem_path)
SELECT v.nome, c.id, v.imagem_path
FROM (VALUES
  ('Toalha de rosto', 'toalhas', 'base/toalha-rosto.png', 1),
  ('Toalha de banho', 'toalhas', 'base/toalha-banho.png', 2),
  ('Avental', 'aventais', 'base/avental.png', 3)
) AS v (nome, slug, imagem_path, ordem)
JOIN categorias c ON c.slug = v.slug
WHERE NOT EXISTS (SELECT 1 FROM pecas)
ORDER BY v.ordem;

-- ==========================================================================
-- Rollback (só antes de apagar as colunas svg e grade antigas):
--   DROP TABLE pecas;
--   ALTER TABLE matrizes DROP CONSTRAINT matrizes_arquivo_check;
--   ALTER TABLE matrizes DROP COLUMN svg_path, DROP COLUMN grade_path, DROP COLUMN paleta;
--   (svg e grade só voltam a NOT NULL se todas as linhas tiverem os dois preenchidos)
-- ==========================================================================
