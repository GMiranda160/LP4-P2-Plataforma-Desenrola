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
        throw new RuntimeException('Não implementado: ComentarioControlador::comentar');
    }

    /** TODO(Eduardo): chamar o serviço com Auth::id() e Auth::eGerente(). */
    public function excluir(): void
    {
        throw new RuntimeException('Não implementado: ComentarioControlador::excluir');
    }

    /** TODO(Eduardo): inverter oculto. A rota é somente da gerente. */
    public function ocultar(): void
    {
        throw new RuntimeException('Não implementado: ComentarioControlador::ocultar');
    }
}
