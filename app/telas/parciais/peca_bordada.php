<?php
/**
 * Parcial: peça com bordado.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Espera $item:
 *   mockup   nome do arquivo em public/assets/img/mockups (ex.: toalha.svg)
 *   cor_peca cor da peça em hex (ex.: #f3ded9)
 *   svg      SVG da matriz gerado no servidor
 *   pos_x    posição horizontal, 0 a 100 (percentual)
 *   pos_y    posição vertical, 0 a 100 (percentual)
 *   escala   largura do bordado em percentual da peça, 10 a 90
 */

$mockup = basename((string) ($item['mockup'] ?? 'bastidor.svg'));
$caminho = RAIZ . '/public/assets/img/mockups/' . $mockup;
$desenho = is_file($caminho) ? (string) file_get_contents($caminho) : '';
?>
<div class="peca" style="color: <?= e($item['cor_peca'] ?? '#2e2321') ?>">
  <div class="peca__mockup" aria-hidden="true"><?= $desenho ?></div>
  <img class="peca__matriz"
       src="<?= e(svg_data_uri((string) ($item['svg'] ?? ''))) ?>"
       alt=""
       style="left: <?= (int) ($item['pos_x'] ?? 50) ?>%; top: <?= (int) ($item['pos_y'] ?? 50) ?>%; width: <?= (int) ($item['escala'] ?? 45) ?>%">
</div>
