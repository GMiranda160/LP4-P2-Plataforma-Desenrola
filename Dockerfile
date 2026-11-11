#
# Arquivo: Dockerfile
# Dono: Guilherme. Só Guilherme altera este arquivo.
# Responsabilidade: imagem Docker publicada pelo Railway (ou Render), com FrankenPHP e PHP 8.4.
#
# Como funciona: a imagem FrankenPHP embute o Caddy e o PHP no mesmo processo.
# O Caddy serve os arquivos estáticos de public/ e manda o restante para o index.php.
#
FROM dunglas/frankenphp:1-php8.4-alpine
WORKDIR /app

# Extensão do banco (pdo_pgsql) e mbstring (usada nas regras de conta).
RUN install-php-extensions pdo_pgsql mbstring \
 && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY Caddyfile /etc/caddy/Caddyfile
COPY app ./app
COPY public ./public

ENV PORT=80
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
