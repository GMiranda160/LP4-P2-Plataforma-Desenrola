/**
 * Arquivo: hub.js
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: curtir sem recarregar a página.
 *
 * TODO(Eduardo):
 * 1. Usar delegação de eventos em .js-curtir.
 * 2. Enviar POST para /hub/curtir com o campo id, Accept: application/json e X-CSRF-Token.
 * 3. O token está em meta[name="csrf"].
 * 4. Com status 401, ir para /login.
 * 5. Atualizar aria-pressed e .js-curtidas com a resposta { curtiu, curtidas }.
 * 6. Tratar falha de rede sem quebrar a página.
 * Veja INSTRUCOES_EDUARDO.md, seção 6.
 */

document.addEventListener('click', async (evento) => {
  const botao = evento.target.closest('.js-curtir');

  if (!botao) {
    return;
  }

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
});