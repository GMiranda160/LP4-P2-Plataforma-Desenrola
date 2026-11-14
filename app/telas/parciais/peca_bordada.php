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
 *   rotacao  giro do bordado em graus, -180 a 180 (opcional, padrão 0)
 *   foto_peca   caminho no Storage da foto da peça (peça base ou foto da pessoa), opcional.
 *               Com foto, ela substitui o desenho do mockup.
 *   foto_tingir verdadeiro para peça base (a cor_peca tinge a foto); falso para foto da pessoa
 *   largura  largura da matriz em pontos (opcional, usada para centralizar sem transform)
 *   altura   altura da matriz em pontos (opcional)
 *
 * A peça é 4:5. A centralização é calculada em PHP: metade da largura da matriz (em % da
 * largura da peça) e metade da altura da matriz (em % da altura da peça), sem usar transform.
 * Se os dados da matriz não vierem, supõe matriz quadrada. A rotação é um rotate em volta
 * do centro do próprio bordado, então não muda a centralização.
 */

$fotoPeca = (string) ($item['foto_peca'] ?? '');
$fotoUrl = $fotoPeca !== '' ? StorageServico::urlPublica($fotoPeca) : '';
$corPeca = (string) ($item['cor_peca'] ?? '#2e2321');
// Mesma regra do montador: só peça base é tingida, e branco significa "cor original da foto".
$tingir = $fotoUrl !== '' && filter_var($item['foto_tingir'] ?? true, FILTER_VALIDATE_BOOLEAN)
    && strtolower($corPeca) !== '#ffffff';

$desenho = '';
if ($fotoUrl === '') {
    $mockup = basename((string) ($item['mockup'] ?? 'bastidor.svg'));
    $caminho = RAIZ . '/public/assets/img/mockups/' . $mockup;
    $desenho = is_file($caminho) ? (string) file_get_contents($caminho) : '';
}

$posX = (float) ($item['pos_x'] ?? 50);
$posY = (float) ($item['pos_y'] ?? 50);
$escala = (float) ($item['escala'] ?? 45);
$largura = (int) ($item['largura'] ?? 0);
$altura = (int) ($item['altura'] ?? 0);
$rotacao = max(-180, min(180, (int) ($item['rotacao'] ?? 0)));

// A peça tem proporção 4:5: a altura do bordado (em % da altura da peça) é
// escala x (altura/largura da matriz) x (4/5).
$alturaFrac = ($largura >= 8 && $altura >= 8)
    ? ($escala * ($altura / $largura) * (4 / 5))
    : $escala; // sem dados da matriz, supõe matriz quadrada

$left = $posX - $escala / 2;
$top = $posY - $alturaFrac / 2;
$estilo = sprintf('left: %s%%; top: %s%%; width: %s%%',
    rtrim(rtrim(number_format($left, 2, '.', ''), '0'), '.'),
    rtrim(rtrim(number_format($top, 2, '.', ''), '0'), '.'),
    rtrim(rtrim(number_format($escala, 2, '.', ''), '0'), '.')
);
if ($rotacao !== 0) {
    $estilo .= sprintf('; transform: rotate(%ddeg)', $rotacao);
}
?>
<div class="peca" style="color: <?= e($corPeca) ?>">
  <?php if ($fotoUrl !== ''): ?>
    <div class="peca__mockup" aria-hidden="true">
      <img class="peca__foto" src="<?= e($fotoUrl) ?>" alt="" loading="lazy">
    </div>
    <?php if ($tingir): ?>
      <div class="peca__tinta" aria-hidden="true"
           style="background-color: <?= e($corPeca) ?>; -webkit-mask-image: url('<?= e($fotoUrl) ?>'); mask-image: url('<?= e($fotoUrl) ?>')"></div>
    <?php endif; ?>
  <?php else: ?>
    <div class="peca__mockup" aria-hidden="true"><?= $desenho ?></div>
  <?php endif; ?>
  <img class="peca__matriz"
       src="<?= e(svg_data_uri((string) ($item['svg'] ?? ''))) ?>"
       alt=""
       style="<?= e($estilo) ?>">
</div>
