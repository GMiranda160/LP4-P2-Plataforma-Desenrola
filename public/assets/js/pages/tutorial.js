/**
 * Arquivo: tutorial.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: pedir o tutorial de IA ao servidor e mostrar a IA digitando na tela.
 * O pedido vai com Accept: application/x-ndjson e o CSRF no cabeçalho X-CSRF-Token.
 * O texto novo chega em fluxo (um JSON por linha) e é escrito letra a letra; o texto
 * já salvo e os erros chegam em JSON comum. Tudo entra como textContent, nunca como HTML.
 * Gerar pede de novo apenas se não houver texto; Regenerar pede sempre (forcar=1) e substitui na tela.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */

import { query, on } from '../utils/dom.js';

const ROTULOS = {
  gerar: { normal: 'Gerar tutorial com IA', carregando: 'Pensando...', escrevendo: 'Escrevendo...' },
  regenerar: { normal: 'Regenerar', carregando: 'Pensando...', escrevendo: 'Escrevendo...' },
};

const ERRO_PADRAO = 'Não consegui gerar o tutorial agora. Tente de novo.';
const ERRO_LIMITE = 'Você pediu muitos tutoriais. Tente de novo em alguns minutos.';
const ERRO_QUEDA = 'A conexão caiu antes do fim. Tente de novo para ver o tutorial completo.';

/** Quem prefere menos movimento recebe cada pedaço inteiro, sem a digitação letra a letra. */
const SEM_ANIMACAO = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;

/** O ancestral mais próximo com rolagem vertical própria, ou null se for a página. */
function acharRolagem(elemento) {
  for (let atual = elemento.parentElement; atual && atual !== document.body; atual = atual.parentElement) {
    const { overflowY } = getComputedStyle(atual);

    if (overflowY === 'auto' || overflowY === 'scroll') {
      return atual;
    }
  }

  return null;
}

/**
 * Digitador: recebe pedaços de texto e mostra no elemento aos poucos, a cada quadro.
 * Escreve cerca de 90 letras por segundo e acelera quando a fila cresce, para nunca
 * ficar muito atrás da IA. terminar() resolve quando a fila esvaziar.
 */
function criarDigitador(elemento) {
  let fila = '';
  let quadro = 0;
  let fimPedido = false;
  let aoTerminar = null;

  // Dentro de uma caixa com rolagem própria (o popup do Hub), acompanha o fim do texto,
  // a não ser que a pessoa tenha subido para reler.
  const rolagem = acharRolagem(elemento);

  let ultimo = 0;

  const passo = () => {
    quadro = 0;

    // A velocidade vem do tempo passado, não do número de quadros: celular lento digita igual.
    const agora = performance.now();
    const decorrido = ultimo === 0 ? 16 : Math.min(agora - ultimo, 250);
    ultimo = agora;

    if (fila !== '') {
      const noFim = rolagem !== null && rolagem.scrollHeight - rolagem.scrollTop - rolagem.clientHeight < 80;
      // 90 letras por segundo, mais rápido quanto maior a fila. Em aba escondida ou com
      // movimento reduzido, ninguém vê a digitação: escreve tudo de uma vez.
      const quantas = SEM_ANIMACAO || document.hidden
        ? fila.length
        : Math.max(1, Math.round(decorrido * (0.09 + fila.length / 1500)));
      elemento.textContent += fila.slice(0, quantas);
      fila = fila.slice(quantas);

      if (noFim) {
        rolagem.scrollTop = rolagem.scrollHeight;
      }
    }

    if (fila !== '') {
      agendar();
      return;
    }

    // Fila vazia: a próxima rajada da IA recomeça a contagem do tempo do zero.
    ultimo = 0;

    if (fimPedido && aoTerminar) {
      aoTerminar();
      aoTerminar = null;
    }
  };

  // O navegador pausa o requestAnimationFrame em aba escondida; lá, um timer segue o trabalho.
  const agendar = () => {
    if (quadro === 0) {
      quadro = document.hidden ? window.setTimeout(passo, 100) : requestAnimationFrame(passo);
    }
  };

  return {
    escrever(pedaco) {
      fila += pedaco;
      agendar();
    },
    limpar() {
      fila = '';
      elemento.textContent = '';
    },
    terminar() {
      fimPedido = true;

      return new Promise((resolver) => {
        aoTerminar = resolver;
        agendar();
      });
    },
  };
}

