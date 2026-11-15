/**
 * Guia - popups de arquitetura do site.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 *
 * Abre e fecha os guias marcados com data-guia-abrir (o valor é o id do
 * popup). Fecha por ESC, clique fora do dialog ou no botão de fechar,
 * trava a rolagem do fundo e devolve o foco para quem abriu.
 *
 * Dentro de um guia com data-guia-deck, as setas do teclado, os botões
 * Anterior/Próximo, os pontos e o contador navegam entre os slides.
 */

export function initGuia() {
  let focoAnterior = null;

  const abrir = (guia, botao) => {
    focoAnterior = botao ?? document.activeElement;
    guia.hidden = false;

    // Um frame com o display aplicado, para o fade e o slide entrarem em cena.
    requestAnimationFrame(() => guia.classList.add('is-open'));

    document.documentElement.classList.add('guia-travado');
    guia.querySelector('.guia__fechar')?.focus();
  };

  const fechar = (guia) => {
    if (!guia || guia.hidden) {
      return;
    }

    guia.classList.remove('is-open');
    document.documentElement.classList.remove('guia-travado');

    const esconder = () => {
      guia.hidden = true;
      guia.removeEventListener('transitionend', esconder);
    };

    guia.addEventListener('transitionend', esconder);
    // Sem transição (reduced motion) o transitionend não dispara.
    window.setTimeout(esconder, 400);

    if (focoAnterior instanceof HTMLElement) {
      focoAnterior.focus();
    }

    focoAnterior = null;
  };

  document.querySelectorAll('[data-guia-abrir]').forEach((botao) => {
    botao.addEventListener('click', () => {
      const guia = document.getElementById(botao.dataset.guiaAbrir ?? '');

      if (guia) {
        abrir(guia, botao);
      }
    });
  });

  document.addEventListener('click', (evento) => {
    // Clique fora do dialog (no próprio overlay) também fecha.
    if (evento.target instanceof Element && evento.target.classList.contains('guia')) {
      fechar(evento.target);
      return;
    }

    const alvo = evento.target.closest('[data-guia-fechar]');

    if (alvo) {
      fechar(alvo.closest('.guia'));
    }
  });

  /* ------------------------------------------------------------------
   * Deck de slides: setas do teclado, botões, pontos, contador e fio
   * de progresso. Um guia por vez pode estar aberto; as setas só valem
   * para o deck aberto.
   * ------------------------------------------------------------------ */
  const decks = new Map();

  document.querySelectorAll('[data-guia-deck]').forEach((guia) => {
    const slides = [...guia.querySelectorAll('.guia__slide')];
    const pontos = [...guia.querySelectorAll('[data-guia-ponto]')];
    const anterior = guia.querySelector('[data-guia-nav="-1"]');
    const proximo = guia.querySelector('[data-guia-nav="1"]');
    const contador = guia.querySelector('[data-guia-contador]');
    const fio = guia.querySelector('[data-guia-progresso]');

    if (slides.length === 0) {
      return;
    }

    const estado = { indice: 0, total: slides.length };

    const irPara = (destino) => {
      estado.indice = Math.min(Math.max(destino, 0), estado.total - 1);

      slides.forEach((slide, i) => {
        slide.classList.toggle('is-active', i === estado.indice);
      });

      pontos.forEach((ponto, i) => {
        ponto.classList.toggle('is-active', i === estado.indice);
        ponto.setAttribute('aria-selected', i === estado.indice ? 'true' : 'false');
      });

      if (contador) {
        contador.textContent = `${estado.indice + 1} / ${estado.total}`;
      }

      if (fio) {
        fio.style.width = `${(estado.indice / (estado.total - 1)) * 100}%`;
      }

      if (anterior) {
        anterior.disabled = estado.indice === 0;
      }

      if (proximo) {
        proximo.disabled = estado.indice === estado.total - 1;
      }
    };

    guia.querySelectorAll('[data-guia-nav]').forEach((botao) => {
      botao.addEventListener('click', () => {
        irPara(estado.indice + Number(botao.dataset.guiaNav));
      });
    });

    pontos.forEach((ponto) => {
      ponto.addEventListener('click', () => {
        irPara(Number(ponto.dataset.guiaPonto));
      });
    });

    decks.set(guia, { irPara, estado });
    irPara(0);
  });

  document.addEventListener('keydown', (evento) => {
    if (evento.key === 'Escape') {
      fechar(document.querySelector('.guia.is-open'));
      return;
    }

    if (evento.key !== 'ArrowLeft' && evento.key !== 'ArrowRight') {
      return;
    }

    const aberto = document.querySelector('.guia.is-open');
    const deck = aberto ? decks.get(aberto) : null;

    if (deck) {
      evento.preventDefault();
      deck.irPara(deck.estado.indice + (evento.key === 'ArrowRight' ? 1 : -1));
    }
  });
}
