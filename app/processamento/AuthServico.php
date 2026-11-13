<?php
/**
 * Camada: Processamento. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: regras de conta, senha e recuperação. Sem $_POST, sem $_SESSION e sem HTML.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class AuthServico
{
    /**
     * Valida nome, usuário (3 a 30 caracteres, único), senha com 6 ou mais caracteres,
     * confirmação e pergunta de segurança. Grava senha e resposta só como hash, com perfil usuario.
     * Devolve [erros, usuario]: com erro, usuario é null.
     */
    public function cadastrar(array $campos): array
    {
        $erros = [];

        $nome = trim($campos['nome'] ?? '');
        $usuario = mb_strtolower(trim($campos['usuario'] ?? ''));
        $email = $usuario . '@example.com';
        $senha = $campos['senha'] ?? '';
        $confirmarSenha = $campos['confirmar_senha'] ?? '';
        $pergunta = $campos['pergunta_seguranca'] ?? '';
        $resposta = mb_strtolower(trim($campos['resposta'] ?? ''));

        $perguntas = [
            'nome do primeiro animal de estimação',
            'cidade onde nasceu',
            'nome da primeira escola',
        ];

        if ($nome === '') {
            $erros[] = 'Informe seu nome.';
        }

        if (!preg_match('/^[a-z0-9._-]{3,30}$/', $usuario)) {
            $erros[] = 'Informe um usuário válido: de 3 a 30 caracteres com letras, números, ponto, hífen ou sublinhado.';
        } else {
            $emailMontado = $usuario . '@example.com';

            if (!filter_var($emailMontado, FILTER_VALIDATE_EMAIL)) {
                $erros[] = 'Não foi possível montar um e-mail válido.';
            } elseif ((new UsuarioDados())->buscarPorEmail($emailMontado) !== null) {
                $erros[] = 'Este usuário já está cadastrado.';
            }
        }

        if (mb_strlen($senha) < 6) {
            $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        if ($senha !== $confirmarSenha) {
            $erros[] = 'As senhas não coincidem.';
        }

        if (!in_array($pergunta, $perguntas, true) || $resposta === '') {
            $erros[] = 'Escolha uma pergunta e informe a resposta.';
        }

        if ($erros !== []) {
            return [$erros, null];
        }

        $usuarios = new UsuarioDados();

        $id = $usuarios->inserir([
            'nome' => $nome,
            'email' => $email,
            'senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
            'perfil' => 'usuario',
            'pergunta_seguranca' => $pergunta,
            'resposta_hash' => password_hash($resposta, PASSWORD_DEFAULT),
        ]);

        $usuario = [
            'id' => $id,
            'nome' => $nome,
            'email' => $email,
            'perfil' => 'usuario',
        ];

        return [[], $usuario];
    }

    /** Confere a senha com password_verify e recusa conta inativa. Devolve o usuário sem os hashes, ou null. */
    public function autenticar(string $email, string $senha): ?array
    {
        $email = mb_strtolower(trim($email));
        $usuario = (new UsuarioDados())->buscarPorEmail($email);

        if ($usuario === null) {
            return null;
        }

        if (!password_verify($senha, $usuario['senha_hash'])) {
            return null;
        }

        $ativo = $usuario['ativo'] ?? false;
        $contaAtiva = $ativo === true
            || $ativo === 1
            || in_array(strtolower((string) $ativo), ['1', 't', 'true'], true);

        if (!$contaAtiva) {
            return null;
        }

        unset($usuario['senha_hash'], $usuario['resposta_hash']);

        return $usuario;
    }

    /** Id e pergunta de segurança da conta deste e-mail, ou null se não existir. */
    public function perguntaDe(string $email): ?array
    {
        $email = mb_strtolower(trim($email));
        $usuario = (new UsuarioDados())->buscarPorEmail($email);

        if ($usuario === null) {
            return null;
        }

        return [
            'id' => (int) $usuario['id'],
            'pergunta' => $usuario['pergunta_seguranca'],
        ];
    }

    /**
     * Confere a resposta (sem diferença de maiúsculas e espaços), valida a nova senha e troca o hash.
     * Devolve a lista de erros. Lista vazia significa que trocou.
     */
    public function redefinirSenha(int $id, array $campos): array
    {
        $usuarios = new UsuarioDados();
        $usuario = $usuarios->buscarPorId($id);

        if ($usuario === null) {
            return ['Não foi possível continuar com esses dados.'];
        }

        $resposta = mb_strtolower(trim($campos['resposta'] ?? ''));
        $senha = $campos['senha'] ?? '';
        $confirmarSenha = $campos['confirmar_senha'] ?? '';

        $erros = [];

        if (!password_verify($resposta, $usuario['resposta_hash'])) {
            $erros[] = 'Resposta de segurança incorreta.';
        }

        if (mb_strlen($senha) < 6) {
            $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        if ($senha !== $confirmarSenha) {
            $erros[] = 'As senhas não coincidem.';
        }

        if ($erros !== []) {
            return $erros;
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $usuarios->atualizarSenha($id, $hash);

        return [];
    }
}
