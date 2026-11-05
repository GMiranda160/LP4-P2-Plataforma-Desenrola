<?php
/**
 * Arquivo: ajudantes.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: funções globais usadas nas telas.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */

/** TODO(Guilherme): escapar o valor com htmlspecialchars, ENT_QUOTES, ENT_SUBSTITUTE e UTF-8. */
function e(mixed $valor): string
{
    throw new RuntimeException('Não implementado: e');
}

/** TODO(Guilherme): formatar número como moeda, por exemplo R$ 39,90. */
function moeda(mixed $valor): string
{
    throw new RuntimeException('Não implementado: moeda');
}

/** TODO(Guilherme): formatar a data no padrão brasileiro. */
function data_br(?string $data): string
{
    throw new RuntimeException('Não implementado: data_br');
}

/** TODO(Guilherme): montar o link do WhatsApp com a mensagem codificada. */
function link_whatsapp(string $mensagem = ''): string
{
    throw new RuntimeException('Não implementado: link_whatsapp');
}

/** TODO(Guilherme): devolver data:image/svg+xml;base64 com o SVG. Usar só em tag img. */
function svg_data_uri(string $svg): string
{
    throw new RuntimeException('Não implementado: svg_data_uri');
}
