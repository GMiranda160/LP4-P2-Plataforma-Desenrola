<?php

declare(strict_types=1);

/**
 * Arquivo: ajudantes.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: funções globais usadas nas telas.
 *
 * Nenhuma destas funções acessa o banco, sessão ou servidor além do que foi
 * passado por parâmetro. Todas são puras, fáceis de testar e seguras para
 * serem chamadas em qualquer tela.
 */

/**
 * Escapa um valor para exibição em HTML.
 * Usada em toda saída de dado vindo de banco, formulário ou URL.
 */
function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Formata um número como moeda brasileira. Exemplo: R$ 39,90.
 * Aceita int, float e string numérica (o banco devolve numérico como string).
 */
function moeda(mixed $valor): string
{
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

/**
 * Formata uma data do banco no padrão brasileiro.
 * "2026-11-19" vira "19/11/2026"; "2026-11-19 14:30:00" vira "19/11/2026 14:30".
 * Devolve string vazia para null e a entrada original se não for data válida.
 */
function data_br(?string $data): string
{
    if ($data === null || trim($data) === '') {
        return '';
    }

    try {
        $momento = new DateTimeImmutable($data);
    } catch (Exception) {
        return $data;
    }

    return str_contains($data, ':') ? $momento->format('d/m/Y H:i') : $momento->format('d/m/Y');
}

/**
 * Monta o link do WhatsApp do ateliê com a mensagem codificada na URL.
 * Sem argumento, usa a mensagem padrão definida em bootstrap.php.
 */
function link_whatsapp(string $mensagem = ''): string
{
    $texto = $mensagem !== '' ? $mensagem : WHATSAPP_MENSAGEM;

    return 'https://wa.me/' . ATELIE_WHATSAPP . '?text=' . rawurlencode($texto);
}

/**
 * Converte um SVG em data URI, para uso no atributo src de uma tag img.
 * O SVG gerado pelo servidor entra aqui; nunca renderize SVG vindo do navegador.
 */
function svg_data_uri(string $svg): string
{
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}
