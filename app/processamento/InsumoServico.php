<?php
/**
 * Camada: Processamento. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: validar insumos e chamar InsumoDados. Sem $_POST, sem $_SESSION e sem HTML.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */
class InsumoServico
{
    /** TODO(Arthur): devolver insumos pela busca. */
    public function listar(string $busca = ''): array
    {
        throw new RuntimeException('Não implementado: InsumoServico::listar');
    }

    /** TODO(Arthur): devolver um insumo, ou null. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: InsumoServico::buscar');
    }

    /** TODO(Arthur): validar tipo, nome, unidade, quantidade >= 0, custo > 0, cor e nome único por tipo. Devolver a lista de erros; lista vazia significa que salvou. */
    public function salvar(int $id, array $campos): array
    {
        throw new RuntimeException('Não implementado: InsumoServico::salvar');
    }

    /** TODO(Arthur): excluir o insumo. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: InsumoServico::excluir');
    }
}
