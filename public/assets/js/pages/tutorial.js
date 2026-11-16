/**
 * Arquivo: tutorial.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: pedir o tutorial de IA ao servidor e mostrar na tela.
 * O CSRF vai no cabeçalho X-CSRF-Token; a resposta é JSON.
 * Gerar pede de novo apenas se não houver texto; Regenerar pede sempre (forcar=1) e substitui na tela.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */

import { query, on } from '../utils/dom.js';

const ROTULOS = {
  gerar: { normal: 'Gerar tutorial com IA', carregando: 'Gerando tutorial...' },
  regenerar: { normal: 'Regenerar', carregando: 'Regenerando...' },
};

function pedirTutorial(botao, root, forcar) {
  const texto = query('[data-tutorial-texto]', root);
  const nota = query('[data-tutorial-nota]', root);
  const erro = query('[data-tutorial-erro]', root);

  if (!texto || !erro) {
    return () => {};
  }

  botao.disabled = true;

  const rotulos = forcar ? ROTULOS.regenerar : ROTULOS.gerar;
  botao.textContent = rotulos.carregando;
  erro.hidden = true;

  const corpo = new FormData();
  corpo.append('bordado_id', String(root.dataset.bordadoId ?? ''));

  if (forcar) {
    corpo.append('forcar', '1');
  }

  return fetch('/bordado/tutorial', {
    method: 'POST',
    headers: {
      Accept: 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf"]')?.getAttribute('content') ?? '',
    },
    body: corpo,
  })
    .then(async (resposta) => {
      const dados = await resposta.json().catch(() => null);

      if (!resposta.ok || !dados || typeof dados.texto !== 'string') {
        throw new Error(
          dados?.erro === 'limite_excedido'
            ? 'Você pediu muitos tutoriais. Tente de novo em alguns minutos.'
            : 'Não consegui gerar o tutorial agora. Tente de novo.'
        );
      }

      return dados;
    })
    .then((dados) => {
      // O texto chega por JSON e entra como textContent: nada de HTML do servidor vira marcação.
      texto.textContent = dados.texto;
      texto.hidden = false;

      if (nota) {
        nota.hidden = false;
      }

      if (!forcar && botao.hasAttribute('data-tutorial-gerar')) {
        botao.hidden = true;
      }

      botao.disabled = false;
      botao.textContent = rotulos.normal;
    })
    .catch((erroFetch) => {
      erro.textContent = erroFetch instanceof Error
        ? erroFetch.message
        : 'Não consegui gerar o tutorial agora. Tente de novo.';
      erro.hidden = false;
      botao.disabled = false;
      botao.textContent = rotulos.normal;
    });
}

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
