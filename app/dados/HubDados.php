<?php
/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL do feed, das curtidas e da moderação.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class HubDados
{
    /** TODO(Guilherme): usar a consulta do feed, com LIMIT e OFFSET em PDO::PARAM_INT. */
    public function feed(int $usuarioId, ?string $categoria, string $ordem, int $limite, int $inicio): array
    {
        throw new RuntimeException('Não implementado: HubDados::feed');
    }

    /** TODO(Guilherme): ordenar pelo total de curtidas. */
    public function maisCurtidos(int $limite = 6): array
    {
        throw new RuntimeException('Não implementado: HubDados::maisCurtidos');
    }

    /** TODO(Guilherme): trazer os campos do card, mais descricao, autor_id, largura, altura, total_pontos, grade e tipo. */
    public function detalhe(int $id, int $usuarioId): ?array
    {
        throw new RuntimeException('Não implementado: HubDados::detalhe');
    }

    /** TODO(Guilherme): listar os bordados do usuário, no formato do card. */
    public function doAutor(int $autorId): array
    {
        throw new RuntimeException('Não implementado: HubDados::doAutor');
    }

    /** TODO(Guilherme): listar nome e slug das categorias. */
    public function categorias(): array
    {
        throw new RuntimeException('Não implementado: HubDados::categorias');
    }

    /** TODO(Guilherme): executar INSERT ... ON CONFLICT DO NOTHING e devolver rowCount. */
    public function inserirCurtida(int $usuarioId, int $bordadoId): int
    {
        throw new RuntimeException('Não implementado: HubDados::inserirCurtida');
    }

    /** TODO(Guilherme): executar o DELETE. */
    public function removerCurtida(int $usuarioId, int $bordadoId): void
    {
        throw new RuntimeException('Não implementado: HubDados::removerCurtida');
    }

    /** TODO(Guilherme): contar as curtidas de um bordado. */
    public function contarCurtidasDe(int $bordadoId): int
    {
        throw new RuntimeException('Não implementado: HubDados::contarCurtidasDe');
    }

    /** TODO(Guilherme): devolver o autor do bordado. */
    public function buscarAutor(int $bordadoId): ?int
    {
        throw new RuntimeException('Não implementado: HubDados::buscarAutor');
    }

    /** TODO(Guilherme): executar o DELETE. */
    public function excluirBordado(int $id): void
    {
        throw new RuntimeException('Não implementado: HubDados::excluirBordado');
    }

    /** TODO(Guilherme): inverter oculto. */
    public function alternarOculto(int $id): void
    {
        throw new RuntimeException('Não implementado: HubDados::alternarOculto');
    }

    /** TODO(Guilherme): contar os posts visíveis do Hub. */
    public function contarPublicados(): int
    {
        throw new RuntimeException('Não implementado: HubDados::contarPublicados');
    }

    /** TODO(Guilherme): contar todas as curtidas. */
    public function contarCurtidas(): int
    {
        throw new RuntimeException('Não implementado: HubDados::contarCurtidas');
    }
}
