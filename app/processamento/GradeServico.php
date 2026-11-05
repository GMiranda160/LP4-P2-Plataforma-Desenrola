<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: validar a grade recebida e gerar o SVG no servidor.
 * Nunca salvar SVG enviado pelo navegador.
 * Veja INSTRUCOES_GUILHERME.md, seção 6.
 */
class GradeServico
{
    /** TODO(Guilherme): exigir tamanho de 8 a 64, até 16 cores #rrggbb, pontos com largura vezes altura e pelo menos um ponto. Lançar InvalidArgumentException se for inválida. */
    public function validar(string $json): array
    {
        throw new RuntimeException('Não implementado: GradeServico::validar');
    }

    /** TODO(Guilherme): gerar um path por cor, unindo pontos vizinhos da mesma linha, com shape-rendering crispEdges. */
    public function gerarSvg(array $grade): string
    {
        throw new RuntimeException('Não implementado: GradeServico::gerarSvg');
    }
}
