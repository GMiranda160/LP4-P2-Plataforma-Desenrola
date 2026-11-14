/**
 * Arquivo: conversor.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: converter imagem em grade de pontos no navegador.
 * A imagem original nunca sai do aparelho da pessoa: tudo roda em canvas.
 * Veja INSTRUCOES_GUILHERME.md, seção 6.
 */

const TIPOS_ACEITOS = ['image/png', 'image/jpeg', 'image/webp'];
const TAMANHO_MAXIMO = 5 * 1024 * 1024; // 5 MB
const LARGURA_PADRAO = 256;
const CORES_PADRAO = 8;
const ALFA_MINIMO = 128;
const LADO_MINIMO = 8;
const LADO_MAXIMO = 1024;
const AMOSTRA_MAXIMA = 60000;

/**
 * Converte um arquivo de imagem em grade de pontos para ponto cruz.
 * Devolve { largura, altura, paleta, pontos, totalPontos }.
 * pontos usa '.' para vazio e um dígito hexadecimal para o índice da cor.
 * Lança Error com mensagem amigável em português se algo impedir a conversão.
 */
export async function converterImagem(arquivo, { largura = LARGURA_PADRAO, cores = CORES_PADRAO } = {}) {
  if (!(arquivo instanceof Blob)) {
    throw new Error('Escolha uma imagem.');
  }

  if (arquivo.size > TAMANHO_MAXIMO) {
    throw new Error('A imagem deve ter no máximo 5 MB.');
  }

  if (!TIPOS_ACEITOS.includes(arquivo.type)) {
    throw new Error('Envie uma imagem PNG, JPEG ou WebP.');
  }

  // O tamanho pedido vale para o lado maior. Assim uma foto em pé não passa do limite na altura.
  const lado = Math.min(LADO_MAXIMO, Math.max(LADO_MINIMO, Math.round(Number(largura) || LARGURA_PADRAO)));
  cores = Math.min(16, Math.max(1, Math.round(Number(cores) || CORES_PADRAO)));

  const bitmap = await createImageBitmap(arquivo);
  const proporcao = bitmap.height / bitmap.width;
  largura = proporcao > 1 ? Math.round(lado / proporcao) : lado;
  const altura = proporcao > 1 ? lado : Math.round(lado * proporcao);

  // O servidor exige pelo menos 8 pontos em cada lado.
  if (largura < LADO_MINIMO || altura < LADO_MINIMO) {
    bitmap.close();
    throw new Error('A imagem é estreita demais. Use uma imagem mais próxima de um quadrado ou um tamanho maior.');
  }

  const canvas = document.createElement('canvas');
  canvas.width = largura;
  canvas.height = altura;

  const contexto = canvas.getContext('2d', { willReadFrequently: true });
  contexto.drawImage(bitmap, 0, 0, largura, altura);
  bitmap.close();

  const { data } = contexto.getImageData(0, 0, largura, altura);

  // Marca como vazio todo pixel com alfa abaixo do mínimo.
  const pixels = [];
  let opacos = 0;

  for (let i = 0; i < data.length; i += 4) {
    if (data[i + 3] < ALFA_MINIMO) {
      pixels.push(null);
      continue;
    }

    pixels.push([data[i], data[i + 1], data[i + 2]]);
    opacos++;
  }

  if (opacos === 0) {
    throw new Error('A imagem é totalmente transparente.');
  }

  // A paleta sai de uma amostra de até AMOSTRA_MAXIMA pixels: em 1024x1024 ordenar 1 milhão de
  // pixels a cada divisão travaria a aba, e a amostra dá praticamente as mesmas cores.
  const opacosLista = pixels.filter(Boolean);
  const passo = Math.max(1, Math.floor(opacosLista.length / AMOSTRA_MAXIMA));
  const amostra = passo === 1 ? opacosLista : opacosLista.filter((_, i) => i % passo === 0);
  const paleta = reduzirCores(amostra, cores);
  const indices = pixels.map((pixel) => (pixel === null ? null : corMaisProxima(paleta, pixel)));
  const pontos = indices.map((indice) => (indice === null ? '.' : indice.toString(16))).join('');

  return {
    largura,
    altura,
    paleta: paleta.map(paraHex),
    pontos,
    totalPontos: opacos,
  };
}

