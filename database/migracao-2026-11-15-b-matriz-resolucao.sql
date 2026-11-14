-- ==========================================================================
-- Migração: matrizes em até 256 pontos (antes: 64 no máximo)
-- Data: 15/11/2026
-- NÃO EXECUTAR sem revisão e sem backup atualizado, como a migração de produtos base.
-- Rode no SQL Editor do Supabase (projeto azgbhmxfkpaciqdcfzdl).
-- ==========================================================================

-- As duas constraints são "check constraints inline", nomeadas pelo Postgres
-- como <tabela>_<coluna>_check.
ALTER TABLE matrizes DROP CONSTRAINT IF EXISTS matrizes_largura_check;
ALTER TABLE matrizes DROP CONSTRAINT IF EXISTS matrizes_altura_check;

ALTER TABLE matrizes
  ADD CONSTRAINT matrizes_largura_check CHECK (largura BETWEEN 8 AND 256),
  ADD CONSTRAINT matrizes_altura_check  CHECK (altura BETWEEN 8 AND 256);

-- Sem mudança em total_pontos (só > 0) nem em MAX_CORES (paleta até 16 cores).
-- O código já aceita grades de até 256x256 e ~96 KB de JSON.
--
-- Rollback:
--   ALTER TABLE matrizes DROP CONSTRAINT matrizes_largura_check;
--   ALTER TABLE matrizes DROP CONSTRAINT matrizes_altura_check;
--   ALTER TABLE matrizes
--     ADD CONSTRAINT matrizes_largura_check CHECK (largura BETWEEN 8 AND 64),
--     ADD CONSTRAINT matrizes_altura_check  CHECK (altura BETWEEN 8 AND 64);
--   -- matrizes já criadas com larg/alt > 64 teriam que ser apagadas antes ou
--   -- recusadas pelo novo limite; por isso o rollback só serve antes de migrar dados.
-- ==========================================================================
