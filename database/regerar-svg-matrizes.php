<?php

declare(strict_types=1);

/**
 * Arquivo: regerar-svg-matrizes.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: script de uso único que regera o SVG das matrizes antigas, feitas com
 * shape-rendering="crispEdges" (borda serrilhada), usando o GradeServico atual (borda suave).
 *
 * O SVG vai para um caminho NOVO no Storage, porque os arquivos sobem com cache de 1 ano:
 * sobrescrever o mesmo caminho deixaria o desenho velho no cache dos navegadores.
 * Ordem: envia o novo, troca o svg_path no banco e só então apaga o antigo.
 * Pula as matrizes que já estão sem crispEdges, então pode rodar de novo sem refazer nada.
 *
 * Uso:
 *   php database/regerar-svg-matrizes.php            só mostra o que faria (não altera nada)
 *   php database/regerar-svg-matrizes.php --aplicar  regera de verdade
 */

require __DIR__ . '/../app/config/bootstrap.php';

$aplicar = in_array('--aplicar', $argv, true);
$pdo = Conexao::pdo();
$storage = new StorageServico();
$grade = new GradeServico();

/** Baixa um arquivo público do bucket. Lança RuntimeException se não conseguir. */
function baixarPublico(string $caminho): string
{
    $curl = curl_init(StorageServico::urlPublica($caminho));
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20]);

    if (is_file(RAIZ . '/cacert.pem')) {
        curl_setopt($curl, CURLOPT_CAINFO, RAIZ . '/cacert.pem');
    }

    $conteudo = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

    if ($conteudo === false || $status !== 200) {
        throw new RuntimeException("não consegui baixar {$caminho} (HTTP {$status})");
    }

    return (string) $conteudo;
}

$matrizes = $pdo->query('SELECT id, svg_path, grade_path FROM matrizes
                         WHERE svg_path IS NOT NULL AND grade_path IS NOT NULL ORDER BY id')->fetchAll();
$semArquivo = (int) $pdo->query('SELECT count(*) FROM matrizes WHERE svg_path IS NULL OR grade_path IS NULL')->fetchColumn();
$regeradas = 0;
$puladas = 0;
$falhas = 0;

echo $aplicar ? "Modo: APLICAR\n\n" : "Modo: SIMULAÇÃO (nada será alterado; use --aplicar)\n\n";

foreach ($matrizes as $m) {
    $novoPath = null;

    try {
        if (!str_contains(baixarPublico($m['svg_path']), 'crispEdges')) {
            $puladas++;
            continue;
        }

        $svg = $grade->gerarSvg($grade->validar(baixarPublico($m['grade_path'])));

        if (!$aplicar) {
            echo "REGERARIA matriz {$m['id']} ({$m['svg_path']})\n";
            $regeradas++;
            continue;
        }

        $novoPath = StorageServico::novoCaminho('matrizes', 'svg');
        $storage->enviar($novoPath, $svg, 'image/svg+xml');

        $consulta = $pdo->prepare('UPDATE matrizes SET svg_path = :novo WHERE id = :id AND svg_path = :antigo');
        $consulta->execute(['novo' => $novoPath, 'id' => $m['id'], 'antigo' => $m['svg_path']]);

        if ($consulta->rowCount() !== 1) {
            throw new RuntimeException('a matriz mudou durante o script');
        }

        $storage->remover($m['svg_path']);
        echo "OK      matriz {$m['id']} -> {$novoPath}\n";
        $regeradas++;
    } catch (Throwable $erro) {
        $falhas++;

        if ($novoPath !== null) {
            $storage->remover($novoPath);
        }

        echo "FALHOU  matriz {$m['id']}: {$erro->getMessage()}\n";
    }
}

echo "\n" . count($matrizes) . " matrizes com arquivo: {$regeradas} " . ($aplicar ? 'regeradas' : 'a regerar')
    . ", {$puladas} já estavam suaves, {$falhas} falhas.\n";

if ($semArquivo > 0) {
    echo "{$semArquivo} matrizes ainda guardam o SVG no banco: rode antes database/enviar-matrizes-storage.php.\n";
}

exit($falhas > 0 ? 1 : 0);
