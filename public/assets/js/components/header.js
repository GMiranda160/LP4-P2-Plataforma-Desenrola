/**
 * Header - comportamento do menu móvel e do menu do usuário.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * O cabeçalho agora é HTML no servidor; este módulo só abre e fecha os dois menus.
 *
 * O menu do usuário é o botão com a inicial do nome. Ele abre o painel com
 * Meus bordados, Painel e Sair. Fecha ao clicar fora, ao escolher um item
 * e com a tecla Escape, que devolve o foco para o botão.
 */

import { query, on } from '../utils/dom.js';

/* Um menu fecha o outro: os dois nunca ficam abertos ao mesmo tempo. */
let fecharMenuUsuario = () => {};
let fecharMenuMovel = () => {};

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

    if (aberto) {
      fecharMenuUsuario();
    }
  };

  fecharMenuMovel = () => definirAberto(false);

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

/** Abre e fecha o painel do menu do usuário. */
export function initMenuUsuario() {
  const botao = query('.user-menu__avatar');
  const painel = query('#menu-usuario');

  if (!botao || !painel) {
    return;
  }

  const aberto = () => botao.getAttribute('aria-expanded') === 'true';

  const definirAberto = (estado) => {
    painel.hidden = !estado;
    botao.setAttribute('aria-expanded', String(estado));

    if (estado) {
      fecharMenuMovel();
    }
  };

  fecharMenuUsuario = () => definirAberto(false);

  on(botao, 'click', () => definirAberto(!aberto()));

  // Clique fora do painel fecha o menu.
  on(document, 'click', (evento) => {
    if (!aberto() || !(evento.target instanceof Element)) {
      return;
    }

    if (!evento.target.closest('.user-menu')) {
      definirAberto(false);
    }
  });

  // Escolher um item fecha o menu.
  on(painel, 'click', (evento) => {
    if (evento.target instanceof Element && evento.target.closest('a, button')) {
      definirAberto(false);
    }
  });

  // Escape fecha o menu e devolve o foco para o botão.
  on(document, 'keydown', (evento) => {
    if (evento.key !== 'Escape' || !aberto()) {
      return;
    }

    definirAberto(false);
    botao.focus();
  });
}