/**
 * Desenha a grade em um canvas, um pixel por ponto.
 * O canvas assume as dimensões da grade; use CSS com image-rendering: pixelated para ampliar.
 */
export function desenharGrade(canvas, grade) {
  if (!canvas || !grade) return;

  canvas.width = grade.largura;
  canvas.height = grade.altura;

  // Escreve os pixels direto num ImageData: com 1024x1024 (1 milhão de pontos), um fillRect
  // por ponto trava o navegador por segundos.
  const contexto = canvas.getContext('2d');
  const imagem = contexto.createImageData(grade.largura, grade.altura);
  const cores = grade.paleta.map((hex) => [1, 3, 5].map((i) => parseInt(hex.slice(i, i + 2), 16)));
  const pontos = grade.pontos;

  for (let i = 0; i < pontos.length; i++) {
    const simbolo = pontos[i];

    if (simbolo === '.') continue;

    const cor = cores[parseInt(simbolo, 16)] ?? [0, 0, 0];
    const p = i * 4;
    imagem.data[p] = cor[0];
    imagem.data[p + 1] = cor[1];
    imagem.data[p + 2] = cor[2];
    imagem.data[p + 3] = 255;
  }

  contexto.putImageData(imagem, 0, 0);
}

/** Median cut recursivo: divide sempre o maior intervalo de cor até ter a paleta pedida. */
function reduzirCores(pixels, numCores) {
  const faixas = [pixels];

  while (faixas.length < numCores) {
    let alvo = -1;
    let maiorVariacao = 0;
    let canalEscolhido = 0;

    for (let i = 0; i < faixas.length; i++) {
      const faixa = faixas[i];

      if (faixa.length < 2) continue;

      for (let canal = 0; canal < 3; canal++) {
        let min = 255;
        let max = 0;

        for (const pixel of faixa) {
          if (pixel[canal] < min) min = pixel[canal];
          if (pixel[canal] > max) max = pixel[canal];
        }

        const variacao = max - min;

        if (variacao > maiorVariacao) {
          maiorVariacao = variacao;
          alvo = i;
          canalEscolhido = canal;
        }
      }
    }

    if (alvo === -1) break; // Nada mais para dividir.

    const faixa = faixas[alvo];
    faixa.sort((a, b) => a[canalEscolhido] - b[canalEscolhido]);

    const meio = Math.floor(faixa.length / 2);
    faixas.splice(alvo, 1, faixa.slice(0, meio), faixa.slice(meio));
  }

  return faixas.map(media);
}

/** Cor média da faixa, canal a canal. */
function media(faixa) {
  const soma = [0, 0, 0];

  for (const pixel of faixa) {
    soma[0] += pixel[0];
    soma[1] += pixel[1];
    soma[2] += pixel[2];
  }

  return soma.map((valor) => Math.round(valor / faixa.length));
}

/** Índice da cor mais próxima da paleta (distância euclidiana). */
function corMaisProxima(paleta, pixel) {
  let indice = 0;
  let menor = Infinity;

  for (let i = 0; i < paleta.length; i++) {
    const dr = paleta[i][0] - pixel[0];
    const dg = paleta[i][1] - pixel[1];
    const db = paleta[i][2] - pixel[2];
    const distancia = dr * dr + dg * dg + db * db;

    if (distancia < menor) {
      menor = distancia;
      indice = i;
    }
  }

  return indice;
}

/** Converte [r, g, b] para '#rrggbb'. */
function paraHex([r, g, b]) {
  return '#' + [r, g, b].map((valor) => valor.toString(16).padStart(2, '0')).join('');
}
