<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: guardar as sessões na tabela sessoes, pois o disco do Render não é persistente.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class SessaoBanco implements SessionHandlerInterface
{
    /** TODO(Guilherme): devolver true. */
    public function open(string $path, string $name): bool
    {
        throw new RuntimeException('Não implementado: SessaoBanco::open');
    }

    /** TODO(Guilherme): devolver true. */
    public function close(): bool
    {
        throw new RuntimeException('Não implementado: SessaoBanco::close');
    }

    /** TODO(Guilherme): buscar os dados da sessão ainda válida, ou devolver texto vazio. */
    public function read(string $id): string|false
    {
        throw new RuntimeException('Não implementado: SessaoBanco::read');
    }

    /** TODO(Guilherme): gravar com INSERT ... ON CONFLICT e expiração de 1 dia. */
    public function write(string $id, string $data): bool
    {
        throw new RuntimeException('Não implementado: SessaoBanco::write');
    }

    /** TODO(Guilherme): apagar a sessão. */
    public function destroy(string $id): bool
    {
        throw new RuntimeException('Não implementado: SessaoBanco::destroy');
    }

    /** TODO(Guilherme): apagar as sessões expiradas. */
    public function gc(int $max_lifetime): int|false
    {
        throw new RuntimeException('Não implementado: SessaoBanco::gc');
    }
}
