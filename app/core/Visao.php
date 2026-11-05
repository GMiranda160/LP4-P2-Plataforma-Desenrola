<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: mostrar telas e parciais.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Visao
{
    /** TODO(Guilherme): extrair $dados, renderizar app/telas/$tela e colocar o resultado no layout. */
    public static function render(string $tela, array $dados = [], string $layout = 'publico'): void
    {
        throw new RuntimeException('Não implementado: Visao::render');
    }

    /** TODO(Guilherme): incluir app/telas/parciais/$nome.php com $dados. */
    public static function parcial(string $nome, array $dados = []): void
    {
        throw new RuntimeException('Não implementado: Visao::parcial');
    }
}
