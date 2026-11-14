/**
 * Arquivo: mascara.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: formatar enquanto a pessoa digita os campos com data-mascara:
 *   documento  CPF (000.000.000-00) ou CNPJ (00.000.000/0000-00), detectado pela quantidade de dígitos
 *   telefone   fixo (00) 0000-0000 ou celular (00) 00000-0000, detectado pela quantidade de dígitos
 *   cep        00000-000
 * O valor vai formatado no envio; o ClienteServico já guarda CPF/CNPJ e CEP só com dígitos.
 * Sem JavaScript os campos aceitam o texto livre, como antes.
 */

/** Aplica um molde ("000.000.000-00") aos dígitos: cada 0 recebe um dígito; o resto é pontuação. */
function moldar(digitos, molde) {
  let saida = '';
  let i = 0;

  for (const simbolo of molde) {
    if (i >= digitos.length) break;
    saida += simbolo === '0' ? digitos[i++] : simbolo;
  }

  return saida;
}

const TIPOS = {
  documento: {
    maximo: 14,
    rotulo: (d) => (d.length > 11 ? 'CNPJ' : 'CPF'),
    // Até 11 dígitos é CPF; do 12º em diante vira CNPJ.
    formatar: (d) => (d.length > 11 ? moldar(d, '00.000.000/0000-00') : moldar(d, '000.000.000-00')),
    placeholder: 'CPF ou CNPJ',
  },
  telefone: {
    maximo: 11,
    // 10 dígitos com DDD é fixo; 11 é celular (com o 9 na frente).
    formatar: (d) => (d.length > 10 ? moldar(d, '(00) 00000-0000') : moldar(d, '(00) 0000-0000')),
    placeholder: '(00) 00000-0000',
  },
  cep: {
    maximo: 8,
    formatar: (d) => moldar(d, '00000-000'),
    placeholder: '00000-000',
  },
};

/** Liga a máscara de um campo, conforme data-mascara. */
export function initMascara(campo) {
  const tipo = TIPOS[campo?.dataset.mascara ?? ''];

  if (!tipo || campo.dataset.mascaraPronta) return;

  campo.dataset.mascaraPronta = '1';
  campo.inputMode = 'numeric';
  campo.autocomplete = campo.autocomplete || 'off';
  if (!campo.placeholder) campo.placeholder = tipo.placeholder;

  const etiqueta = tipo.rotulo ? document.createElement('span') : null;

  if (etiqueta) {
    // Mostra ao lado do rótulo se o número digitado é CPF ou CNPJ.
    etiqueta.className = 'mascara__tipo';
    etiqueta.setAttribute('aria-live', 'polite');
    document.querySelector(`label[for="${campo.id}"]`)?.append(etiqueta);
  }

  const aplicar = () => {
    // Quantos dígitos havia antes do cursor: o cursor volta para depois do mesmo dígito.
    const antesDoCursor = campo.value.slice(0, campo.selectionStart ?? campo.value.length).replace(/\D/g, '').length;
    const digitos = campo.value.replace(/\D/g, '').slice(0, tipo.maximo);
    const formatado = tipo.formatar(digitos);

    campo.value = formatado;

    if (etiqueta) etiqueta.textContent = digitos.length ? tipo.rotulo(digitos) : '';

    if (document.activeElement === campo) {
      let posicao = 0;
      let vistos = 0;
      while (posicao < formatado.length && vistos < antesDoCursor) {
        if (/\d/.test(formatado[posicao])) vistos++;
        posicao++;
      }
      campo.setSelectionRange(posicao, posicao);
    }
  };

  campo.addEventListener('input', aplicar);
  aplicar();
}
