/**
 * Arquivo: enquadrador.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: popup da foto da peça no montador. A pessoa escolhe a imagem (do computador,
 * arrastando, colando com Ctrl+V ou por um link da web) e ajusta o enquadramento numa moldura 4:5,
 * arrastando e dando zoom. A foto sai já em 4:5, então preenche a peça inteira: sem faixas nas
 * laterais e com o bordado na mesma posição na prévia, no PNG baixado e nos cards.
 *
 * O link da web é baixado pelo servidor (/montador/imagem), porque o navegador não pode ler
 * a imagem de outro site no canvas.
 */

import { query, on } from '../utils/dom.js';

// Saída em 4:5, a proporção da peça (.peca e .montador__area).
const SAIDA_LARGURA = 1280;
const SAIDA_ALTURA = 1600;
const TIPOS = ['image/png', 'image/jpeg', 'image/webp'];

/**
 * Dois modos de uso:
 *  enquadrar: foto da peça, recortada em 4:5 e devolvida como JPEG em data URL.
 *  original: imagem da matriz, devolvida crua (sem moldura, sem JPEG), para o conversor.
 */
const MODOS = {
  enquadrar: {
    titulo: 'Foto da sua peça',
    origem: 'Envie uma foto da peça, de frente e com boa luz.',
    ajuste: 'Arraste para enquadrar a peça e use o zoom para aproximar ou afastar.',
    limite: 10 * 1024 * 1024,
    dica: 'ou arraste a imagem para cá · PNG, JPEG ou WebP até 10 MB',
    usar: 'Usar esta foto',
  },
  original: {
    titulo: 'Imagem para virar matriz',
    origem: 'Escolha o desenho que vai virar matriz de pontos.',
    ajuste: '',
    limite: 5 * 1024 * 1024,
    dica: 'ou arraste a imagem para cá · PNG, JPEG ou WebP até 5 MB',
    usar: 'Converter esta imagem',
  },
};
const ZOOM_MAXIMO = 4; // 4x além de preencher a moldura
const PASSO_TECLADO = 12; // px por seta

/**
 * Liga o popup. No modo padrão, aoConfirmar recebe o data URL JPEG 4:5 da foto enquadrada.
 * No modo 'original', o Blob cru vai para o aoUsar informado na abertura.
 * Devolve { abrir }.
 */
