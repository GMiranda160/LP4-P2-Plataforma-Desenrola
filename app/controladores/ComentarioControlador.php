<?php
/**
 * Camada: Controlador. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: publicar, excluir e ocultar comentários.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class ComentarioControlador
{
    /** TODO(Eduardo): limitar a 5 comentários por minuto com Sessao, chamar o serviço e voltar para /hub/bordado?id=...#comentarios. */
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

        $destino = '/hub/bordado?id=' . $bordadoId . '#comentarios';
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

    /** TODO(Eduardo): chamar o serviço com Auth::id() e Auth::eGerente(). */
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

        $excluido = (new ComentarioServico())->excluir(
            $id,
            $usuarioId,
            Auth::eGerente()
        );

        if (!$excluido) {
            Sessao::flash('erro', 'Não foi possível excluir esse comentário.');
            Resposta::redirecionar('/hub');
        }

        Sessao::flash('sucesso', 'Comentário excluído.');
        Resposta::redirecionar('/hub');
    }

    /** TODO(Eduardo): inverter oculto. A rota é somente da gerente. */
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

        (new ComentarioServico())->alternarOculto($id);

        Sessao::flash('sucesso', 'Visibilidade do comentário atualizada.');
        Resposta::redirecionar('/hub');
    }
}
