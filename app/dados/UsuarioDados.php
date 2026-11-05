<?php
/**
 * Camada: Dados. Dono: Eduardo. Só Eduardo altera este arquivo.
 * Responsabilidade: SQL da tabela usuarios. Sem validação e sem HTML.
 * Outras partes usam estes métodos. Não mude as assinaturas.
 * Veja INSTRUCOES_EDUARDO.md, seção 7.
 */
class UsuarioDados
{
    /** TODO(Eduardo): buscar o usuário pelo e-mail, ou devolver null. */
    public function buscarPorEmail(string $email): ?array
    {
        throw new RuntimeException('Não implementado: UsuarioDados::buscarPorEmail');
    }

    /** TODO(Eduardo): buscar o usuário pelo id, ou devolver null. */
    public function buscarPorId(int $id): ?array
    {
        throw new RuntimeException('Não implementado: UsuarioDados::buscarPorId');
    }

    /** TODO(Eduardo): inserir e devolver o id com RETURNING id. */
    public function inserir(array $d): int
    {
        throw new RuntimeException('Não implementado: UsuarioDados::inserir');
    }

    /** TODO(Eduardo): trocar o hash da senha. */
    public function atualizarSenha(int $id, string $hash): void
    {
        throw new RuntimeException('Não implementado: UsuarioDados::atualizarSenha');
    }

    /** TODO(Eduardo): listar id, nome, email, perfil, criado_em e total_bordados, sem hashes. */
    public function listar(string $busca = ''): array
    {
        throw new RuntimeException('Não implementado: UsuarioDados::listar');
    }

    /** TODO(Eduardo): devolver o total de usuários. */
    public function contar(): int
    {
        throw new RuntimeException('Não implementado: UsuarioDados::contar');
    }
}
