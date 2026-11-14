/**
 * Arquivo: tutorial.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: pedir o tutorial de IA ao servidor e mostrar na tela.
 * O CSRF vai no cabeçalho X-CSRF-Token; a resposta é JSON.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */

import { query, on } from '../utils/dom.js';

const ROTULO_BOTAO = 'Gerar tutorial com IA';
const ROTULO_CARREGANDO = 'Gerando tutorial...';

export function initTutorial(root) {
  const botao = query('[data-tutorial-gerar]', root);
  const texto = query('[data-tutorial-texto]', root);
  const nota = query('[data-tutorial-nota]', root);
  const erro = query('[data-tutorial-erro]', root);

  if (!botao || !texto || !nota || !erro) return;

  const bordadoId = root.dataset.bordadoId;
  const token = document.querySelector('meta[name="csrf"]')?.getAttribute('content') ?? '';

  const mostrarErro = (mensagem) => {
    erro.textContent = mensagem;
    erro.hidden = false;
  };

  const reativar = () => {
    botao.disabled = false;
    botao.textContent = ROTULO_BOTAO;
  };

  on(botao, 'click', async () => {
    botao.disabled = true;
    botao.textContent = ROTULO_CARREGANDO;
    erro.hidden = true;

    const corpo = new FormData();
    corpo.append('bordado_id', String(bordadoId ?? ''));

    let dados = null;

    try {
      const resposta = await fetch('/bordado/tutorial', {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-CSRF-Token': token,
        },
        body: corpo,
      });

      dados = await resposta.json().catch(() => null);

      if (!resposta.ok || !dados || typeof dados.texto !== 'string') {
        mostrarErro(
          dados?.erro === 'limite_excedido'
            ? 'Você pediu muitos tutoriais. Tente de novo em alguns minutos.'
            : 'Não consegui gerar o tutorial agora. Tente de novo.'
        );
        reativar();
        return;
      }
    } catch {
      mostrarErro('Falha de conexão. Verifique sua internet e tente de novo.');
      reativar();
      return;
    }

    // O texto chega por JSON e entra como textContent: nada de HTML do servidor vira marcação.
    texto.textContent = dados.texto;
    texto.hidden = false;
    nota.hidden = false;
    botao.hidden = true;
  });
}
