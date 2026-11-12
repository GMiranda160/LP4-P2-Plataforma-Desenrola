<?php

declare(strict_types=1);

/**
 * Camada: Controladores. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: catálogo público e página do produto.
 *
 * Sem produtos cadastrados, as telas renderizam com estado vazio bonito.
 * Erros de banco vão para o log, nunca para a tela do visitante.
 */
class CatalogoControlador
{
    public function listar(): void
    {
        $categoria = isset($_GET['categoria']) ? (string) $_GET['categoria'] : '';
        $produtos = [];
        $categorias = [];

        try {
            $produtos = (new ProdutoServico())->catalogo($categoria === '' ? null : $categoria);
            $categorias = (new ProdutoServico())->categorias();
        } catch (Throwable $erro) {
            error_log('Catálogo: banco indisponível ou vazio (' . $erro->getMessage() . ').');
        }

        Visao::render('catalogo/lista', [
            'titulo' => 'Catálogo | Desenrola',
            'pagina' => 'Catálogo',
            'produtos' => $produtos,
            'categorias' => $categorias,
            'categoriaAtual' => $categoria,
        ]);
    }

    public function produto(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $produto = null;

        if ($id > 0) {
            try {
                $produto = (new ProdutoServico())->mostrar($id);
            } catch (Throwable $erro) {
                error_log('Produto ' . $id . ': banco indisponível (' . $erro->getMessage() . ').');
            }
        }

        if ($produto === null) {
            http_response_code(404);
            Visao::render('erros/404');
            return;
        }

        Visao::render('catalogo/produto', [
            'titulo' => $produto['nome'] . ' | Desenrola',
            'pagina' => $produto['nome'],
            'produto' => $produto,
        ]);
    }
}
