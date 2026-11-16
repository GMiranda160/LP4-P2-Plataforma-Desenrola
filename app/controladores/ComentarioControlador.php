<?php
/**
 * Camada: Controlador. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: publicar, excluir e ocultar comentários.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioControlador
{
    /** POST /hub/comentar: limita a 5 por minuto na sessão, publica pelo serviço e volta para a seção de comentários do bordado. */
    public function comentar(): void
    {
        $usuarioId = Auth::id();

        if ($usuarioId === null) {
            Resposta::redirecionar('/login');
        }

        $bordadoId = filter_var($_POST['bordado_id'] ?? null, FILTER_VALIDATE_INT);

        if ($bordadoId === false || $bordadoId < 1) {
            Sessao::flash('erro', 'Bordado inválido.');
            Resposta::redirecionar('/hub');
        }

        $destino = destino_hub($_POST['voltar'] ?? null, '/hub/bordado?id=' . $bordadoId . '#comentarios');
        $agora = time();

        $instantes = Sessao::obter('comentarios_minuto', []);

        if (!is_array($instantes)) {
            $instantes = [];
        }

        $instantes = array_values(array_filter(
            $instantes,
            static fn ($instante) => is_numeric($instante) && (int) $instante > $agora - 60
        ));

        if (count($instantes) >= 5) {
            Sessao::flash('erro', 'Você pode publicar até 5 comentários por minuto. Tente novamente em instantes.');
            Resposta::redirecionar($destino);
        }

        $texto = is_string($_POST['texto'] ?? null) ? $_POST['texto'] : '';
        $erros = (new ComentarioServico())->comentar($bordadoId, $usuarioId, $texto);

        if ($erros !== []) {
            Sessao::flash('erro', $erros[0]);
            Resposta::redirecionar($destino);
        }

        $instantes[] = $agora;
        Sessao::definir('comentarios_minuto', $instantes);

        Sessao::flash('sucesso', 'Comentário publicado.');
        Resposta::redirecionar($destino);
    }

    /** POST /hub/comentario/excluir: exclui pelo serviço com Auth::id() e Auth::eGerente(), e volta para a seção de comentários. */
    public function excluir(): void
    {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            Sessao::flash('erro', 'Comentário inválido.');
            Resposta::redirecionar('/hub');
        }

        $usuarioId = Auth::id();

        if ($usuarioId === null) {
            Resposta::redirecionar('/login');
        }

        $servico = new ComentarioServico();
        $comentario = $servico->buscar($id);
        $destino = destino_hub($_POST['voltar'] ?? null, $comentario !== null
            ? '/hub/bordado?id=' . (int) $comentario['bordado_id'] . '#comentarios'
            : '/hub');

        $excluido = $servico->excluir(
            $id,
            $usuarioId,
            Auth::eGerente()
        );

        if (!$excluido) {
            Sessao::flash('erro', 'Não foi possível excluir esse comentário.');
            Resposta::redirecionar($destino);
        }

        Sessao::flash('sucesso', 'Comentário excluído.');
        Resposta::redirecionar($destino);
    }

    /** POST /gerente/hub/comentario/ocultar: inverte o oculto. A rota já exige gerente; volta para a seção de comentários. */
    public function ocultar(): void
    {
        if (!Auth::eGerente()) {
            Sessao::flash('erro', 'Apenas a gerente pode fazer isso.');
            Resposta::redirecionar('/hub');
        }

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            Sessao::flash('erro', 'Comentário inválido.');
            Resposta::redirecionar('/hub');
        }

        $servico = new ComentarioServico();
        $comentario = $servico->buscar($id);
        $destino = destino_hub($_POST['voltar'] ?? null, $comentario !== null
            ? '/hub/bordado?id=' . (int) $comentario['bordado_id'] . '#comentarios'
            : '/hub');

        $servico->alternarOculto($id);

        Sessao::flash('sucesso', 'Visibilidade do comentário atualizada.');
        Resposta::redirecionar($destino);
    }
}
