/**
 * Arquivo: montador.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: controlar as quatro etapas do montador (matriz, peça, posição e publicar),
 * converter a imagem no navegador via conversor.js e posicionar a matriz na peça com uma caixa de
 * transformação (mover, redimensionar pelos cantos e girar pela alça de cima) com Pointer Events.
 * Veja INSTRUCOES_GUILHERME.md, seções 6 e 7.
 */

import { query, queryAll, on } from '../utils/dom.js';
import { converterImagem, desenharGrade } from './conversor.js';

const PASSOS = ['matriz', 'peca', 'posicao', 'publicar'];
const COR_PADRAO = '#f3ded9';
const POS_PADRAO = 50;
const ESCALA_PADRAO = 45;
const ESCALA_MIN = 10;
const ESCALA_MAX = 90;
const PASSO_TECLADO = 1;
const PASSO_GIRO = 5;
const FOTO_LADO = 1600; // px: lado maior da foto da peça enviada
const IMA_GIRO = 4; // graus: perto de um múltiplo de 45, a rotação gruda nele

export function initMontador(root) {
  const form = query('form', root);
  const dados = lerDados(root);

  if (!form || !dados) return;

  const etapas = queryAll('.montador__etapa', root);
  const passos = queryAll('.stepper__step', root);
  const botoesVoltar = queryAll('[data-voltar]', root);
  const erroBox = query('.montador__erro', root);
  const gradeJson = query('[data-grade-json]', form);
  const posX = query('[data-pos-x]', form);
  const posY = query('[data-pos-y]', form);
  const escala = query('[data-escala]', form);
  const rotacao = query('[data-rotacao]', form);
  const corPeca = query('[data-cor-peca]', form);
  const categoriaId = query('[data-categoria]', form);
  const tinta = query('[data-tinta]', root);
  const amostraCores = queryAll('.swatch[data-cor]', root);
  const titulo = query('[data-titulo]', form);
  const pecaIdCampo = query('[data-peca-id]', form);
  const fotoPecaCampo = query('[data-foto-peca]', form);
  const baixar = query('[data-baixar]', root);

  // Etapa 1: matriz existente ou imagem convertida.
  const radiosMatriz = queryAll('input[name="matriz_id"]', root);
  const arquivo = query('.montador__arquivo', root);
  const largura = query('.montador__largura', root);
  const cores = query('.montador__cores', root);
  const canvas = query('.montador__grade', root);
  const status = query('.montador__status', root);
  const limpar = query('.montador__limpar', root);

  // Etapa 2: peça base com foto real ou foto enviada pela própria pessoa.
  const radiosBase = queryAll('input[name="peca_base"]', root);
  const arquivoPeca = query('.montador__arquivo-peca', root);
  const fotoLinha = query('[data-foto-linha]', root);
  const fotoNota = query('[data-foto-nota]', root);
  const fotoMiniatura = query('[data-foto-miniatura]', root);
  const fotoIcone = query('[data-foto-icone]', root);
  const fotoPreview = query('[data-foto-preview]', root);
  const modoMontagem = String(dados.modo ?? 'hub');

  // Etapa 3: posição.
  const area = query('.montador__area', root);
  const peca = query('.montador__peca', root);
  const mockupBox = query('.montador__mockup', root);
  const caixa = query('[data-caixa]', root);
  const posicaoTexto = query('.montador__posicao', root);
  const resetar = query('[data-resetar]', root);

  let passoAtual = 0;
  let gesto = null; // gesto em andamento: tipo (mover, redimensionar, girar) e valores iniciais
  let gradeConvertida = null;
  let fotoDoUsuario = null;

  function lerDados(raiz) {
    const script = query('#dados-montador', raiz.parentNode ?? document) ?? query('#dados-montador', document);

    if (!script) return null;

    try {
      return JSON.parse(script.textContent || '{}');
    } catch {
      return null;
    }
  }

  function irPara(indice) {
    passoAtual = Math.max(0, Math.min(indice, PASSOS.length - 1));

    etapas.forEach((etapa, i) => etapa.classList.toggle('is-active', i === passoAtual));
    passos.forEach((passo, i) => {
      passo.classList.toggle('is-active', i === passoAtual);
      passo.setAttribute('aria-current', i === passoAtual ? 'step' : 'false');
    });

    esconderErro();
  }

  function mostrarErro(mensagem) {
    if (!erroBox) return;
    erroBox.textContent = mensagem;
    erroBox.hidden = false;
  }

  function esconderErro() {
    if (!erroBox) return;
    erroBox.textContent = '';
    erroBox.hidden = true;
  }

  function matrizEscolhida() {
    return radiosMatriz.find((radio) => radio.checked) ?? null;
  }

  function gradeSelecionada() {
    return gradeJson?.value ? gradeConvertida : null;
  }

  function atualizarPreviewMatriz() {
    if (!peca) return;

    const matriz = matrizEscolhida();

    if (gradeSelecionada()) {
      peca.src = gradeConvertida.preview;
    } else if (matriz) {
      const encontrada = (dados.matrizes ?? []).find((m) => String(m.id) === matriz.value);
      if (encontrada?.svg) peca.src = encontrada.svg;
    } else {
      peca.removeAttribute('src');
    }

    caixa?.toggleAttribute('hidden', !peca.getAttribute('src'));
  }

  function limparImagem({ manterSelecao = false } = {}) {
    gradeConvertida = null;
    if (gradeJson) gradeJson.value = '';
    if (arquivo) arquivo.value = '';
    if (status) status.textContent = manterSelecao ? status.textContent : '';
    canvas?.classList.remove('is-visivel');
    limpar?.classList.remove('is-visivel');
    atualizarPreviewMatriz();
  }

  async function converterSelecao() {
    const arquivoAtual = arquivo?.files?.[0];

    if (!arquivoAtual) return;

    status.textContent = 'Convertendo a imagem...';
    esconderErro();

    try {
      const larguraPx = Number(largura?.value || 256);
      const coresQtd = Number(cores?.value || 8);
      const grade = await converterImagem(arquivoAtual, { largura: larguraPx, cores: coresQtd });

      // Limpa a matriz existente escolhida: agora a imagem é a fonte.
      radiosMatriz.forEach((radio) => { radio.checked = false; });

      gradeConvertida = { ...grade, preview: '' };
      if (gradeJson) gradeJson.value = JSON.stringify({ largura: grade.largura, altura: grade.altura, paleta: grade.paleta, pontos: grade.pontos });

      if (canvas) {
        desenharGrade(canvas, grade);
        gradeConvertida.preview = canvas.toDataURL('image/png');
        canvas.classList.add('is-visivel');
      }

      if (limpar) limpar.classList.add('is-visivel');
      if (status) status.textContent = `Imagem convertida: ${grade.largura}×${grade.altura}, ${grade.paleta.length} cores, ${grade.totalPontos} pontos.`;

      atualizarPreviewMatriz();
    } catch (erro) {
      limparImagem({ manterSelecao: false });
      mostrarErro(erro instanceof Error ? erro.message : 'Não consegui converter a imagem.');
    }
  }

  /** A categoria do bordado vem da peça escolhida (data-categoria-id, cadastrado em /gerente/pecas). */
  function sincronizarCategoria() {
    if (!categoriaId) return;

    const marcada = radiosBase.find((radio) => radio.checked);
    const id = Number(marcada?.dataset.categoriaId || 0);

    if (id > 0) categoriaId.value = String(id);
  }

  function pecaModo() {
    const marcada = radiosBase.find((radio) => radio.checked);

    if (!marcada) return 'base';
    return marcada.value === 'foto' ? 'foto' : 'base';
  }

  function renderizarFundo() {
    if (!mockupBox) return;

    const base = radiosBase.find((radio) => radio.checked);

    if (!base) {
      mockupBox.innerHTML = '';
      atualizarTinta();
      return;
    }

    const foto = base.value === 'foto' ? fotoDoUsuario : base.dataset.foto;

    if (foto) {
      mockupBox.innerHTML = '';
      const img = document.createElement('img');
      img.className = 'montador__mockup-foto';
      img.alt = '';
      img.src = foto;
      img.crossOrigin = 'anonymous';
      mockupBox.appendChild(img);
      atualizarTinta();
      return;
    }

    mockupBox.innerHTML = '';
    atualizarTinta();
  }

  function definirCorSimples(hex) {
    const limpa = String(hex || '').trim().toLowerCase();

    if (!/^#[0-9a-f]{6}$/.test(limpa)) return;

    if (corPeca) corPeca.value = limpa;

    amostraCores.forEach((botao) => {
      const selecionada = botao.dataset.cor === limpa;
      botao.classList.toggle('is-selected', selecionada);
      botao.setAttribute('aria-pressed', selecionada ? 'true' : 'false');
    });

    atualizarTinta();
  }

  function atualizarTinta() {
    if (!tinta) return;

    const modo = pecaModo();
    const cor = (corPeca?.value || '#ffffff').toLowerCase();
    const fundo = mockupBox?.querySelector('.montador__mockup-foto');

    tinta.style.backgroundColor = cor;

    // Máscara com a própria foto: pinta só os pixels da peça, não o fundo.
    const mascara = fundo ? `url("${fundo.src}")` : 'none';
    tinta.style.webkitMaskImage = mascara;
    tinta.style.maskImage = mascara;
    tinta.style.display = !fundo || cor === '#ffffff' || modo === 'foto' ? 'none' : 'block';
  }

  /** Prévia da foto enviada na etapa 2: miniatura no cartão "Minha foto" e imagem maior abaixo do botão. */
  function mostrarFotoEnviada() {
    const temFoto = Boolean(fotoDoUsuario);

    [fotoMiniatura, fotoPreview].forEach((img) => {
      if (!img) return;
      if (temFoto) img.src = fotoDoUsuario;
      else img.removeAttribute('src');
    });

    fotoMiniatura?.toggleAttribute('hidden', !temFoto);
    fotoIcone?.toggleAttribute('hidden', temFoto);
    fotoPreview?.toggleAttribute('hidden', !temFoto || pecaModo() !== 'foto');
  }

  function sincronizarModoPeca() {
    const modo = pecaModo();

    // A paleta multiplica só peças brancas. A foto da pessoa mantém as cores dela.
    amostraCores.forEach((botao) => {
      botao.disabled = modo === 'foto';
      botao.setAttribute('aria-disabled', modo === 'foto' ? 'true' : 'false');
    });

    fotoLinha?.toggleAttribute('hidden', modo !== 'foto');
    fotoNota?.toggleAttribute('hidden', false);
    mostrarFotoEnviada();

    sincronizarCategoria();
    renderizarFundo();
  }

  function carregarImg(src) {
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.crossOrigin = 'anonymous';
      img.onload = () => resolve(img);
      img.onerror = () => reject(new Error('Não consegui carregar a imagem.'));
      img.src = src;
    });
  }

  function limitesContidos(largura, altura, W, H) {
    const esc = Math.min(W / largura, H / altura);
    const dw = largura * esc;
    const dh = altura * esc;

    return { dw, dh, dx: (W - dw) / 2, dy: (H - dh) / 2 };
  }

  function nomeDoArquivo(titulo) {
    const base = String(titulo || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');

    return (base || 'montagem') + '.png';
  }

  async function baixarMontagem() {
    if (!peca?.src) {
      mostrarErro('Escolha a matriz antes de baixar a montagem.');
      return;
    }

    const W = 1080;
    const H = 1350;
    const lienzo = document.createElement('canvas');
    lienzo.width = W;
    lienzo.height = H;
    const ctx = lienzo.getContext('2d');

    ctx.fillStyle = '#f5ede2';
    ctx.fillRect(0, 0, W, H);

    const foto = mockupBox?.querySelector('.montador__mockup-foto');

    if (foto) {
      const img = await carregarImg(foto.src);
      const { dw, dh, dx, dy } = limitesContidos(img.naturalWidth, img.naturalHeight, W, H);
      ctx.drawImage(img, dx, dy, dw, dh);

      const corTint = pecaModo() === 'foto' ? '#ffffff' : (corPeca?.value || '#ffffff').toLowerCase();

      if (corTint !== '#ffffff') {
        // Máscara: silhueta da peça na cor escolhida, multiplicada sobre a foto.
        const silhueta = document.createElement('canvas');
        silhueta.width = W;
        silhueta.height = H;
        const sctx = silhueta.getContext('2d');
        sctx.drawImage(img, dx, dy, dw, dh);
        sctx.globalCompositeOperation = 'source-in';
        sctx.fillStyle = corTint;
        sctx.fillRect(0, 0, W, H);
        ctx.globalCompositeOperation = 'multiply';
        ctx.drawImage(silhueta, 0, 0);
        ctx.globalCompositeOperation = 'source-over';
      }
    }

    try {
      const matriz = await carregarImg(peca.src);
      const w = (Number(escala?.value ?? ESCALA_PADRAO) / 100) * W;
      const h = w * (matriz.height / matriz.width);
      const cx = (Number(posX?.value ?? POS_PADRAO) / 100) * W;
      const cy = (Number(posY?.value ?? POS_PADRAO) / 100) * H;
      const giro = (Number(rotacao?.value ?? 0) * Math.PI) / 180;
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(giro);
      ctx.imageSmoothingEnabled = false;
      ctx.drawImage(matriz, -w / 2, -h / 2, w, h);
      ctx.restore();
    } catch {
      mostrarErro('Não consegui desenhar a matriz na imagem.');
      return;
    }

    const link = document.createElement('a');
    link.download = nomeDoArquivo(titulo?.value);
    link.href = lienzo.toDataURL('image/png');
    link.click();
  }

  function limitar(valor, minimo, maximo) {
    return Math.max(minimo, Math.min(maximo, valor));
  }

  /** Normaliza o ângulo para -180 a 180 e gruda nos múltiplos de 45 (ou vai de 15 em 15 com Shift). */
  function ajustarGiro(graus, comShift = false) {
    let angulo = ((((graus + 180) % 360) + 360) % 360) - 180;
    const passo = comShift ? 15 : 45;
    const alvo = Math.round(angulo / passo) * passo;

    if (comShift || Math.abs(angulo - alvo) <= IMA_GIRO) angulo = alvo;
    if (angulo === -180) angulo = 180;

    return Math.round(angulo);
  }

  /** Atualiza os campos ocultos (com limites) e redesenha a caixa. */
  function definir({ x, y, tamanho, giro } = {}) {
    if (x !== undefined && posX) posX.value = String(Math.round(limitar(x, 0, 100)));
    if (y !== undefined && posY) posY.value = String(Math.round(limitar(y, 0, 100)));
    if (tamanho !== undefined && escala) escala.value = String(Math.round(limitar(tamanho, ESCALA_MIN, ESCALA_MAX)));
    if (giro !== undefined && rotacao) rotacao.value = String(ajustarGiro(giro));
    renderizarPosicao();
  }

  function renderizarPosicao() {
    const x = Number(posX?.value ?? POS_PADRAO);
    const y = Number(posY?.value ?? POS_PADRAO);
    const tamanho = Number(escala?.value ?? ESCALA_PADRAO);
    const giro = Number(rotacao?.value ?? 0);

    if (caixa) {
      caixa.style.left = `${x}%`;
      caixa.style.top = `${y}%`;
      caixa.style.width = `${tamanho}%`;
      caixa.style.setProperty('--giro', `${giro}deg`);
    }

    if (posicaoTexto) posicaoTexto.textContent = `Posição ${x}% × ${y}% · Tamanho ${tamanho} · Giro ${giro}°`;
  }

  /** Centro da caixa em pixels da tela, a partir de pos_x e pos_y. */
  function centroNaTela(retangulo) {
    return {
      cx: retangulo.left + (Number(posX?.value ?? POS_PADRAO) / 100) * retangulo.width,
      cy: retangulo.top + (Number(posY?.value ?? POS_PADRAO) / 100) * retangulo.height,
    };
  }

  function iniciarGesto(evento) {
    if (!area || evento.button > 0) return;

    const retangulo = area.getBoundingClientRect();
    const alca = evento.target.closest?.('[data-alca]');
    const dentroDaCaixa = Boolean(caixa && caixa.contains(evento.target));

    // Toque fora do bordado: o centro pula para lá e o gesto segue como arraste.
    if (!dentroDaCaixa) {
      definir({
        x: ((evento.clientX - retangulo.left) / retangulo.width) * 100,
        y: ((evento.clientY - retangulo.top) / retangulo.height) * 100,
      });
    }

    const { cx, cy } = centroNaTela(retangulo);

    gesto = {
      tipo: alca?.dataset.alca ?? 'mover',
      retangulo,
      cx,
      cy,
      inicioX: evento.clientX,
      inicioY: evento.clientY,
      posX: Number(posX?.value ?? POS_PADRAO),
      posY: Number(posY?.value ?? POS_PADRAO),
      escala: Number(escala?.value ?? ESCALA_PADRAO),
      distancia: Math.max(1, Math.hypot(evento.clientX - cx, evento.clientY - cy)),
    };

    evento.preventDefault();
    area.setPointerCapture(evento.pointerId);
    caixa?.classList.add('is-ativa');
    caixa?.focus({ preventScroll: true });
  }

  function continuarGesto(evento) {
    if (!gesto) return;

    const { retangulo, cx, cy } = gesto;

    if (gesto.tipo === 'mover') {
      definir({
        x: gesto.posX + ((evento.clientX - gesto.inicioX) / retangulo.width) * 100,
        y: gesto.posY + ((evento.clientY - gesto.inicioY) / retangulo.height) * 100,
      });
    } else if (gesto.tipo === 'redimensionar') {
      // A distância do canto até o centro muda na mesma proporção do bordado (mantém o aspecto).
      const distancia = Math.hypot(evento.clientX - cx, evento.clientY - cy);
      definir({ tamanho: gesto.escala * (distancia / gesto.distancia) });
    } else if (gesto.tipo === 'girar') {
      // A alça fica em cima do bordado: apontar para cima é giro 0.
      const graus = (Math.atan2(evento.clientY - cy, evento.clientX - cx) * 180) / Math.PI + 90;
      if (rotacao) rotacao.value = String(ajustarGiro(graus, evento.shiftKey));
      renderizarPosicao();
    }
  }

  function terminarGesto() {
    gesto = null;
    caixa?.classList.remove('is-ativa');
  }

  /** Teclado na caixa: setas movem, + e - mudam o tamanho, [ e ] giram, 0 endireita. Shift acelera. */
  function teclaNaCaixa(evento) {
    const passo = evento.shiftKey ? PASSO_TECLADO * 5 : PASSO_TECLADO;
    const x = Number(posX?.value ?? POS_PADRAO);
    const y = Number(posY?.value ?? POS_PADRAO);
    const tamanho = Number(escala?.value ?? ESCALA_PADRAO);
    const giro = Number(rotacao?.value ?? 0);
    const acoes = {
      ArrowLeft: () => definir({ x: x - passo }),
      ArrowRight: () => definir({ x: x + passo }),
      ArrowUp: () => definir({ y: y - passo }),
      ArrowDown: () => definir({ y: y + passo }),
      '+': () => definir({ tamanho: tamanho + passo }),
      '=': () => definir({ tamanho: tamanho + passo }),
      '-': () => definir({ tamanho: tamanho - passo }),
      '[': () => { if (rotacao) rotacao.value = String(ajustarGiro(giro - PASSO_GIRO, false)); renderizarPosicao(); },
      ']': () => { if (rotacao) rotacao.value = String(ajustarGiro(giro + PASSO_GIRO, false)); renderizarPosicao(); },
      '0': () => definir({ giro: 0 }),
    };
    const acao = acoes[evento.key];

    if (acao) {
      evento.preventDefault();
      acao();
    }
  }

  /** Valida a etapa pelo nome (data-etapa): no modo matriz as etapas de peça e posição não existem. */
  function passoValido(indice) {
    const etapa = etapas[indice]?.dataset.etapa;

    if (etapa === 'matriz') {
      if (matrizEscolhida() || gradeSelecionada()) return true;
      mostrarErro('Escolha uma matriz ou envie uma imagem para converter.');
      return false;
    }

    if (etapa === 'peca') {
      const modo = pecaModo();

      if (modo === 'foto' && !fotoDoUsuario) {
        mostrarErro('Escolha a foto da sua peça.');
        return false;
      }

      return true;
    }

    if (etapa === 'publicar') {
      if (titulo && titulo.value.trim() !== '') return true;
      mostrarErro('Informe o título.');
      titulo?.focus();
      return false;
    }

    return true;
  }

  // Eventos: etapas.
  queryAll('[data-avancar]', root).forEach((botao) => on(botao, 'click', () => {
    if (passoValido(passoAtual)) irPara(passoAtual + 1);
  }));

  botoesVoltar.forEach((botao) => on(botao, 'click', () => irPara(passoAtual - 1)));

  // Eventos: matriz e imagem.
  radiosMatriz.forEach((radio) => on(radio, 'change', () => {
    if (radio.checked) {
      limparImagem({ manterSelecao: true });
      atualizarPreviewMatriz();
    }
  }));

  on(arquivo, 'change', converterSelecao);
  on(largura, 'change', converterSelecao);
  on(cores, 'change', converterSelecao);
  on(limpar, 'click', () => limparImagem({ manterSelecao: false }));

  // Eventos: peça base com foto e foto enviada pela pessoa.
  radiosBase.forEach((radio) => on(radio, 'change', () => sincronizarModoPeca()));

  on(arquivoPeca, 'change', () => {
    const file = arquivoPeca?.files?.[0];

    if (!file) return;

    if (/heic|heif/i.test(file.type) || /\.(heic|heif)$/i.test(file.name)) {
      arquivoPeca.value = '';
      mostrarErro('Foto em HEIC (padrão do iPhone) não abre no navegador. No app Fotos, exporte como JPEG e envie de novo.');
      return;
    }

    if (file.size > 10 * 1024 * 1024 || !/^image\/(png|jpeg|webp)$/.test(file.type)) {
      fotoDoUsuario = null;
      arquivoPeca.value = '';
      mostrarFotoEnviada();
      mostrarErro('Escolha uma imagem PNG, JPEG ou WebP de até 10 MB.');
      return;
    }

    const leitor = new FileReader();
    leitor.onload = () => {
      fotoDoUsuario = String(leitor.result ?? '');
      esconderErro();
      mostrarFotoEnviada();
      renderizarFundo();
    };
    leitor.readAsDataURL(file);
  });

  // Eventos: paleta de cores da peça (multiplica sobre a foto).
  amostraCores.forEach((botao) => on(botao, 'click', () => definirCorSimples(botao.dataset.cor)));

  // Eventos: posição.
  // Caixa de transformação: mover pelo corpo, redimensionar pelos cantos, girar pela alça de cima.
  if (area) {
    on(area, 'pointerdown', iniciarGesto);
    on(area, 'pointermove', continuarGesto);
    on(area, 'pointerup', terminarGesto);
    on(area, 'pointercancel', terminarGesto);
  }

  on(caixa, 'keydown', teclaNaCaixa);
  on(resetar, 'click', () => definir({ x: POS_PADRAO, y: POS_PADRAO, tamanho: ESCALA_PADRAO, giro: 0 }));

  // Envio: peça base / modo matriz seguem para o servidor; foto própria baixa o PNG (publicar com foto pede a migração).
  /**
   * Reduz a foto da peça para no máximo FOTO_LADO px no lado maior e devolve um data URL em JPEG.
   * Assim o envio fica pequeno (algumas centenas de KB) e cabe no limite do servidor.
   */
  async function fotoReduzida() {
    const img = await carregarImg(fotoDoUsuario);
    const fator = Math.min(1, FOTO_LADO / Math.max(img.naturalWidth, img.naturalHeight));
    const lienzo = document.createElement('canvas');
    lienzo.width = Math.round(img.naturalWidth * fator);
    lienzo.height = Math.round(img.naturalHeight * fator);
    const ctx = lienzo.getContext('2d');
    // JPEG não tem transparência: fundo branco no lugar das áreas vazias.
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, lienzo.width, lienzo.height);
    ctx.drawImage(img, 0, 0, lienzo.width, lienzo.height);

    return lienzo.toDataURL('image/jpeg', 0.85);
  }

  on(baixar, 'click', () => baixarMontagem());

  // Envio: a peça vai junto. Peça base manda o id; foto própria vai reduzida para o Storage.
  on(form, 'submit', async (evento) => {
    const ultima = etapas.length - 1;

    if (!passoValido(ultima)) {
      evento.preventDefault();
      irPara(ultima);
      return;
    }

    const botao = query('button[type="submit"]', form);
    const marcada = radiosBase.find((radio) => radio.checked);
    const comFoto = modoMontagem !== 'matriz' && pecaModo() === 'foto';

    if (pecaIdCampo) pecaIdCampo.value = !comFoto && marcada && marcada.value !== 'foto' ? marcada.value : '';
    if (fotoPecaCampo) fotoPecaCampo.value = '';

    if (!comFoto) {
      if (botao) botao.disabled = true;
      return;
    }

    // A foto precisa ser reduzida antes: segura o envio, prepara e envia de novo sem passar por aqui.
    evento.preventDefault();
    if (botao) botao.disabled = true;

    try {
      if (fotoPecaCampo) fotoPecaCampo.value = await fotoReduzida();
      HTMLFormElement.prototype.submit.call(form);
    } catch {
      if (botao) botao.disabled = false;
      mostrarErro('Não consegui preparar a foto da peça. Tente outra imagem.');
    }
  });

  // Estado inicial: primeira matriz e primeira peça base marcadas, posição padrão.
  // A matriz vinda da URL (?matriz=) já chega marcada pelo servidor; sem ela, marca a primeira.
  if (radiosMatriz[0] && !matrizEscolhida()) radiosMatriz[0].checked = true;
  if (radiosBase[0]) radiosBase[0].checked = true;

  renderizarFundo();
  sincronizarCategoria();
  definirCorSimples('#f3ded9');
  renderizarPosicao();
  atualizarPreviewMatriz();
  irPara(0);
}
