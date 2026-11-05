<?php
/**
 * Arquivo: rotas.php
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: lista de todas as rotas do sistema.
 * Formato: [método, caminho, [Classe::class, 'método'], acesso]
 * Acesso: publico, login ou gerente.
 *
 * TODO(Guilherme): transformar a lista abaixo no array devolvido por este arquivo.
 * Veja README.md, seção 8.
 *
 * Público
 * GET  /                               HomeControlador::index
 * GET  /catalogo                       CatalogoControlador::listar
 * GET  /produto                        CatalogoControlador::produto
 * GET  /hub                            HubControlador::feed
 * GET  /hub/bordado                    HubControlador::detalhe
 * GET  /login                          AuthControlador::loginForm
 * POST /login                          AuthControlador::login
 * GET  /cadastro                       AuthControlador::cadastroForm
 * POST /cadastro                       AuthControlador::cadastrar
 * GET  /recuperar-senha                AuthControlador::recuperarForm
 * POST /recuperar-senha                AuthControlador::recuperar
 * POST /recuperar-senha/redefinir      AuthControlador::redefinir
 *
 * Login
 * POST /sair                           AuthControlador::sair
 * GET  /meus-bordados                  HubControlador::meus
 * POST /hub/curtir                     HubControlador::curtir
 * POST /hub/comentar                   ComentarioControlador::comentar
 * POST /hub/comentario/excluir         ComentarioControlador::excluir
 * POST /bordado/excluir                HubControlador::excluirBordado
 * GET  /montador                       MontadorControlador::index
 * POST /montador/salvar                MontadorControlador::salvar
 * POST /bordado/tutorial               TutorialControlador::gerar
 *
 * Gerente
 * GET  /gerente                        PainelControlador::index
 * GET  /gerente/usuarios               UsuarioControlador::listar
 * GET  /gerente/insumos                InsumoControlador::listar
 * GET  /gerente/insumos/form           InsumoControlador::form
 * POST /gerente/insumos/salvar         InsumoControlador::salvar
 * POST /gerente/insumos/excluir        InsumoControlador::excluir
 * GET  /gerente/clientes               ClienteControlador::listar
 * GET  /gerente/clientes/form          ClienteControlador::form
 * POST /gerente/clientes/salvar        ClienteControlador::salvar
 * POST /gerente/clientes/excluir       ClienteControlador::excluir
 * GET  /gerente/produtos               ProdutoControlador::listar
 * GET  /gerente/produtos/form          ProdutoControlador::form
 * POST /gerente/produtos/salvar        ProdutoControlador::salvar
 * POST /gerente/produtos/excluir       ProdutoControlador::excluir
 * POST /gerente/produtos/ativar        ProdutoControlador::ativar
 * GET  /gerente/matrizes               MatrizControlador::listar
 * GET  /gerente/matrizes/form          MatrizControlador::form
 * POST /gerente/matrizes/salvar        MatrizControlador::salvar
 * POST /gerente/matrizes/excluir       MatrizControlador::excluir
 * POST /gerente/hub/ocultar            HubControlador::ocultar
 * POST /gerente/hub/comentario/ocultar ComentarioControlador::ocultar
 */

return [];
