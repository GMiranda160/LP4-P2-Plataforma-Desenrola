<?php
/**
 * Camada: Processamento. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: regras de comentários. Sem $_POST, sem $_SESSION e sem HTML.
 * HubControlador usa listar() na página do bordado. Não mude essa assinatura.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioServico
{
    /** Devolve os comentários do mais antigo ao mais novo, com o nome do autor; ocultos só quando $incluirOcultos. */
    public function listar(int $bordadoId, bool $incluirOcultos = false): array
    {
        return (new ComentarioDados())->listar($bordadoId, $incluirOcultos);
    }

    /** Os últimos comentários de cada bordado do feed, agrupados por bordado_id; ocultos só quando $incluirOcultos. */
    public function ultimosPorBordados(array $bordadoIds, int $limite = 2, bool $incluirOcultos = false): array
    {
        return (new ComentarioDados())->ultimosPorBordados($bordadoIds, $limite, $incluirOcultos);
    }

    /** O comentário com este id, ou null se não existir. Usado para saber para onde voltar após excluir ou ocultar. */
    public function buscar(int $id): ?array
    {
        return (new ComentarioDados())->buscar($id);
    }

    /** Aplica trim, exige de 1 a 280 caracteres (mb_strlen), recusa bordado oculto ou inexistente e devolve a lista de erros. */
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

    /** Exclui o comentário se quem pede for o autor ou a gerente. Devolve true se excluiu. */
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

    /** Inverte o campo oculto do comentário. */
    public function alternarOculto(int $id): void
    {
        (new ComentarioDados())->alternarOculto($id);
    }
}
