<?php
/**
 * Parcial: menu do usuário (botão com a inicial e painel).
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Recebe: $naGerencia (bool). Define os itens do painel:
 *   false (site público): Meus bordados, Painel (só gerente) e Sair.
 *   true (área da gerência): Meus bordados, Início e Sair.
 * Uso: Visao::parcial('menu_usuario', ['naGerencia' => true]).
 *
 * O dropdown abre e fecha por js/components/header.js.
 */

$usuarioAtual = Auth::usuario();

if ($usuarioAtual === null) {
    return;
}

// Inicial e primeiro nome para o botão do usuário. Com mb_, nomes com acento saem certos.
$nome = (string) $usuarioAtual['nome'];
$inicial = mb_strtoupper(mb_substr($nome, 0, 1));
$primeiroNome = explode(' ', trim($nome))[0] ?? '';
?>
<div class="user-menu">
  <button class="user-menu__avatar" type="button" aria-expanded="false" aria-controls="menu-usuario">
    <span class="user-menu__letra" aria-hidden="true"><?= e($inicial) ?></span>
    <span class="user-menu__primeiro" aria-hidden="true"><?= e($primeiroNome) ?></span>
    <svg class="user-menu__seta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
    <span class="visually-hidden">Minha conta: <?= e($nome) ?></span>
  </button>

  <div class="user-menu__painel" id="menu-usuario" hidden>
    <p class="user-menu__nome"><?= e($nome) ?></p>

    <ul class="user-menu__lista" role="list">
      <?php if (!empty($naGerencia)): ?>
        <li><a class="user-menu__item" href="/">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          Início
        </a></li>
      <?php elseif (Auth::eGerente()): ?>
        <li><a class="user-menu__item" href="/gerente">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          Painel
        </a></li>
      <?php endif; ?>
      <li><a class="user-menu__item" href="/meus-bordados">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg>
        Meus bordados
      </a></li>
      <li>
        <form method="post" action="/sair">
          <?= Csrf::campo() ?>
          <button class="user-menu__item" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Sair
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>
