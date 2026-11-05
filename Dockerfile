#
# Arquivo: Dockerfile
# Dono: Guilherme. Só Guilherme altera este arquivo.
# Responsabilidade: imagem Docker publicada pelo Render, com PHP 8.4 e FrankenPHP.
#
# TODO(Guilherme):
# 1. Usar a imagem dunglas/frankenphp:1-php8.4-alpine, com WORKDIR /app.
# 2. Instalar a extensão pdo_pgsql e ativar o php.ini de produção.
# 3. Copiar Caddyfile, app e public para a imagem.
# 4. Definir PORT=80 como padrão e iniciar o frankenphp com o Caddyfile.
# 5. Testar o deploy no Render antes de seguir com a base.
# Veja INSTRUCOES_GUILHERME.md, seção 2.1.
#
