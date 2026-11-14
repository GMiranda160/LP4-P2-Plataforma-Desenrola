/**
 * Arquivo: moeda.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: máscara de dinheiro em reais nos campos com data-moeda.
 * Enquanto a pessoa digita, o campo mostra "R$ 1.234,56": cada dígito entra pelos centavos
 * (como em caixa eletrônico), e apagar tira o último dígito. No envio do formulário o valor
 * volta para o formato simples "1234.56", que é o que os serviços PHP já entendem.
 * Sem JavaScript o campo continua aceitando "1234,56" ou "1234.56".
 */

const LIMITE_CENTAVOS = 9999999999; // R$ 99.999.999,99, o máximo que o servidor aceita
const formatador = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

/** Centavos inteiros a partir do valor que veio do servidor ("12.5", "12,50" ou vazio). */
function centavosIniciais(texto) {
  const limpo = String(texto ?? '').trim().replace(/[^\d.,]/g, '');

  if (limpo === '') return null;

  // "1.234,56" (pt-BR) ou "1234.56" (banco): a última vírgula ou ponto separa os centavos.
  const corte = Math.max(limpo.lastIndexOf(','), limpo.lastIndexOf('.'));
  const inteiro = corte >= 0 ? limpo.slice(0, corte).replace(/\D/g, '') : limpo;
  const decimal = corte >= 0 ? limpo.slice(corte + 1).replace(/\D/g, '').padEnd(2, '0').slice(0, 2) : '00';
  const centavos = Number(`${inteiro || '0'}${decimal}`);

  return Number.isFinite(centavos) ? Math.min(centavos, LIMITE_CENTAVOS) : null;
}

/** "R$ 1.234,56" a partir de centavos; vazio quando não há valor. */
function formatar(centavos) {
  return centavos === null ? '' : formatador.format(centavos / 100);
}

/** Liga a máscara em um campo. O campo vira texto com teclado numérico no celular. */
export function initMoeda(campo) {
  if (!campo || campo.dataset.moedaPronta) return;

  campo.dataset.moedaPronta = '1';
  campo.type = 'text';
  campo.inputMode = 'numeric';
  campo.autocomplete = 'off';
  campo.removeAttribute('step');
  campo.removeAttribute('min');
  if (!campo.placeholder) campo.placeholder = 'R$ 0,00';

  let centavos = centavosIniciais(campo.value);
  campo.value = formatar(centavos);

  const mostrar = () => {
    campo.value = formatar(centavos);
    // Cursor sempre no fim: os dígitos entram pela direita.
    const fim = campo.value.length;
    campo.setSelectionRange?.(fim, fim);
  };

  campo.addEventListener('beforeinput', (evento) => {
    const tipo = evento.inputType;

    if (tipo === 'insertText' || tipo === 'insertReplacementText') {
      evento.preventDefault();
      const digitos = String(evento.data ?? '').replace(/\D/g, '');
      if (digitos === '') return;
      const novo = Number(`${centavos ?? 0}${digitos}`);
      if (novo <= LIMITE_CENTAVOS) centavos = novo;
      mostrar();
      return;
    }

    if (tipo.startsWith('delete')) {
      evento.preventDefault();
      // Campo todo selecionado (ex.: Ctrl+A e apagar) limpa de uma vez.
      const tudo = campo.selectionStart === 0 && campo.selectionEnd === campo.value.length;
      centavos = tudo || centavos === null || centavos < 10 ? null : Math.floor(centavos / 10);
      mostrar();
    }
  });

  // Colar ou autopreencher: relê o texto inteiro.
  campo.addEventListener('input', () => {
    centavos = centavosIniciais(campo.value);
    mostrar();
  });

  campo.addEventListener('focus', () => requestAnimationFrame(mostrar));

  // No envio, o servidor recebe "1234.56".
  campo.form?.addEventListener('submit', () => {
    campo.value = centavos === null ? '' : (centavos / 100).toFixed(2);
  });
}
