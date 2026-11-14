-- ==========================================================================
-- Migração: produtos base + imagens no Supabase Storage
-- Data: 15/11/2026  |  Autor: Guilherme (executada somente após revisão no chat)
--
-- O que muda:
--   1. produtos.bordado_id deixa de ser NOT NULL (produto "base", peça crua,
--      ex.: Toalha de rosto). O UNIQUE continua: Postgres permite vários NULL.
--   2. produtos e bordados ganham imagem_url (caminho do objeto no bucket
--      Supabase Storage, ex.: 'base/toalha-rosto.png'). NULL = usar mockup.
--   3. Nova tabela imagens_tmp: foto enviada pelo usuário, vida curta
--      (apagada ao finalizar a montagem; backstop de 1 dia purgado no código).
--
-- NÃO EXECUTAR sem revisão e sem backup atualizado.
-- Rode no SQL Editor do Supabase (projeto azgbhmxfkpaciqdcfzdl).
-- Faça um backup pelo painel ANTES (o database/backup.sql do repo está desatualizado).
-- ==========================================================================

-- 1) Produtos base não têm bordado.
ALTER TABLE produtos ALTER COLUMN bordado_id DROP NOT NULL;

-- 2) Apontar para a imagem no bucket 'mostruario' (caminho, não URL completa).
ALTER TABLE produtos ADD COLUMN IF NOT EXISTS imagem_url text;
ALTER TABLE bordados ADD COLUMN IF NOT EXISTS imagem_url text;

-- 3) Uploads em edição: foto do usuário enquanto monta (vida curta).
CREATE TABLE IF NOT EXISTS imagens_tmp (
  id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  usuario_id bigint NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE,
  dados bytea NOT NULL,
  mimetype text NOT NULL,
  criado_em timestamptz NOT NULL DEFAULT now()
);
CREATE INDEX IF NOT EXISTS imagens_tmp_criado_idx ON imagens_tmp (criado_em);

-- ==========================================================================
-- Rollback (só após garantir que não há produtos base cadastrados):
--   DROP TABLE imagens_tmp;
--   ALTER TABLE bordados DROP COLUMN imagem_url;
--   ALTER TABLE produtos  DROP COLUMN imagem_url;
--   DELETE FROM produtos WHERE bordado_id IS NULL;
--   ALTER TABLE produtos ALTER COLUMN bordado_id SET NOT NULL;
-- ==========================================================================
