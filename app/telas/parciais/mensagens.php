<?php
/**
 * Parcial: mensagens flash (sucesso, erro e aviso) como toasts temporários.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Lê as mensagens da sessão e as exibe uma única vez, no canto inferior direito.
 * Sucesso e aviso somem sozinhos; erro fica mais tempo. O botão fecha na hora.
 * O init fica em assets/js/components/toast.js.
 * Tipos conhecidos: sucesso (verde), erro (vermelho) e aviso (neutro).
 */

$mensagens = Sessao::tirarFlash();

if ($mensagens === []) {
    return;
}

$classes = [
    'sucesso' => 'alert--sucesso',
    'erro' => 'alert--erro',
    'aviso' => 'alert--aviso',
];

// Ícones em linha: círculo com check, triângulo de alerta e círculo de informação.
$icones = [
    'sucesso' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
    'erro' => '<path d="M12 3 2 20h20L12 3Z"/><path d="M12 10v4"/><path d="M12 17h.01"/>',
    'aviso' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/>',
];
?>
<div class="flash" role="status" aria-live="polite">
  <?php foreach ($mensagens as $mensagem): ?>
    <?php
    $tipo = (string) ($mensagem['tipo'] ?? 'aviso');
    $classe = $classes[$tipo] ?? 'alert--aviso';
    $icone = $icones[$tipo] ?? $icones['aviso'];
    ?>
    <div class="alert <?= e($classe) ?>"<?= $tipo === 'erro' ? ' role="alert"' : '' ?>>
      <span class="alert__icone" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $icone ?></svg>
      </span>
      <p class="alert__texto"><?= e((string) ($mensagem['mensagem'] ?? '')) ?></p>
      <button type="button" class="alert__fechar" data-toast-fechar aria-label="Fechar aviso">&times;</button>
    </div>
  <?php endforeach; ?>
</div>
