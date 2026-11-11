<?php
/**
 * Tela: erro 403
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Rota: acesso indevido (POST sem CSRF válido, ou área da gerente sem perfil).
 * Recebe: $titulo (definido na tela), $conteudo (montado no layout).
 */

$titulo = 'Acesso negado | Desenrola';
?>
<section class="section">
  <div class="container section__head">
    <p class="eyebrow">Erro 403</p>
    <h1>Você não tem permissão</h1>
    <p class="lead">Esta área é restrita ou a operação não pôde ser validada.</p>
    <p><a class="btn btn--primary" href="/">Voltar para o início</a></p>
  </div>
</section>
