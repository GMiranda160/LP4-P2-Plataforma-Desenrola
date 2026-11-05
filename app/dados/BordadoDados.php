<?php
/**
 * Camada: Dados. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: SQL de gravação e leitura de bordados para montador e tutorial.
 * Veja INSTRUCOES_GUILHERME.md, seções 7 e 8.
 */
class BordadoDados
{
    /** TODO(Guilherme): inserir o bordado e devolver o id. */
    public function inserir(array $d): int
    {
        throw new RuntimeException('Não implementado: BordadoDados::inserir');
    }

    /** TODO(Guilherme): devolver bordado, categoria e matriz. */
    public function buscarParaTutorial(int $id): ?array
    {
        throw new RuntimeException('Não implementado: BordadoDados::buscarParaTutorial');
    }

    /** TODO(Guilherme): gravar tutorial_ia. */
    public function salvarTutorial(int $id, string $texto): void
    {
        throw new RuntimeException('Não implementado: BordadoDados::salvarTutorial');
    }

    /** TODO(Guilherme): contar os bordados do usuário na última hora. */
    public function contarRecentes(int $usuarioId): int
    {
        throw new RuntimeException('Não implementado: BordadoDados::contarRecentes');
    }
}
