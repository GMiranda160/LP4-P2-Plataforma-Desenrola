<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras do feed, das curtidas e da moderação.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class HubServico
{
    /** TODO(Guilherme): buscar 13 itens para calcular temMais. A ordem usa lista fixa, nunca o valor direto. */
    public function feed(int $usuarioId, ?string $categoria, string $ordem, int $pagina): array
    {
        throw new RuntimeException('Não implementado: HubServico::feed');
    }

    /** TODO(Guilherme): alimentar a home. */
    public function maisCurtidos(int $limite = 6): array
    {
        throw new RuntimeException('Não implementado: HubServico::maisCurtidos');
    }

    /** TODO(Guilherme): devolver o bordado com paleta decodificada. */
    public function detalhe(int $id, int $usuarioId): ?array
    {
        throw new RuntimeException('Não implementado: HubServico::detalhe');
    }

    /** TODO(Guilherme): devolver os bordados do usuário. */
    public function meus(int $usuarioId): array
    {
        throw new RuntimeException('Não implementado: HubServico::meus');
    }

    /** TODO(Guilherme): devolver as categorias para os filtros. */
    public function categorias(): array
    {
        throw new RuntimeException('Não implementado: HubServico::categorias');
    }

    /** TODO(Guilherme): alternar a curtida e devolver curtiu e curtidas. */
    public function curtir(int $usuarioId, int $bordadoId): array
    {
        throw new RuntimeException('Não implementado: HubServico::curtir');
    }

    /** TODO(Guilherme): excluir só para o autor ou a gerente. */
    public function excluirBordado(int $id, int $usuarioId, bool $eGerente): bool
    {
        throw new RuntimeException('Não implementado: HubServico::excluirBordado');
    }

    /** TODO(Guilherme): inverter o campo oculto. */
    public function alternarOculto(int $id): void
    {
        throw new RuntimeException('Não implementado: HubServico::alternarOculto');
    }
}
