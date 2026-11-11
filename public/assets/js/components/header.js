/**
 * Header - comportamento do menu móvel.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * O cabeçalho agora é HTML no servidor; este módulo só abre/fecha o menu.
 */

import { query, on } from '../utils/dom.js';

export function initHeader() {
  const toggle = query('.nav-toggle');
  const nav = query('#primary-nav');

  if (!toggle || !nav) {
    return;
  }

  const definirAberto = (aberto) => {
    nav.classList.toggle('is-open', aberto);
    toggle.setAttribute('aria-expanded', String(aberto));
    toggle.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
  };

  on(toggle, 'click', () => {
    definirAberto(!nav.classList.contains('is-open'));
  });

  // Fecha ao clicar em qualquer link do menu.
  on(nav, 'click', (evento) => {
    if (evento.target.closest('a')) {
      definirAberto(false);
    }
  });

  // Fecha com Escape.
  on(document, 'keydown', (evento) => {
    if (evento.key === 'Escape') {
      definirAberto(false);
    }
  });

  // Fecha ao voltar para desktop ou ao navegar por âncoras.
  const desktop = window.matchMedia('(min-width: 1101px)');
  on(desktop, 'change', (evento) => {
    if (evento.matches) {
      definirAberto(false);
    }
  });

  on(window, 'hashchange', () => definirAberto(false));
}
