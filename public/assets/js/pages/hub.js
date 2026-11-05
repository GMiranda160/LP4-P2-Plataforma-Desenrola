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
