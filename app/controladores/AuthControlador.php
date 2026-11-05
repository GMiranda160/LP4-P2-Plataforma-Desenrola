<?php
/**
 * Camada: Controlador. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: login, cadastro, saída e recuperação de senha.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class AuthControlador
{
    /** TODO(Eduardo): mostrar auth/login com $titulo e $velhos. */
    public function loginForm(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::loginForm');
    }

    /** TODO(Eduardo): chamar AuthServico::autenticar. Com sucesso, usar Auth::entrar; gerente vai para /gerente e usuário vai para /hub. Com erro: Usuário ou senha inválidos. */
    public function login(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::login');
    }

    /** TODO(Eduardo): mostrar auth/cadastro com $titulo, $perguntas e $velhos. */
    public function cadastroForm(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::cadastroForm');
    }

    /** TODO(Eduardo): chamar o serviço. Com sucesso, usar Auth::entrar e ir para /montador. */
    public function cadastrar(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::cadastrar');
    }

    /** TODO(Eduardo): chamar Auth::sair e voltar para /. */
    public function sair(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::sair');
    }

    /** TODO(Eduardo): mostrar auth/recuperar. */
    public function recuperarForm(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::recuperarForm');
    }

    /** TODO(Eduardo): buscar o usuário, gravar recuperar_id na Sessao e mostrar auth/redefinir com a pergunta. E-mail desconhecido recebe mensagem genérica. */
    public function recuperar(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::recuperar');
    }

    /** TODO(Eduardo): contar tentativas na Sessao, com limite de 5, chamar o serviço, apagar recuperar_id e ir para /login. */
    public function redefinir(): void
    {
        throw new RuntimeException('Não implementado: AuthControlador::redefinir');
    }
}
