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
        return (new ComentarioDados())->listar($bordadoId, $incluirOcultos);
    }

    /** TODO(Eduardo): aplicar trim, aceitar de 1 a 280 caracteres com mb_strlen, recusar bordado oculto e devolver a lista de erros. */
    public function comentar(int $bordadoId, int $usuarioId, string $texto): array
    {
        $dados = new ComentarioDados();

        if (!$dados->bordadoVisivel($bordadoId)) {
            return ['Não é possível comentar neste bordado.'];
        }

        $texto = trim($texto);
        $tamanho = mb_strlen($texto, 'UTF-8');

        if ($tamanho < 1) {
            return ['Escreva um comentário.'];
        }

        if ($tamanho > 280) {
            return ['O comentário deve ter no máximo 280 caracteres.'];
        }

        $dados->inserir($bordadoId, $usuarioId, $texto);

        return [];
    }

    /** TODO(Eduardo): excluir só para o autor ou a gerente. */
    public function excluir(int $id, int $usuarioId, bool $eGerente): bool
    {
        $dados = new ComentarioDados();
        $comentario = $dados->buscar($id);

        if ($comentario === null) {
            return false;
        }

        $ehAutor = (int) $comentario['usuario_id'] === $usuarioId;

        if (!$ehAutor && !$eGerente) {
            return false;
        }

        $dados->excluir($id);

        return true;
    }

    /** TODO(Eduardo): inverter o campo oculto. */
    public function alternarOculto(int $id): void
    {
        (new ComentarioDados())->alternarOculto($id);
    }
}
