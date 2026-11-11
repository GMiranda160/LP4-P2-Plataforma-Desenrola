<?php

declare(strict_types=1);

/**
 * Camada: Controladores. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: montar a home pública. Sem SQL e sem regras de negócio.
 *
 * Os dados vêm do banco (produtos em destaque e mais curtidos do Hub).
 * Se o banco estiver vazio ou indisponível, a home renderiza com estado
 * vazio bonito em vez de quebrar; o erro fica no log.
 */
class HomeControlador
{
    public function index(): void
    {
        $destaques = [];
        $maisCurtidos = [];
        $categorias = [];

        try {
            $destaques = (new ProdutoServico())->destaques(6);
            $maisCurtidos = (new HubServico())->maisCurtidos(6, Auth::id() ?? 0);
            $categorias = (new ProdutoServico())->categorias();
        } catch (Throwable $erro) {
            error_log('Home: banco indisponível ou vazio (' . $erro->getMessage() . '). Renderizando com estado vazio.');
        }

        Visao::render('home/index', [
            'titulo' => 'Desenrola: Hub e Mostruário de Bordados',
            'destaques' => $destaques,
            'maisCurtidos' => $maisCurtidos,
            'categorias' => $categorias,
        ]);
    }
}
