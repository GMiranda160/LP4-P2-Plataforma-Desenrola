<?php
/**
 * Camada: Processamento. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: regras de conta, senha e recuperação. Sem $_POST, sem $_SESSION e sem HTML.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class AuthServico
{
    /** TODO(Eduardo): validar nome, e-mail único, senha com 6 caracteres, confirmação e resposta. Criar hashes, usar perfil usuario e devolver [erros, usuario]. */
    public function cadastrar(array $campos): array
    {
        throw new RuntimeException('Não implementado: AuthServico::cadastrar');
    }

    /** TODO(Eduardo): conferir com password_verify e bloquear conta inativa. Devolver o usuário ou null. */
    public function autenticar(string $email, string $senha): ?array
    {
        throw new RuntimeException('Não implementado: AuthServico::autenticar');
    }

    /** TODO(Eduardo): devolver id e pergunta do usuário, ou null. */
    public function perguntaDe(string $email): ?array
    {
        throw new RuntimeException('Não implementado: AuthServico::perguntaDe');
    }

    /** TODO(Eduardo): conferir a resposta normalizada, validar a nova senha, trocar o hash e devolver a lista de erros. */
    public function redefinirSenha(int $id, array $campos): array
    {
        throw new RuntimeException('Não implementado: AuthServico::redefinirSenha');
    }
}
