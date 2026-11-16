<?php
/**
 * Camada: Controlador. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: lista de usuários para a gerente.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class UsuarioControlador
{
    /** GET /gerente/usuarios: lista os usuários com o layout da gerente, sem nenhum hash; busca por nome ou e-mail em ?busca=. */
    public function listar(): void
    {
        $busca = is_string($_GET['busca'] ?? null)
        ? trim($_GET['busca'])
        : '';

        $usuarios = (new UsuarioDados())->listar($busca);

        Visao::render('usuarios/lista', [
            'titulo' => 'Usuários',
            'busca' => $busca,
            'usuarios' => $usuarios,
        ], 'gerente');
    }
}
