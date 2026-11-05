<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: centralizar o uso de $_SESSION.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Sessao
{
    /** TODO(Guilherme): registrar SessaoBanco, configurar o cookie com secure, httponly e samesite Lax, e chamar session_start(). */
    public static function iniciar(): void
    {
        throw new RuntimeException('Não implementado: Sessao::iniciar');
    }

    /** TODO(Guilherme): gravar um valor na sessão. */
    public static function definir(string $chave, mixed $valor): void
    {
        throw new RuntimeException('Não implementado: Sessao::definir');
    }

    /** TODO(Guilherme): ler um valor da sessão. */
    public static function obter(string $chave, mixed $padrao = null): mixed
    {
        throw new RuntimeException('Não implementado: Sessao::obter');
    }

    /** TODO(Guilherme): apagar um valor da sessão. */
    public static function remover(string $chave): void
    {
        throw new RuntimeException('Não implementado: Sessao::remover');
    }

    /** TODO(Guilherme): guardar uma mensagem de sucesso ou erro para a próxima tela. */
    public static function flash(string $tipo, string $mensagem): void
    {
        throw new RuntimeException('Não implementado: Sessao::flash');
    }

    /** TODO(Guilherme): guardar o formulário digitado, sem campos de senha. */
    public static function guardarVelhos(array $dados): void
    {
        throw new RuntimeException('Não implementado: Sessao::guardarVelhos');
    }

    /** TODO(Guilherme): devolver e apagar o formulário guardado. */
    public static function tirarVelhos(): array
    {
        throw new RuntimeException('Não implementado: Sessao::tirarVelhos');
    }
}
