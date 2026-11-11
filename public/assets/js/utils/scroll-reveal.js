/**
 * Revelação de elementos conforme a rolagem (data-reveal).
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Sem JavaScript (ou sem IntersectionObserver), os elementos ficam visíveis.
 */

import { queryAll } from './dom.js';

export function initScrollReveal() {
  const elementos = queryAll('[data-reveal]');

  if (elementos.length === 0) {
    return;
  }

  if (!('IntersectionObserver' in window)) {
    elementos.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  // Aplica o atraso opcional data-reveal-delay como variável CSS.
  elementos.forEach((el) => {
    const atraso = el.getAttribute('data-reveal-delay');

    if (atraso) {
      el.style.setProperty('--reveal-delay', `${atraso}ms`);
    }
  });

  const observador = new IntersectionObserver(
    (entradas) => {
      for (const entrada of entradas) {
        if (entrada.isIntersecting) {
          entrada.target.classList.add('is-visible');
          observador.unobserve(entrada.target);
        }
      }
    },
    { threshold: 0.12, rootMargin: '0px 0px -48px 0px' }
  );

  elementos.forEach((el) => observador.observe(el));
}
