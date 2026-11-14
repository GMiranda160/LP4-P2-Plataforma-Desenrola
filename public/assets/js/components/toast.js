/**
 * Arquivo: toast.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: sumir com os toasts automaticamente e pelo botão de fechar.
 * Os toasts já vêm renderizados pelo servidor (parciais/mensagens.php) com a classe .alert.
 */

import { queryAll, query, on } from '../utils/dom.js';

const TEMPO_SUCESSO = 6000;
const TEMPO_ERRO = 9000;

export function initToasts() {
  const toasts = queryAll('.flash .alert');

  toasts.forEach((toast) => {
    const ehErro = toast.classList.contains('alert--erro');

    const remover = () => {
      if (toast.classList.contains('is-saindo')) return;
      toast.classList.add('is-saindo');
      toast.addEventListener('animationend', () => toast.remove(), { once: true });
    };

    on(query('[data-toast-fechar]', toast), 'click', remover);
    setTimeout(remover, ehErro ? TEMPO_ERRO : TEMPO_SUCESSO);
  });
}
