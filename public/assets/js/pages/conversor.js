/**
 * Arquivo: conversor.js
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: converter imagem em grade de pontos no navegador.
 *
 * TODO(Guilherme):
 * 1. Exportar converterImagem(arquivo, { largura, cores }) e desenharGrade(canvas, grade).
 * 2. Aceitar PNG, JPEG e WebP de até 5 MB.
 * 3. Reduzir em canvas, mantendo a proporção, e tratar alfa abaixo de 128 como vazio.
 * 4. Reduzir as cores com median cut.
 * 5. Montar pontos com . para vazio e um dígito hexadecimal para o índice da cor.
 * 6. A imagem original nunca sai do navegador.
 * Veja INSTRUCOES_GUILHERME.md, seção 6.
 */
