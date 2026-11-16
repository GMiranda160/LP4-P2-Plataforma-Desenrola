/**
 * Arquivo: hub.js
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: interações dos posts do Hub, por delegação de eventos:
 *                   curtir sem recarregar (.js-curtir: POST para /hub/curtir com Accept: application/json e
 *                   X-CSRF-Token de meta[name="csrf"]), abrir e fechar o tutorial do post (.js-abrir-tutorial)
 *                   e pedir confirmação antes de excluir (form[data-confirmar]).
 * Veja INSTRUCOES_EDUARDO.md, seção 6.
 */

/** Envia a curtida e atualiza o coração e o número do post. */
async function curtir(botao) {
  botao.disabled = true;

  try {
    const resposta = await fetch('/hub/curtir', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-Token': document.querySelector('meta[name="csrf"]').content,
      },
      body: new URLSearchParams({ id: botao.dataset.id }),
    });

    if (resposta.status === 401) {
      window.location.href = '/login';
      return;
    }

    if (!resposta.ok) {
      throw new Error('Falha ao curtir');
    }

    const dados = await resposta.json();
    botao.setAttribute('aria-pressed', String(dados.curtiu));
    botao.querySelector('.js-curtidas').textContent = dados.curtidas;
  } catch (erro) {
    console.error('Não foi possível atualizar a curtida.', erro);
  } finally {
    botao.disabled = false;
  }
}

/** Abre ou fecha o painel do tutorial ligado ao botão por aria-controls. */
function alternarTutorial(botao) {
  const painel = document.getElementById(botao.getAttribute('aria-controls'));

  if (!painel) {
    return;
  }

  const abrir = painel.hidden;
  painel.hidden = !abrir;
  botao.setAttribute('aria-expanded', String(abrir));

  // No Hub o tutorial é um popup: trava a rolagem do fundo e devolve o foco ao fechar.
  if (painel.classList.contains('hub-modal')) {
    document.body.classList.toggle('hub-modal-aberto', abrir);

    if (abrir) {
      const fechar = painel.querySelector('.hub-modal__fechar');

      if (fechar) {
        fechar.focus();
      }
    } else {
      botao.focus();
    }
  }
}

/** Fecha um popup de tutorial aberto. */
function fecharModal(modal) {
  modal.hidden = true;
  document.body.classList.remove('hub-modal-aberto');

  const botao = document.querySelector(`[aria-controls="${modal.id}"]`);

  if (botao) {
    botao.setAttribute('aria-expanded', 'false');
    botao.focus();
  }
}

document.addEventListener('click', (evento) => {
  const alvo = evento.target instanceof Element ? evento.target : null;

  if (!alvo) {
    return;
  }

  const fechar = alvo.closest('[data-hub-fechar]');

  if (fechar) {
    const modal = fechar.closest('.hub-modal');

    if (modal) {
      fecharModal(modal);
      return;
    }
  }

  const botaoCurtir = alvo.closest('.js-curtir');

  if (botaoCurtir) {
    curtir(botaoCurtir);
    return;
  }

  const botaoTutorial = alvo.closest('.js-abrir-tutorial');

  if (botaoTutorial) {
    alternarTutorial(botaoTutorial);
  }
});

document.addEventListener('keydown', (evento) => {
  if (evento.key !== 'Escape') {
    return;
  }

  document.querySelectorAll('.hub-modal:not([hidden])').forEach(fecharModal);
});

/* Excluir bordado ou comentário só depois de confirmar. */
document.addEventListener('submit', (evento) => {
  const mensagem = evento.target.dataset?.confirmar;

  if (mensagem && !window.confirm(mensagem)) {
    evento.preventDefault();
  }
});
