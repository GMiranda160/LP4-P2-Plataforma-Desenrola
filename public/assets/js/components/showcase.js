/**
 * Vitrine em loop da home.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Loop contínuo com requestAnimationFrame. Pausa no hover/foco e no botão,
 * respeita prefers-reduced-motion e telas pequenas (aí manda o scroll nativo).
 */

import { query, queryAll, on } from '../utils/dom.js';

export function initShowcase(root) {
  const viewport = query('.showcase__viewport', root);
  const track = query('.showcase__track', root);
  const botaoAnterior = query('[data-showcase-prev]', root);
  const botaoProximo = query('[data-showcase-next]', root);
  const toggle = query('[data-showcase-toggle]', root);

  if (!viewport || !track || track.children.length < 2) {
    return;
  }

  const grupos = queryAll('.showcase__group', track);
  const gap = 24; // mesmo valor de --showcase-gap
  const larguraGrupo = () => grupos[0].scrollWidth + gap;

  const reduzMovimento = window.matchMedia('(prefers-reduced-motion: reduce)');
  const telaPequena = window.matchMedia('(max-width: 900px)');

  let offset = 0;
  let velocidade = 0;
  let animando = false;
  let pausado = false;
  let estatico = false;

  const velocidadeBase = 0.8;

  // Poucos itens: se o grupo inteiro cabe na vitrine, não há o que rolar. A vitrine fica
  // parada e centralizada, sem a cópia do loop, sem setas e sem o botão de pausa.
  const cabeInteiro = () => grupos[0].scrollWidth - gap <= viewport.clientWidth;
  const podeAnimar = () => !estatico && !reducaoAtiva() && !telaPequena.matches;

  const passo = () => {
    offset += velocidade;

    const largura = larguraGrupo();

    if (offset >= largura) {
      offset -= largura;
    }

    track.style.transform = `translate3d(${-offset}px, 0, 0)`;
    animando = requestAnimationFrame(passo);
  };

  const iniciar = () => {
    if (!podeAnimar()) {
      return;
    }

    velocidade = pausado ? 0 : velocidadeBase;

    if (!animando) {
      animando = requestAnimationFrame(passo);
    }
  };

  const parar = () => {
    if (animando) {
      cancelAnimationFrame(animando);
      animando = false;
    }
  };

  const reducaoAtiva = () => reduzMovimento.matches;

  const atualizarToggle = () => {
    if (!toggle) {
      return;
    }

    toggle.setAttribute('aria-pressed', String(pausado));
    const rotulo = toggle.querySelector('span:last-child');

    if (rotulo) {
      rotulo.textContent = pausado ? 'Retomar' : 'Pausar';
    }
  };

  on(viewport, 'mouseenter', () => {
    if (!pausado) {
      velocidade = 0;
    }
  });

  on(viewport, 'mouseleave', () => {
    if (!pausado) {
      velocidade = velocidadeBase;
    }
  });

  const deslocar = (direcao) => {
    const largura = larguraGrupo();
    offset += direcao * Math.max(240, largura * 0.25);

    if (offset < 0) {
      offset += largura;
    } else if (offset >= largura) {
      offset -= largura;
    }

    track.style.transform = `translate3d(${-offset}px, 0, 0)`;
  };

  on(botaoAnterior, 'click', () => deslocar(-1));
  on(botaoProximo, 'click', () => deslocar(1));

  on(toggle, 'click', () => {
    pausado = !pausado;
    velocidade = pausado ? 0 : velocidadeBase;
    atualizarToggle();

    if (pausado) {
      parar();
    } else {
      animando = false;
      iniciar();
    }
  });

  const aoMudarMidia = () => {
    if (!podeAnimar()) {
      parar();
      offset = 0;
      track.style.transform = '';
      return;
    }

    animando = false;
    iniciar();
  };

  const verificarTamanho = () => {
    // A cópia do loop fica escondida no modo parado, então mede sempre pelo primeiro grupo.
    const cabe = cabeInteiro();

    if (cabe === estatico) {
      return;
    }

    estatico = cabe;
    root.classList.toggle('showcase--estatico', estatico);
    aoMudarMidia();
  };

  on(reduzMovimento, 'change', aoMudarMidia);
  on(telaPequena, 'change', aoMudarMidia);

  if ('ResizeObserver' in window) {
    new ResizeObserver(verificarTamanho).observe(viewport);
  } else {
    on(window, 'resize', verificarTamanho);
  }

  atualizarToggle();
  verificarTamanho();
  iniciar();
}
