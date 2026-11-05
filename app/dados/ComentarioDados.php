<?php
/**
 * Camada: Dados. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: SQL da tabela comentarios. Sem validação e sem HTML.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioDados
{
    /** TODO(Eduardo): buscar com JOIN em usuarios. Sem ocultos, filtrar AND NOT c.oculto. */
    public function listar(int $bordadoId, bool $incluirOcultos = false): array
    {
        throw new RuntimeException('Não implementado: ComentarioDados::listar');
    }

    /** TODO(Eduardo): devolver o comentário, ou null. */
    public function buscar(int $id): ?array
    {
        throw new RuntimeException('Não implementado: ComentarioDados::buscar');
    }

    /** TODO(Eduardo): executar o INSERT. */
    public function inserir(int $bordadoId, int $usuarioId, string $texto): void
    {
        throw new RuntimeException('Não implementado: ComentarioDados::inserir');
    }

    /** TODO(Eduardo): executar o DELETE. */
    public function excluir(int $id): void
    {
        throw new RuntimeException('Não implementado: ComentarioDados::excluir');
    }

    /** TODO(Eduardo): inverter oculto. */
    public function alternarOculto(int $id): void
    {
        throw new RuntimeException('Não implementado: ComentarioDados::alternarOculto');
    }

    /** TODO(Eduardo): executar SELECT 1 FROM bordados WHERE id = :id AND NOT oculto. */
    public function bordadoVisivel(int $bordadoId): bool
    {
        throw new RuntimeException('Não implementado: ComentarioDados::bordadoVisivel');
    }

    /** TODO(Eduardo): devolver o total, usado no painel. */
    public function contar(): int
    {
        throw new RuntimeException('Não implementado: ComentarioDados::contar');
    }
}
