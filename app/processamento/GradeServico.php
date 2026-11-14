<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: validar a grade recebida do navegador e gerar o SVG no servidor.
 * Nunca confie na grade do navegador: tudo passa por validação aqui antes de salvar.
 * Veja INSTRUCOES_GUILHERME.md, seção 6.
 */
class GradeServico
{
    private const MIN = 8;
    private const MAX = 1024;
    private const MAX_CORES = 16;
    // Grade 1024x1024: pontos (1 char por célula, ~1 MB) + paleta + chaves do JSON, com folga.
    private const MAX_BYTES = 1048576 + 4096;

    /**
     * Devolve a grade validada (largura, altura, paleta, pontos, total_pontos).
     * Lança InvalidArgumentException com mensagem amigável se a grade for inválida.
     */
    public function validar(string $json): array
    {
        if (strlen($json) > self::MAX_BYTES) {
            throw new InvalidArgumentException('Grade muito grande.');
        }

        $grade = json_decode($json, true);

        $largura = is_array($grade) ? ($grade['largura'] ?? null) : null;
        $altura = is_array($grade) ? ($grade['altura'] ?? null) : null;
        $paleta = is_array($grade) ? ($grade['paleta'] ?? null) : null;
        $pontos = is_array($grade) ? ($grade['pontos'] ?? null) : null;

        $valida = is_int($largura) && is_int($altura)
            && $largura >= self::MIN && $largura <= self::MAX
            && $altura >= self::MIN && $altura <= self::MAX
            && is_array($paleta) && $paleta !== [] && count($paleta) <= self::MAX_CORES
            && is_string($pontos) && strlen($pontos) === $largura * $altura;

        // strspn em vez de regex: com 1 milhão de pontos a regex pode estourar o limite do PCRE.
        if (!$valida || strspn((string) $pontos, '.0123456789abcdef') !== strlen((string) $pontos)) {
            throw new InvalidArgumentException('Matriz inválida.');
        }

        foreach ($paleta as $cor) {
            if (!is_string($cor) || !preg_match('/^#[0-9a-f]{6}$/i', $cor)) {
                throw new InvalidArgumentException('Cor inválida.');
            }
        }

        // Cada símbolo acima de '.' é o índice hexadecimal da cor na paleta.
        foreach (str_split(count_chars($pontos, 3)) as $simbolo) {
            if ($simbolo !== '.' && hexdec($simbolo) >= count($paleta)) {
                throw new InvalidArgumentException('Cor fora da paleta.');
            }
        }

        $total = strlen($pontos) - substr_count($pontos, '.');

        if ($total === 0) {
            throw new InvalidArgumentException('A matriz está vazia.');
        }

        return [
            'largura' => $largura,
            'altura' => $altura,
            'paleta' => array_values($paleta),
            'pontos' => $pontos,
            'total_pontos' => $total,
        ];
    }

    /**
     * Gera um SVG pequeno: um path por cor, unindo pontos vizinhos da mesma linha.
     * O SVG é montado só no servidor; nunca salve SVG enviado pelo navegador.
     */
    public function gerarSvg(array $g): string
    {
        $caminhos = array_fill(0, count($g['paleta']), '');

        for ($y = 0; $y < $g['altura']; $y++) {
            $x = 0;

            while ($x < $g['largura']) {
                $simbolo = $g['pontos'][$y * $g['largura'] + $x];
                $inicio = $x;

                while ($x < $g['largura'] && $g['pontos'][$y * $g['largura'] + $x] === $simbolo) {
                    $x++;
                }

                if ($simbolo !== '.') {
                    $tamanho = $x - $inicio;
                    $caminhos[hexdec($simbolo)] .= "M{$inicio} {$y}h{$tamanho}v1h-{$tamanho}z";
                }
            }
        }

        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" shape-rendering="crispEdges">',
            $g['largura'],
            $g['altura']
        );

        foreach ($caminhos as $i => $caminho) {
            if ($caminho !== '') {
                $svg .= sprintf('<path fill="%s" d="%s"/>', $g['paleta'][$i], $caminho);
            }
        }

        return $svg . '</svg>';
    }
}
