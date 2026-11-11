<?php

declare(strict_types=1);

/**
 * Arquivo: rotas.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: lista de todas as rotas do sistema.
 * Formato: [método, caminho, [Classe::class, 'método'], acesso]
 * Acesso: publico, login ou gerente.
 */

return [
    // Público
    ['GET',  '/',                            [HomeControlador::class, 'index'],               'publico'],
    ['GET',  '/catalogo',                    [CatalogoControlador::class, 'listar'],          'publico'],
    ['GET',  '/produto',                     [CatalogoControlador::class, 'produto'],         'publico'],
    ['GET',  '/hub',                         [HubControlador::class, 'feed'],                 'publico'],
    ['GET',  '/hub/bordado',                 [HubControlador::class, 'detalhe'],              'publico'],
    ['GET',  '/login',                       [AuthControlador::class, 'loginForm'],           'publico'],
    ['POST', '/login',                       [AuthControlador::class, 'login'],               'publico'],
    ['GET',  '/cadastro',                    [AuthControlador::class, 'cadastroForm'],        'publico'],
    ['POST', '/cadastro',                    [AuthControlador::class, 'cadastrar'],           'publico'],
    ['GET',  '/recuperar-senha',             [AuthControlador::class, 'recuperarForm'],       'publico'],
    ['POST', '/recuperar-senha',             [AuthControlador::class, 'recuperar'],           'publico'],
    ['POST', '/recuperar-senha/redefinir',   [AuthControlador::class, 'redefinir'],           'publico'],

    // Login
    ['POST', '/sair',                        [AuthControlador::class, 'sair'],                'login'],
    ['GET',  '/meus-bordados',               [HubControlador::class, 'meus'],                 'login'],
    ['POST', '/hub/curtir',                  [HubControlador::class, 'curtir'],               'login'],
    ['POST', '/hub/comentar',                [ComentarioControlador::class, 'comentar'],      'login'],
    ['POST', '/hub/comentario/excluir',      [ComentarioControlador::class, 'excluir'],       'login'],
    ['POST', '/bordado/excluir',             [HubControlador::class, 'excluirBordado'],       'login'],
    ['GET',  '/montador',                    [MontadorControlador::class, 'index'],           'login'],
    ['POST', '/montador/salvar',             [MontadorControlador::class, 'salvar'],          'login'],
    ['POST', '/bordado/tutorial',            [TutorialControlador::class, 'gerar'],           'login'],

    // Gerente
    ['GET',  '/gerente',                     [PainelControlador::class, 'index'],             'gerente'],
    ['GET',  '/gerente/usuarios',            [UsuarioControlador::class, 'listar'],           'gerente'],
    ['GET',  '/gerente/insumos',             [InsumoControlador::class, 'listar'],            'gerente'],
    ['GET',  '/gerente/insumos/form',        [InsumoControlador::class, 'form'],              'gerente'],
    ['POST', '/gerente/insumos/salvar',      [InsumoControlador::class, 'salvar'],            'gerente'],
    ['POST', '/gerente/insumos/excluir',     [InsumoControlador::class, 'excluir'],           'gerente'],
    ['GET',  '/gerente/clientes',            [ClienteControlador::class, 'listar'],           'gerente'],
    ['GET',  '/gerente/clientes/form',       [ClienteControlador::class, 'form'],             'gerente'],
    ['POST', '/gerente/clientes/salvar',     [ClienteControlador::class, 'salvar'],           'gerente'],
    ['POST', '/gerente/clientes/excluir',    [ClienteControlador::class, 'excluir'],          'gerente'],
    ['GET',  '/gerente/produtos',            [ProdutoControlador::class, 'listar'],           'gerente'],
    ['GET',  '/gerente/produtos/form',       [ProdutoControlador::class, 'form'],             'gerente'],
    ['POST', '/gerente/produtos/salvar',     [ProdutoControlador::class, 'salvar'],           'gerente'],
    ['POST', '/gerente/produtos/excluir',    [ProdutoControlador::class, 'excluir'],          'gerente'],
    ['POST', '/gerente/produtos/ativar',     [ProdutoControlador::class, 'ativar'],           'gerente'],
    ['GET',  '/gerente/matrizes',            [MatrizControlador::class, 'listar'],            'gerente'],
    ['GET',  '/gerente/matrizes/form',       [MatrizControlador::class, 'form'],              'gerente'],
    ['POST', '/gerente/matrizes/salvar',     [MatrizControlador::class, 'salvar'],            'gerente'],
    ['POST', '/gerente/matrizes/excluir',    [MatrizControlador::class, 'excluir'],           'gerente'],
    ['POST', '/gerente/hub/ocultar',         [HubControlador::class, 'ocultar'],              'gerente'],
    ['POST', '/gerente/hub/comentario/ocultar', [ComentarioControlador::class, 'ocultar'],    'gerente'],
];
