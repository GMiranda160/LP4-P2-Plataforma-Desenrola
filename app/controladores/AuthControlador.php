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
        Visao::render('auth/login', [
            
            'titulo' => 'Entrar',
            'velhos' => Sessao::tirarVelhos(),
        ]);
    }

    /** TODO(Eduardo): chamar AuthServico::autenticar. Com sucesso, usar Auth::entrar; gerente vai para /gerente e usuário vai para /hub. Com erro: Usuário ou senha inválidos. */
    public function login(): void
    {
        $email = is_string($_POST['email'] ?? null) ? $_POST['email'] : '';
        $senha = is_string($_POST['senha'] ?? null) ? $_POST['senha'] : '';

        $usuario = (new AuthServico())->autenticar($email, $senha);

        if ($usuario === null) {
            Sessao::guardarVelhos($_POST);
            Sessao::flash('erro', 'Usuário ou senha inválidos.');
            Resposta::redirecionar('/login');
        }

        Auth::entrar($usuario);

        if (($usuario['perfil'] ?? '') === 'gerente') {
            Resposta::redirecionar('/gerente');
        }

        Resposta::redirecionar('/hub');
    }

    /** TODO(Eduardo): mostrar auth/cadastro com $titulo, $perguntas e $velhos. */
    public function cadastroForm(): void
    {
        Visao::render('auth/cadastro', [
            'titulo' => 'Criar conta',
            'perguntas' => [
                'nome do primeiro animal de estimação',
                'cidade onde nasceu',
                'nome da primeira escola',
            ],
            'velhos' => Sessao::tirarVelhos(),
        ]);
    }

    /** TODO(Eduardo): chamar o serviço. Com sucesso, usar Auth::entrar e ir para /montador. */
    public function cadastrar(): void
    {
        [$erros, $usuario] = (new AuthServico())->cadastrar($_POST);

        if ($erros !== []) {
            Sessao::guardarVelhos($_POST);
            Sessao::flash('erro', $erros[0]);
            Resposta::redirecionar('/cadastro');
        }

        Auth::entrar($usuario);
        Sessao::flash('sucesso', 'Conta criada com sucesso!');
        Resposta::redirecionar('/montador');
    }

    /** TODO(Eduardo): chamar Auth::sair e voltar para /. */
    public function sair(): void
    {
        Auth::sair();
        Resposta::redirecionar('/');
    }

    /** TODO(Eduardo): mostrar auth/recuperar. */
    public function recuperarForm(): void
    {
        Visao::render('auth/recuperar', [
            'titulo' => 'Recuperar senha',
        ]);
    }

    /** TODO(Eduardo): buscar o usuário, gravar recuperar_id na Sessao e mostrar auth/redefinir com a pergunta. E-mail desconhecido recebe mensagem genérica. */
    public function recuperar(): void
    {
        $email = is_string($_POST['email'] ?? null) ? $_POST['email'] : '';
        $dados = (new AuthServico())->perguntaDe($email);

        if ($dados === null) {
            Sessao::flash('erro', 'Não foi possível continuar com esses dados.');
            Resposta::redirecionar('/recuperar-senha');
        }

        Sessao::definir('recuperar_id', (int) $dados['id']);
        Sessao::definir('recuperar_pergunta', $dados['pergunta']);
        Sessao::definir('recuperar_tentativas', 0);

        Visao::render('auth/redefinir', [
            'titulo' => 'Redefinir senha',
            'pergunta' => $dados['pergunta'],
        ]);
    }

    /** TODO(Eduardo): contar tentativas na Sessao, com limite de 5, chamar o serviço, apagar recuperar_id e ir para /login. */
    public function redefinir(): void
    {
        $id = Sessao::obter('recuperar_id');

        if (!is_numeric($id)) {
            Sessao::flash('erro', 'Não foi possível continuar com esses dados.');
            Resposta::redirecionar('/recuperar-senha');
        }

        $erros = (new AuthServico())->redefinirSenha((int) $id, $_POST);

        if ($erros !== []) {
            $tentativas = (int) Sessao::obter('recuperar_tentativas', 0) + 1;

            if ($tentativas >= 5) {
                Sessao::remover('recuperar_id');
                Sessao::remover('recuperar_pergunta');
                Sessao::remover('recuperar_tentativas');
                Sessao::flash('erro', 'Limite de tentativas atingido. Inicie a recuperação novamente.');
                Resposta::redirecionar('/recuperar-senha');
            }

            Sessao::definir('recuperar_tentativas', $tentativas);
            Sessao::flash('erro', $erros[0]);

            Visao::render('auth/redefinir', [
                'titulo' => 'Redefinir senha',
                'pergunta' => Sessao::obter('recuperar_pergunta'),
            ]);
        }

        Sessao::remover('recuperar_id');
        Sessao::remover('recuperar_pergunta');
        Sessao::remover('recuperar_tentativas');
        Sessao::flash('sucesso', 'Senha alterada com sucesso!');
        Resposta::redirecionar('/login');
    }
}
