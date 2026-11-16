<?php
/**
 * Tela: ajudantes das telas do Hub
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Recebe: nada; carregado com require_once pelas telas do Hub.
 * Responsabilidade: funções de apresentação do Hub: tempo relativo ("há 2 h"), avatar com a inicial
 *                   do nome (no mesmo estilo do menu do usuário) e ícones de traço, como os do cabeçalho.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */

if (!function_exists('hub_tempo')) {
    /**
     * Tempo relativo de uma data: "agora", "há 5 min", "há 3 h", "há 2 dias".
     * Depois de uma semana, mostra a data no formato brasileiro.
     */
    function hub_tempo(?string $data): string
    {
        if ($data === null || trim($data) === '') {
            return '';
        }

        try {
            $momento = new DateTimeImmutable($data);
        } catch (Exception) {
            return $data;
        }

        $segundos = time() - $momento->getTimestamp();

        if ($segundos < 60) {
            return 'agora';
        }

        if ($segundos < 3600) {
            return 'há ' . intdiv($segundos, 60) . ' min';
        }

        if ($segundos < 86400) {
            return 'há ' . intdiv($segundos, 3600) . ' h';
        }

        if ($segundos < 604800) {
            $dias = intdiv($segundos, 86400);

            return 'há ' . $dias . ($dias === 1 ? ' dia' : ' dias');
        }

        return $momento->format('d/m/Y');
    }

    /** Avatar redondo com a inicial do nome, no vinho da marca. Tamanhos: sm e md. */
    function hub_avatar(string $nome, string $tamanho = 'md'): string
    {
        $nome = trim($nome);
        $inicial = $nome !== '' ? mb_strtoupper(mb_substr($nome, 0, 1)) : '?';

        return '<span class="hub-avatar hub-avatar--' . e($tamanho) . '" aria-hidden="true">' . e($inicial) . '</span>';
    }

    /**
     * Ícone de traço em SVG inline, que herda a cor do texto.
     * Nomes: coracao, comentario, tutorial, olho, olho-fechado, lixeira, matriz, pontos, novo,
     *        todas, avental, bolsa, toalha, personalizados, recentes.
     */
    function hub_icone(string $nome): string
    {
        $caminhos = [
            'coracao' => '<path d="M19.5 12.6 12 20l-7.5-7.4A4.9 4.9 0 0 1 12 6.1a4.9 4.9 0 0 1 7.5 6.5Z"/>',
            'comentario' => '<path d="M21 12a8 8 0 0 1-11.6 7.1L4 20.5l1.4-4.6A8 8 0 1 1 21 12Z"/>',
            'tutorial' => '<path d="M12 3.5 13.6 8a2 2 0 0 0 1.3 1.3l4.6 1.6-4.6 1.6a2 2 0 0 0-1.3 1.3L12 18.5l-1.6-4.6a2 2 0 0 0-1.3-1.3L4.5 11l4.6-1.6A2 2 0 0 0 10.4 8Z"/><path d="M19 3v3M17.5 4.5h3"/>',
            'olho' => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="2.8"/>',
            'olho-fechado' => '<path d="M9.9 5.7A9.6 9.6 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17 17 0 0 1-2.6 3.4M6.6 6.6C3.9 8.3 2.5 12 2.5 12S6 18.5 12 18.5a9.3 9.3 0 0 0 5.4-1.7"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/><path d="m3 3 18 18"/>',
            'lixeira' => '<path d="M4 7h16M9.5 7V4.5h5V7M6 7l1 13h10l1-13"/><path d="M10 11v5.5M14 11v5.5"/>',
            'matriz' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 9.3h16M4 14.7h16M9.3 4v16M14.7 4v16"/>',
            'pontos' => '<path d="m5 5 4 4M9 5 5 9M15 5l4 4M19 5l-4 4M5 15l4 4M9 15l-4 4M15 15l4 4M19 15l-4 4"/>',
            'novo' => '<path d="M12 5v14M5 12h14"/>',
            'recentes' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
            'todas' => '<rect x="4" y="4" width="6.5" height="6.5" rx="1.4"/><rect x="13.5" y="4" width="6.5" height="6.5" rx="1.4"/><rect x="4" y="13.5" width="6.5" height="6.5" rx="1.4"/><rect x="13.5" y="13.5" width="6.5" height="6.5" rx="1.4"/>',
            'avental' => '<path d="M9.5 4h5v3l1.6 2.2L14.5 20h-5L7.9 9.2 9.5 7z"/><path d="M9.5 4a.8.8 0 0 0 5 0"/>',
            'bolsa' => '<path d="M5.5 9.5h13l-1.2 10.2a2 2 0 0 1-2 1.8H8.7a2 2 0 0 1-2-1.8L5.5 9.5Z"/><path d="M9 13V7.5a3 3 0 0 1 6 0V13"/>',
            'toalha' => '<rect x="4" y="7" width="16" height="10" rx="1.5"/><path d="M4 10.3h16M4 13.7h16"/>',
            'personalizados' => '<path d="M4 20l1-4L16.5 4.5a2.1 2.1 0 0 1 3 3L8 19l-4 1Z"/><path d="M13 8l3 3"/>',
        ];

        return '<svg class="hub-icone" viewBox="0 0 24 24" aria-hidden="true" focusable="false">'
            . ($caminhos[$nome] ?? '')
            . '</svg>';
    }

    /**
     * Ícone da categoria pelo slug; slug desconhecido cai no ícone de grade.
     */
    function hub_icone_categoria(string $slug): string
    {
        $mapa = [
            'todas' => 'todas',
            'aventais' => 'avental',
            'bolsas' => 'bolsa',
            'toalhas' => 'toalha',
            'personalizados' => 'personalizados',
        ];

        return hub_icone($mapa[$slug] ?? 'todas');
    }
}