export function criarEnquadrador(dialogo, { aoConfirmar } = {}) {
  if (!dialogo) return { abrir() {} };

  const origem = query('[data-enq-origem]', dialogo);
  const ajuste = query('[data-enq-ajuste]', dialogo);
  const arquivo = query('[data-enq-arquivo]', dialogo);
  const soltar = query('[data-enq-soltar]', dialogo);
  const campoLink = query('[data-enq-url]', dialogo);
  const botaoCarregar = query('[data-enq-carregar]', dialogo);
  const erro = query('[data-enq-erro]', dialogo);
  const moldura = query('[data-enq-moldura]', dialogo);
  const imagem = query('[data-enq-img]', dialogo);
  const zoom = query('[data-enq-zoom]', dialogo);
  const subtitulo = query('[data-enq-sub]', dialogo);
  const titulo = query('.enquadrador__titulo', dialogo);
  const botaoUsar = query('[data-enq-usar]', dialogo);
  const dicaArquivo = query('.enquadrador__dica', soltar);

  // Modo da abertura atual e para quem vai a imagem no modo original.
  let modoAtual = 'enquadrar';
  let aoUsarAtual = null;
  let blobAtual = null;

  function config() {
    return MODOS[modoAtual] ?? MODOS.enquadrar;
  }

  // Estado do enquadramento, independente do tamanho da tela:
  // z = 1 preenche a moldura (cover); cx, cy = ponto da imagem que fica no centro da moldura.
  let fonte = null; // HTMLImageElement carregada
  let urlTemporaria = null;
  let corFundo = '#ffffff';
  let z = 1;
  let cx = 0;
  let cy = 0;
  let arraste = null;

  // Medidas da moldura e da imagem na tela.
  function medidas() {
    const W = moldura.clientWidth;
    const H = moldura.clientHeight;
    const larg = fonte.naturalWidth;
    const alt = fonte.naturalHeight;
    const cobrir = Math.max(W / larg, H / alt);
    const caber = Math.min(W / larg, H / alt);

    return { W, H, larg, alt, cobrir, zMin: caber / cobrir };
  }

  // Posição da imagem na moldura, já presa nos limites. Atualiza cx e cy para o estado preso.
  function posicao() {
    const { W, H, larg, alt, cobrir, zMin } = medidas();

    z = Math.min(ZOOM_MAXIMO, Math.max(zMin, z));

    const s = cobrir * z;
    const dw = larg * s;
    const dh = alt * s;
    let ox = W / 2 - cx * s;
    let oy = H / 2 - cy * s;

    // Maior que a moldura: não deixa sobrar espaço. Menor: fica centralizada.
    ox = dw >= W ? Math.min(0, Math.max(W - dw, ox)) : (W - dw) / 2;
    oy = dh >= H ? Math.min(0, Math.max(H - dh, oy)) : (H - dh) / 2;

    cx = (W / 2 - ox) / s;
    cy = (H / 2 - oy) / s;

    return { W, H, s, dw, dh, ox, oy, zMin };
  }

  function desenhar() {
    if (!fonte || !moldura.clientWidth) return;

    const { dw, dh, ox, oy, zMin } = posicao();

    imagem.style.width = `${dw}px`;
    imagem.style.height = `${dh}px`;
    imagem.style.transform = `translate(${ox}px, ${oy}px)`;

    // Controle de zoom em escala logarítmica: o meio do curso fica perto de "preencher".
    if (zoom && document.activeElement !== zoom) {
      zoom.value = String(Math.round((Math.log(z / zMin) / Math.log(ZOOM_MAXIMO / zMin)) * 1000));
    }
  }

  function zoomPara(novo) {
    z = novo;
    desenhar();
  }

  function mostrarErro(mensagem) {
    if (!erro) return;
    erro.textContent = mensagem;
    erro.hidden = !mensagem;
  }

  function mostrarEtapa(etapa) {
    origem.hidden = etapa !== 'origem';
    ajuste.hidden = etapa !== 'ajuste';
    dialogo.dataset.etapa = etapa;
    if (subtitulo?.dataset[etapa]) subtitulo.textContent = subtitulo.dataset[etapa];

    if (etapa === 'ajuste') {
      requestAnimationFrame(() => {
        desenhar();
        moldura.focus();
      });
    } else {
      (campoLink ?? arquivo)?.focus();
    }
  }

  /** Cor média da borda da imagem: preenche o que sobrar quando a pessoa afasta o zoom. */
  function corDaBorda(img) {
    const lado = 48;
    const tela = document.createElement('canvas');
    tela.width = lado;
    tela.height = lado;
    const ctx = tela.getContext('2d', { willReadFrequently: true });
    ctx.fillStyle = '#ffffff'; // transparência vira branco, como no JPEG final
    ctx.fillRect(0, 0, lado, lado);
    ctx.drawImage(img, 0, 0, lado, lado);
    const { data } = ctx.getImageData(0, 0, lado, lado);
    const soma = [0, 0, 0];
    let n = 0;

    for (let y = 0; y < lado; y++) {
      for (let x = 0; x < lado; x++) {
        if (x > 1 && x < lado - 2 && y > 1 && y < lado - 2) continue;
        const i = (y * lado + x) * 4;
        soma[0] += data[i];
        soma[1] += data[i + 1];
        soma[2] += data[i + 2];
        n++;
      }
    }

    return '#' + soma.map((v) => Math.round(v / n).toString(16).padStart(2, '0')).join('');
  }

  async function carregarSrc(src) {
    const img = new Image();
    img.src = src;

    try {
      await img.decode();
    } catch {
      throw new Error('Não consegui abrir essa imagem. Tente outra.');
    }

    if (urlTemporaria && urlTemporaria !== src) URL.revokeObjectURL(urlTemporaria);
    urlTemporaria = src.startsWith('blob:') ? src : null;

    fonte = img;
    corFundo = corDaBorda(img);
    imagem.src = src;
    moldura.style.backgroundColor = corFundo;
    z = 1;
    cx = img.naturalWidth / 2;
    cy = img.naturalHeight / 2;
    mostrarErro('');

    // Modo original: entrega o arquivo inteiro, sem moldura e sem JPEG.
    if (modoAtual === 'original') {
      const entregar = aoUsarAtual;
      const blob = blobAtual;
      dialogo.close();

      if (blob && typeof entregar === 'function') {
        entregar(blob);
      }

      return;
    }

    mostrarEtapa('ajuste');
  }

  async function carregarArquivo(file) {
    if (!file) return;

    if (/heic|heif/i.test(file.type) || /\.(heic|heif)$/i.test(file.name ?? '')) {
      mostrarErro('Foto em HEIC (padrão do iPhone) não abre no navegador. No app Fotos, exporte como JPEG e envie de novo.');
      return;
    }

    const limite = config().limite;

    if (!TIPOS.includes(file.type) || file.size > limite) {
      mostrarErro(`Escolha uma imagem PNG, JPEG ou WebP de até ${limite / 1024 / 1024} MB.`);
      return;
    }

    blobAtual = file;

    try {
      await carregarSrc(URL.createObjectURL(file));
    } catch (e) {
      mostrarErro(e.message);
    }
  }

  async function carregarLink(link) {
    const url = String(link ?? '').trim();

    if (!/^https?:\/\//i.test(url)) {
      mostrarErro('Cole um link que comece com http:// ou https://.');
      return;
    }

    botaoCarregar && (botaoCarregar.disabled = true);
    botaoCarregar && (botaoCarregar.textContent = 'Carregando...');
    mostrarErro('');

    try {
      const resposta = await fetch(`/montador/imagem?url=${encodeURIComponent(url)}`, { credentials: 'same-origin' });

      if (!resposta.ok) {
        const corpo = await resposta.json().catch(() => null);
        throw new Error(corpo?.erro || 'Não consegui baixar a imagem desse link.');
      }

      const blob = await resposta.blob();
      const limite = config().limite;

      if (blob.size > limite) {
        throw new Error(`A imagem desse link tem mais de ${limite / 1024 / 1024} MB. Escolha uma menor.`);
      }

      blobAtual = blob;
      await carregarSrc(URL.createObjectURL(blob));
    } catch (e) {
      mostrarErro(e.message || 'Não consegui baixar a imagem desse link.');
    } finally {
      if (botaoCarregar) {
        botaoCarregar.disabled = false;
        botaoCarregar.textContent = 'Carregar';
      }
    }
  }

  /** Recorta a moldura em 1280x1600 e devolve um JPEG. */
  function gerar() {
    const { W, dw, dh, ox, oy } = posicao();
    const k = SAIDA_LARGURA / W;
    const tela = document.createElement('canvas');
    tela.width = SAIDA_LARGURA;
    tela.height = SAIDA_ALTURA;
    const ctx = tela.getContext('2d');
    ctx.fillStyle = corFundo;
    ctx.fillRect(0, 0, SAIDA_LARGURA, SAIDA_ALTURA);
    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';
    ctx.drawImage(fonte, ox * k, oy * k, dw * k, dh * k);

    return tela.toDataURL('image/jpeg', 0.88);
  }

  // Origem: computador, arrastar e soltar, colar e link.
  on(arquivo, 'change', () => {
    carregarArquivo(arquivo.files?.[0]);
    arquivo.value = '';
  });

  on(soltar, 'dragover', (e) => {
    e.preventDefault();
    soltar.classList.add('is-sobre');
  });
  on(soltar, 'dragleave', () => soltar.classList.remove('is-sobre'));
  on(soltar, 'drop', (e) => {
    e.preventDefault();
    soltar.classList.remove('is-sobre');
    const file = e.dataTransfer?.files?.[0];
    const texto = e.dataTransfer?.getData('text/uri-list') || e.dataTransfer?.getData('text/plain');

    if (file) carregarArquivo(file);
    else if (texto) carregarLink(texto.split('\n')[0]);
  });

  on(botaoCarregar, 'click', () => carregarLink(campoLink?.value));

  // Enter no campo do link carrega a imagem, sem enviar o formulário do montador.
  on(campoLink, 'keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      carregarLink(campoLink.value);
    }
  });

  on(dialogo, 'paste', (e) => {
    if (e.target === campoLink) return; // colar texto no campo do link funciona normal

    const itens = Array.from(e.clipboardData?.items ?? []);
    const itemImagem = itens.find((item) => item.type.startsWith('image/'));

    if (itemImagem) {
      e.preventDefault();
      carregarArquivo(itemImagem.getAsFile());
      return;
    }

    const texto = e.clipboardData?.getData('text/plain')?.trim();

    if (texto && /^https?:\/\//i.test(texto)) {
      e.preventDefault();
      if (campoLink) campoLink.value = texto;
      carregarLink(texto);
    }
  });

  // Ajuste: arrastar, roda do mouse, controle de zoom e teclado.
  on(moldura, 'pointerdown', (e) => {
    if (!fonte) return;
    moldura.setPointerCapture(e.pointerId);
    arraste = { x: e.clientX, y: e.clientY };
    moldura.classList.add('is-arrastando');
  });

  on(moldura, 'pointermove', (e) => {
    if (!arraste) return;
    const { s } = posicao();
    cx -= (e.clientX - arraste.x) / s;
    cy -= (e.clientY - arraste.y) / s;
    arraste = { x: e.clientX, y: e.clientY };
    desenhar();
  });

  const soltarArraste = () => {
    arraste = null;
    moldura.classList.remove('is-arrastando');
  };
  on(moldura, 'pointerup', soltarArraste);
  on(moldura, 'pointercancel', soltarArraste);

  on(moldura, 'wheel', (e) => {
    if (!fonte) return;
    e.preventDefault();
    zoomPara(z * Math.exp(-e.deltaY * 0.0015));
  }, { passive: false });

  on(zoom, 'input', () => {
    if (!fonte) return;
    const { zMin } = medidas();
    zoomPara(zMin * Math.pow(ZOOM_MAXIMO / zMin, Number(zoom.value) / 1000));
  });

  on(moldura, 'keydown', (e) => {
    if (!fonte) return;
    const { s } = posicao();
    const mover = { ArrowLeft: [1, 0], ArrowRight: [-1, 0], ArrowUp: [0, 1], ArrowDown: [0, -1] }[e.key];

    if (mover) {
      e.preventDefault();
      cx -= (mover[0] * PASSO_TECLADO) / s;
      cy -= (mover[1] * PASSO_TECLADO) / s;
      desenhar();
    } else if (e.key === '+' || e.key === '=') {
      e.preventDefault();
      zoomPara(z * 1.1);
    } else if (e.key === '-') {
      e.preventDefault();
      zoomPara(z / 1.1);
    } else if (e.key === '0') {
      e.preventDefault();
      zoomPara(1);
    }
  });

  if ('ResizeObserver' in window) {
    new ResizeObserver(() => desenhar()).observe(moldura);
  }

  // Botões do popup.
  on(query('[data-enq-trocar]', dialogo), 'click', () => mostrarEtapa('origem'));
  on(query('[data-enq-preencher]', dialogo), 'click', () => zoomPara(1));
  on(query('[data-enq-inteira]', dialogo), 'click', () => zoomPara(0));
  on(query('[data-enq-usar]', dialogo), 'click', () => {
    if (!fonte) return;

    try {
      aoConfirmar(gerar());
      dialogo.close();
    } catch {
      mostrarErro('Não consegui preparar essa foto. Tente outra imagem.');
      mostrarEtapa('origem');
    }
  });
  dialogo.querySelectorAll('[data-enq-fechar]').forEach((botao) => on(botao, 'click', () => dialogo.close()));

  // Clique no fundo escurecido fecha o popup.
  on(dialogo, 'click', (e) => {
    if (e.target === dialogo) dialogo.close();
  });

  return {
    /**
     * Abre o popup. Sem opções, é a foto da peça (enquadramento 4:5).
     * No modo 'original', entrega o Blob para aoUsar, sem passar pelo enquadramento.
     */
    abrir({ modo = 'enquadrar', aoUsar = null } = {}) {
      modoAtual = modo in MODOS ? modo : 'enquadrar';
      aoUsarAtual = typeof aoUsar === 'function' ? aoUsar : null;

      const cfg = config();

      // Cada abertura começa do zero: nada de herdar a imagem de um fluxo anterior
      // (a peça não pode nascer com a imagem enviada para a matriz).
      if (urlTemporaria) {
        URL.revokeObjectURL(urlTemporaria);
        urlTemporaria = null;
      }

      fonte = null;
      blobAtual = null;
      corFundo = '#ffffff';
      z = 1;
      cx = 0;
      cy = 0;
      arraste = null;
      if (imagem) imagem.removeAttribute('src');
      if (moldura) moldura.classList.remove('is-arrastando');
      if (campoLink) campoLink.value = '';
      if (arquivo) arquivo.value = '';

      if (titulo) titulo.textContent = cfg.titulo;
      if (botaoUsar) botaoUsar.textContent = cfg.usar;
      if (dicaArquivo) dicaArquivo.textContent = cfg.dica;
      if (subtitulo) {
        subtitulo.dataset.origem = cfg.origem;
        subtitulo.dataset.ajuste = cfg.ajuste;
      }

      mostrarErro('');
      dialogo.showModal();
      mostrarEtapa(modoAtual === 'original' || !fonte ? 'origem' : 'ajuste');
    },
  };
}
