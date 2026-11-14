<?php

declare(strict_types=1);

/**
 * Arquivo: enviar-matrizes-storage.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: script de uso único que leva as matrizes antigas (svg e grade dentro da tabela)
 * para o Storage. Para cada matriz sem svg_path: envia o SVG e a grade, grava os caminhos e
 * só então limpa as colunas antigas. Pode rodar de novo sem duplicar nada.
 * Uso: php database/enviar-matrizes-storage.php
 */

require __DIR__ . '/../app/config/bootstrap.php';

$pdo = Conexao::pdo();
$storage = new StorageServico();
$pendentes = $pdo->query('SELECT id, svg, grade::text AS grade FROM matrizes WHERE svg_path IS NULL ORDER BY id')->fetchAll();
$falhas = 0;

foreach ($pendentes as $m) {
    $svgPath = StorageServico::novoCaminho('matrizes', 'svg');
    $gradePath = $m['grade'] !== null ? StorageServico::novoCaminho('matrizes', 'json') : null;

    try {
        $storage->enviar($svgPath, (string) $m['svg'], 'image/svg+xml');
        if ($gradePath !== null) {
            $storage->enviar($gradePath, (string) $m['grade'], 'application/json');
        }

        $consulta = $pdo->prepare('UPDATE matrizes SET svg_path = :svg, grade_path = :grade, svg = NULL, grade = NULL
                                   WHERE id = :id AND svg_path IS NULL');
        $consulta->execute(['svg' => $svgPath, 'grade' => $gradePath, 'id' => $m['id']]);
        echo "OK      matriz {$m['id']} -> {$svgPath}\n";
    } catch (Throwable $erro) {
        $falhas++;
        $storage->remover($svgPath);
        if ($gradePath !== null) {
            $storage->remover($gradePath);
        }
        echo "FALHOU  matriz {$m['id']}: {$erro->getMessage()}\n";
    }
}

echo count($pendentes) . " pendentes, {$falhas} falhas.\n";
exit($falhas > 0 ? 1 : 0);
