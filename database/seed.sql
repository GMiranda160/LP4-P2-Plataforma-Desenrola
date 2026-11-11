--
-- Arquivo: seed.sql
-- Dono: Guilherme. Só Guilherme altera este arquivo.
-- Responsabilidade: dados mínimos para o sistema iniciar.
--
-- Conteúdo:
--   1. As quatro categorias do mostruário (com os mockups em public/assets/img/mockups/).
--   2. As três contas de gerente (Guilherme, Eduardo e Arthur), para entrar no painel.
--
-- Não cria nenhum outro usuário: o resto do banco é populado pelo próprio site.
-- Pode ser reexecutado sem duplicar registros.
--
-- Como aplicar: rode schema.sql antes, e depois execute este arquivo no SQL Editor.

-- 1. Categorias -----------------------------------------------------------

INSERT INTO categorias (nome, slug, mockup) VALUES
  ('Toalhas',       'toalhas',       'toalha.svg'),
  ('Bolsas',        'bolsas',        'bolsa.svg'),
  ('Aventais',      'aventais',      'avental.svg'),
  ('Personalizados','personalizados','bastidor.svg')
ON CONFLICT (slug) DO NOTHING;

-- 2. Contas de gerente ---------------------------------------------------
-- Senha provisória: admin123 (troque no primeiro acesso, ou via SQL).
-- A pergunta de segurança é um placeholder: os gerentes não usam o fluxo
-- de recuperação pelo site. Resposta é uma string aleatória, sem significado.

INSERT INTO usuarios
  (nome, email, senha_hash, perfil, pergunta_seguranca, resposta_hash, ativo)
VALUES
  ('Guilherme Miranda', 'gmiranda@example.com',
   '$2y$12$Dk5gsIFb5hFbsASMocbHh.i3AlPEleQx8YPRPwy8b2vOSZT7Iruii',
   'gerente', 'Pergunta definida pelo administrador',
   '$2y$12$MewMSPkVzTYYHGmUyl0xDOx5E4n5LIHzTJEqZYH9nqGkyoJedVsV6',
   true),
  ('Eduardo Henrique', 'eduardo@example.com',
   '$2y$12$Dk5gsIFb5hFbsASMocbHh.i3AlPEleQx8YPRPwy8b2vOSZT7Iruii',
   'gerente', 'Pergunta definida pelo administrador',
   '$2y$12$MewMSPkVzTYYHGmUyl0xDOx5E4n5LIHzTJEqZYH9nqGkyoJedVsV6',
   true),
  ('Arthur Urel', 'arthur@example.com',
   '$2y$12$Dk5gsIFb5hFbsASMocbHh.i3AlPEleQx8YPRPwy8b2vOSZT7Iruii',
   'gerente', 'Pergunta definida pelo administrador',
   '$2y$12$MewMSPkVzTYYHGmUyl0xDOx5E4n5LIHzTJEqZYH9nqGkyoJedVsV6',
   true)
ON CONFLICT (email) DO NOTHING;
