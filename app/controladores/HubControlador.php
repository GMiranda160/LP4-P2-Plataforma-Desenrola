<?php
/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: feed, detalhe e curtidas. As telas são de Eduardo.
 * Enquanto a lógica não estiver pronta, enviar dados de exemplo no formato do contrato da tela.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class HubControlador
{
    /** TODO(Guilherme): mostrar hub/feed com $titulo, $bordados, $categorias, $categoriaAtual, $ordem, $pagina e $temMais. */
    public function feed(): void
    {
        throw new RuntimeException('Não implementado: HubControlador::feed');
    }

    /** TODO(Guilherme): mostrar hub/detalhe com $titulo, $bordado, $comentarios e $podeExcluir. Post oculto só abre para a gerente. */
    public function detalhe(): void
    {
        throw new RuntimeException('Não implementado: HubControlador::detalhe');
    }

    /** TODO(Guilherme): mostrar hub/meus com $titulo e $bordados do usuário logado. */
    public function meus(): void
    {
        throw new RuntimeException('Não implementado: HubControlador::meus');
    }

    /** TODO(Guilherme): alternar a curtida e responder JSON com curtiu e curtidas. */
    public function curtir(): void
    {
        throw new RuntimeException('Não implementado: HubControlador::curtir');
    }

    /** TODO(Guilherme): permitir só para o autor ou a gerente. */
    public function excluirBordado(): void
    {
        throw new RuntimeException('Não implementado: HubControlador::excluirBordado');
    }

    /** TODO(Guilherme): inverter bordados.oculto. */
    public function ocultar(): void
    {
        throw new RuntimeException('Não implementado: HubControlador::ocultar');
    }
}
