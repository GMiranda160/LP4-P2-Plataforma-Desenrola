<?php

declare(strict_types=1);

/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: abrir o montador e salvar bordados.
 * Veja INSTRUCOES_GUILHERME.md, seção 7.
 */
class MontadorControlador
{
    /** Modos do montador. hub é o padrão; matriz e produto são só da gerente. */
    private const MODOS = ['hub', 'matriz', 'produto'];

    /** GET /montador: abre o montador com matrizes, categorias e modo. */
    public function index(): void
    {
        $modo = in_array($_GET['modo'] ?? 'hub', self::MODOS, true) ? $_GET['modo'] ?? 'hub' : 'hub';

        if ($modo !== 'hub' && !Auth::eGerente()) {
            Sessao::flash('erro', 'Somente a gerente pode criar matrizes e combinações de produto.');
            Resposta::redirecionar('/montador');
        }

        // O usuário escolhe entre as matrizes padrão e as que ele mesmo já criou.
        $matrizDados = new MatrizDados();
        $matrizes = array_merge(
            $matrizDados->listarPadrao(),
            $matrizDados->listarDoAutor((int) Auth::id())
        );

        $titulos = ['hub' => 'Montar bordado', 'matriz' => 'Nova matriz', 'produto' => 'Nova combinação'];

        Visao::render('montador/index', [
            'titulo' => $titulos[$modo],
            'modo' => $modo,
            'matrizes' => $matrizes,
            'categorias' => (new ProdutoServico())->categorias(),
        ]);
    }

    /** TODO(Guilherme): chamar MontadorServico. Hub vai para /hub/bordado?id=, matriz vai para /gerente/matrizes e produto vai para /gerente/produtos/form. */
    public function salvar(): void
    {
        throw new RuntimeException('Não implementado: MontadorControlador::salvar');
    }
}
