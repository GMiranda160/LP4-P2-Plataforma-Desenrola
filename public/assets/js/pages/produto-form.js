/**
 * Arquivo: produto-form.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: no cadastro de produto, ao trocar a combinação, trazer o nome e a
 * descrição do bordado escolhido (o que a gerente escreveu no montador).
 *
 * Só troca um campo se ele estiver vazio ou ainda igual ao da combinação anterior:
 * o que a gerente já digitou à mão nunca é apagado.
 */

import { query } from '../utils/dom.js';

export function initProdutoForm(select) {
  const form = select.form;
  const nome = form ? query('[name="nome"]', form) : null;
  const descricao = form ? query('[name="descricao"]', form) : null;

  if (!nome || !descricao) {
    return;
  }

  const dadosDe = (opcao) => ({
    nome: opcao?.dataset.nome ?? '',
    descricao: opcao?.dataset.descricao ?? '',
  });

  let anterior = dadosDe(select.selectedOptions[0]);

  select.addEventListener('change', () => {
    const atual = dadosDe(select.selectedOptions[0]);

    if (nome.value.trim() === '' || nome.value === anterior.nome) {
      nome.value = atual.nome;
    }

    if (descricao.value.trim() === '' || descricao.value === anterior.descricao) {
      descricao.value = atual.descricao;
    }

    anterior = atual;
  });
}
