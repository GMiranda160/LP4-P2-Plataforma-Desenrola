<?php

declare(strict_types=1);

/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: montar o painel da gerente com os totais do sistema.
 * Veja INSTRUCOES_GUILHERME.md, seção 10.
 */
class PainelControlador
{
    /** GET /gerente: mostra painel/index com os totais de cada área e atalhos para os cadastros. */
    public function index(): void
    {
        $hub = new HubDados();

        // Cada cartão: rótulo, total e link. O total é null quando a contagem ainda não está pronta.
        $totais = [
            ['rotulo' => 'Insumos', 'total' => $this->contar(fn () => (new InsumoDados())->contar()), 'link' => '/gerente/insumos'],
            ['rotulo' => 'Clientes', 'total' => $this->contar(fn () => (new ClienteDados())->contar()), 'link' => '/gerente/clientes'],
            ['rotulo' => 'Produtos', 'total' => $this->contar(fn () => (new ProdutoDados())->contar()), 'link' => '/gerente/produtos'],
            ['rotulo' => 'Matrizes', 'total' => $this->contar(fn () => (new MatrizDados())->contar()), 'link' => '/gerente/matrizes'],
            ['rotulo' => 'Usuários', 'total' => $this->contar(fn () => (new UsuarioDados())->contar()), 'link' => '/gerente/usuarios'],
            ['rotulo' => 'Posts no Hub', 'total' => $this->contar(fn () => $hub->contarPublicados()), 'link' => '/hub'],
            ['rotulo' => 'Curtidas', 'total' => $this->contar(fn () => $hub->contarCurtidas()), 'link' => '/hub?ordem=curtidos'],
            ['rotulo' => 'Comentários', 'total' => $this->contar(fn () => (new ComentarioDados())->contar()), 'link' => '/hub'],
        ];

        Visao::render('painel/index', [
            'titulo' => 'Painel',
            'totais' => $totais,
        ], 'gerente');
    }

    /**
     * Executa uma contagem. Método de colega ainda não implementado devolve null, para o painel
     * abrir mesmo assim. Qualquer outro erro continua subindo.
     */
    private function contar(callable $contagem): ?int
    {
        try {
            return $contagem();
        } catch (RuntimeException $e) {
            if (str_starts_with($e->getMessage(), 'Não implementado')) {
                return null;
            }
            throw $e;
        }
    }
}
