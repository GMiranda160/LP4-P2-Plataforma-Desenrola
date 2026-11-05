<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras de matrizes.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class MatrizServico
{
    /** TODO(Guilherme): devolver as matrizes padrão. */
    public function listarPadrao(): array
    {
        throw new RuntimeException('Não implementado: MatrizServico::listarPadrao');
    }

    /** TODO(Guilherme): devolver uma matriz. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: MatrizServico::buscar');
    }

    /** TODO(Guilherme): validar nome e tempo médio > 0. Devolver a lista de erros. */
    public function salvarDados(int $id, array $campos): array
    {
        throw new RuntimeException('Não implementado: MatrizServico::salvarDados');
    }

    /** TODO(Guilherme): recusar matriz em uso e devolver se excluiu. */
    public function excluir(int $id): bool
    {
        throw new RuntimeException('Não implementado: MatrizServico::excluir');
    }
}
