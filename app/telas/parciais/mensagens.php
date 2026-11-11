<?php
/**
 * Parcial: mensagens flash (sucesso, erro e aviso).
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Lê as mensagens da sessão e as exibe uma única vez.
 * Tipos conhecidos: sucesso (verde), erro (vermelho) e aviso (neutro).
 */

$mensagens = Sessao::tirarFlash();

if ($mensagens === []) {
    return;
}

$classes = [
    'sucesso' => 'alert--sucesso',
    'erro' => 'alert--erro',
    'aviso' => 'alert--sucesso',
];
?>
<div class="flash" role="status" aria-live="polite">
  <?php foreach ($mensagens as $mensagem): ?>
    <?php
    $tipo = (string) ($mensagem['tipo'] ?? 'aviso');
    $classe = $classes[$tipo] ?? 'alert--sucesso';
    ?>
    <p class="alert <?= e($classe) ?>"><?= e((string) ($mensagem['mensagem'] ?? '')) ?></p>
  <?php endforeach; ?>
</div>
