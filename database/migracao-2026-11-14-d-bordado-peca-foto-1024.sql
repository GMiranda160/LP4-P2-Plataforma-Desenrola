-- ==========================================================================
-- Migração: peça do bordado e matrizes até 1024 pontos
-- Data: 14/11/2026
--
-- O que muda:
--   1. bordados ganha peca_id (peça base escolhida no montador) e foto_path
--      (foto da própria peça enviada pela pessoa, no bucket 'mostruario',
--      pasta fotos/). Os cards passam a mostrar essa foto em vez do desenho
--      genérico da categoria. Bordados antigos ficam com os dois NULL.
--   2. matrizes passam a aceitar de 8 a 1024 pontos de lado (256, 512, 1024).
-- Rode no SQL Editor do Supabase (projeto azgbhmxfkpaciqdcfzdl).
-- ==========================================================================

ALTER TABLE bordados ADD COLUMN IF NOT EXISTS peca_id bigint REFERENCES pecas (id) ON DELETE SET NULL;
ALTER TABLE bordados ADD COLUMN IF NOT EXISTS foto_path text;

ALTER TABLE matrizes DROP CONSTRAINT IF EXISTS matrizes_largura_check;
ALTER TABLE matrizes DROP CONSTRAINT IF EXISTS matrizes_altura_check;
ALTER TABLE matrizes
  ADD CONSTRAINT matrizes_largura_check CHECK (largura BETWEEN 8 AND 1024),
  ADD CONSTRAINT matrizes_altura_check CHECK (altura BETWEEN 8 AND 1024);

-- Rollback:
--   ALTER TABLE bordados DROP COLUMN foto_path, DROP COLUMN peca_id;
--   (o limite de 256 só volta se nenhuma matriz passar de 256)