/** Lê o corpo NDJSON linha a linha e chama aoReceber com cada objeto. */
async function lerFluxo(resposta, aoReceber) {
  const leitor = resposta.body.getReader();
  const decodificador = new TextDecoder();
  let pendente = '';

  for (;;) {
    const { value, done } = await leitor.read();

    pendente += decodificador.decode(value ?? new Uint8Array(), { stream: !done });

    let quebra = pendente.indexOf('\n');

    while (quebra !== -1) {
      const linha = pendente.slice(0, quebra).trim();
      pendente = pendente.slice(quebra + 1);

      if (linha !== '') {
        aoReceber(JSON.parse(linha));
      }

      quebra = pendente.indexOf('\n');
    }

    if (done) {
      break;
    }
  }

  if (pendente.trim() !== '') {
    aoReceber(JSON.parse(pendente));
  }
}

async function pedirTutorial(botao, root, forcar) {
  const texto = query('[data-tutorial-texto]', root);
  const nota = query('[data-tutorial-nota]', root);
  const erro = query('[data-tutorial-erro]', root);

  if (!texto || !erro) {
    return;
  }

  const rotulos = forcar ? ROTULOS.regenerar : ROTULOS.gerar;
  const textoAnterior = texto.textContent;
  const digitador = criarDigitador(texto);
  let recebeuTexto = false;

  const comecarAEscrever = () => {
    if (!recebeuTexto) {
      recebeuTexto = true;
      digitador.limpar();
      texto.classList.remove('is-pensando');
      botao.textContent = rotulos.escrevendo;
    }
  };

  botao.disabled = true;
  botao.textContent = rotulos.carregando;
  erro.hidden = true;

  // Enquanto a IA pensa, a caixa aparece com os três pontinhos; o texto antigo (se houver) some.
  texto.hidden = false;
  texto.textContent = '';
  texto.classList.add('is-digitando', 'is-pensando');
  texto.setAttribute('aria-busy', 'true');

  const corpo = new FormData();
  corpo.append('bordado_id', String(root.dataset.bordadoId ?? ''));

  if (forcar) {
    corpo.append('forcar', '1');
  }

  try {
    const resposta = await fetch('/bordado/tutorial', {
      method: 'POST',
      headers: {
        // ndjson pede o fluxo; json fica para os erros e para o texto já salvo.
        Accept: 'application/x-ndjson, application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf"]')?.getAttribute('content') ?? '',
      },
      body: corpo,
    });

    const tipo = resposta.headers.get('Content-Type') ?? '';

    if (resposta.ok && tipo.includes('application/x-ndjson') && resposta.body) {
      let terminou = false;

      await lerFluxo(resposta, (linha) => {
        if (linha.tipo === 'texto' && typeof linha.texto === 'string') {
          comecarAEscrever();
          digitador.escrever(linha.texto);
        } else if (linha.tipo === 'recomecar') {
          digitador.limpar();
        } else if (linha.tipo === 'fim') {
          terminou = true;
        } else if (linha.tipo === 'erro') {
          throw new Error(ERRO_PADRAO);
        }
      });

      if (!terminou) {
        throw new Error(recebeuTexto ? ERRO_QUEDA : ERRO_PADRAO);
      }
    } else {
      const dados = await resposta.json().catch(() => null);

      if (!resposta.ok || !dados || typeof dados.texto !== 'string') {
        throw new Error(dados?.erro === 'limite_excedido' ? ERRO_LIMITE : ERRO_PADRAO);
      }

      comecarAEscrever();
      digitador.escrever(dados.texto);
    }

    await digitador.terminar();

    if (nota) {
      nota.hidden = false;
    }

    if (!forcar && botao.hasAttribute('data-tutorial-gerar')) {
      botao.hidden = true;
    }
  } catch (erroFetch) {
    await digitador.terminar();

    // Sem nenhum texto novo, volta o que estava na tela (ou esconde a caixa vazia).
    if (!recebeuTexto) {
      texto.textContent = textoAnterior;
      texto.hidden = textoAnterior.trim() === '';
    }

    erro.textContent = erroFetch instanceof Error && erroFetch.message ? erroFetch.message : ERRO_PADRAO;
    erro.hidden = false;
  } finally {
    texto.classList.remove('is-digitando', 'is-pensando');
    texto.removeAttribute('aria-busy');
    botao.disabled = false;
    botao.textContent = rotulos.normal;
  }
}

/** Liga os botões Gerar e Regenerar de um bloco [data-tutorial]. */
export function initTutorial(root) {
  const botaoGerar = query('[data-tutorial-gerar]', root);
  const botaoRegenerar = query('[data-tutorial-regenerar]', root);

  if (botaoGerar) {
    on(botaoGerar, 'click', () => pedirTutorial(botaoGerar, root, false));
  }

  if (botaoRegenerar) {
    on(botaoRegenerar, 'click', () => pedirTutorial(botaoRegenerar, root, true));
  }
}
