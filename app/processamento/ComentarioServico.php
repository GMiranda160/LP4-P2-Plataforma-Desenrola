<?php
/**
 * Camada: Processamento. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: regras de comentários. Sem $_POST, sem $_SESSION e sem HTML.
 * HubControlador usa listar() na página do bordado. Não mude essa assinatura.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioServico
{
    /** TODO(Eduardo): devolver os comentários do mais antigo ao mais novo, com o nome do autor. */
    public function listar(int $bordadoId, bool $incluirOcultos = false): array
    {
        throw new RuntimeException('Não implementado: ComentarioServico::listar');
    }

    /** TODO(Eduardo): aplicar trim, aceitar de 1 a 280 caracteres com mb_strlen, recusar bordado oculto e devolver a lista de erros. */
    public function comentar(int $bordadoId, int $usuarioId, string $texto): array
    {
        throw new RuntimeException('Não implementado: ComentarioServico::comentar');
    }

    /** TODO(Eduardo): excluir só para o autor ou a gerente. */
    public function excluir(int $id, int $usuarioId, bool $eGerente): bool
    {
        throw new RuntimeException('Não implementado: ComentarioServico::excluir');
    }

    /** TODO(Eduardo): inverter o campo oculto. */
    public function alternarOculto(int $id): void
    {
        throw new RuntimeException('Não implementado: ComentarioServico::alternarOculto');
    }
}
