/**
 * Utilitários de DOM.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Pequenas funções compartilhadas pelos scripts da plataforma.
 */

/** Retorna o primeiro elemento que casa com o seletor. */
export function query(seletor, escopo = document) {
  return escopo.querySelector(seletor);
}

/** Retorna todos os elementos que casam com o seletor como array. */
export function queryAll(seletor, escopo = document) {
  return Array.from(escopo.querySelectorAll(seletor));
}

/** Adiciona um event listener a um elemento, se ele existir. */
export function on(alvo, evento, manipulador, opcoes) {
  if (alvo) {
    alvo.addEventListener(evento, manipulador, opcoes);
  }
}
