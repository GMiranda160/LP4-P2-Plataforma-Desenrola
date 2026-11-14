-- ==========================================================================
-- Migração: rotação do bordado na peça
-- Data: 14/11/2026
-- O montador agora posiciona o bordado com uma caixa de transformação
-- (mover, redimensionar e girar). A rotação fica em graus, de -180 a 180.
-- Aditiva: bordados antigos ficam com 0 (retos), sem mudar o que já existe.
-- Rode no SQL Editor do Supabase (projeto azgbhmxfkpaciqdcfzdl).
-- ==========================================================================

ALTER TABLE bordados
  ADD COLUMN IF NOT EXISTS rotacao int NOT NULL DEFAULT 0
  CONSTRAINT bordados_rotacao_check CHECK (rotacao BETWEEN -180 AND 180);

-- Rollback:
--   ALTER TABLE bordados DROP COLUMN rotacao;
