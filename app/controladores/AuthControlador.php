<?php
/**
 * Camada: Controlador. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: login, cadastro, saída e recuperação de senha.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class AuthControlador
{
    /** GET /login: mostra o formulário de login, repreenchido após um erro. */
    public function loginForm(): void
    {
        Visao::render('auth/login', [
            
            'titulo' => 'Entrar',
            'velhos' => Sessao::tirarVelhos(),
        ]);
    }

    /**
     * POST /login: monta usuario@example.com e confere a senha no AuthServico.
     * Gerente vai para /gerente e usuário vai para /hub. Com erro: Usuário ou senha inválidos.
     */
    public function login(): void
    {
        $usuario = mb_strtolower(trim((string) ($_POST['usuario'] ?? '')));
        $email = $usuario === '' ? '' : $usuario . '@example.com';
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

    /** GET /cadastro: mostra o formulário de cadastro com as perguntas de segurança. */
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

    /** POST /cadastro: cria a conta, já entra com ela e vai para /montador. Com erro, volta ao formulário. */
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

    /** POST /sair: encerra a sessão e volta para a home. */
    public function sair(): void
    {
        Auth::sair();
        Resposta::redirecionar('/');
    }

    /** GET /recuperar-senha: pede o usuário da conta. */
    public function recuperarForm(): void
    {
        Visao::render('auth/recuperar', [
            'titulo' => 'Recuperar senha',
        ]);
    }

    /**
     * POST /recuperar-senha: guarda na sessão o id e a pergunta da conta e mostra auth/redefinir.
     * Usuário desconhecido recebe uma mensagem genérica, para não revelar quem tem conta.
     */
    public function recuperar(): void
    {
        $usuario = mb_strtolower(trim((string) ($_POST['usuario'] ?? '')));
        $email = $usuario === '' ? '' : $usuario . '@example.com';
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

    /**
     * POST /recuperar-senha/redefinir: confere a resposta e troca a senha.
     * Depois de 5 tentativas erradas, a recuperação recomeça do início.
     */
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
