<?php
/**
 * Tela: detalhe do bordado
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /hub/bordado?id=
 * Recebe: $titulo, $bordado, $comentarios, $podeExcluir
 * $bordado tem os mesmos campos do post do feed, mais tipo.
 * Responsabilidade: o post do bordado aberto, no mesmo componente do feed (_card.php) em modo detalhe:
 *                   peça maior, descrição inteira, todos os comentários e o tutorial com IA já aberto.
 * Veja INSTRUCOES_EDUARDO.md, seção 5.
 */

$b = $bordado;
$b['podeExcluir'] = $podeExcluir;
$modoPost = 'detalhe';
$comentariosPost = $comentarios;
?>
<div class="container hub-page hub-page--detalhe">
  <a class="link-button hub-voltar" href="/hub">Voltar ao Hub</a>
  <?php require RAIZ . '/app/telas/hub/_card.php'; ?>
</div>
