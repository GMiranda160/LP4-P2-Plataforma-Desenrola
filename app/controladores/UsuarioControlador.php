<?php
/**
 * Camada: Controlador. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: lista de usuários para a gerente.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class UsuarioControlador
{
    /** TODO(Eduardo): mostrar usuarios/lista com o layout gerente, sem nenhum hash. */
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
