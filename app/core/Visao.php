<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: mostrar telas e parciais.
 *
 * Convenção de nomes:
 * - render('home/index') carrega app/telas/home/index.php.
 * - parcial('peca_bordada') carrega app/telas/parciais/peca_bordada.php.
 * - O nome só aceita letras, números, "-", "_" e "/"; "../" é recusado.
 *
 * O layout recebe $titulo e $conteudo já prontos. A tela não imprime o
 * cabeçalho nem o rodapé; isso é papel do layout.
 */
class Visao
{
    /**
     * Renderiza a tela dentro do layout informado (publico por padrão).
     * A tela pode definir $titulo; caso contrário, usa o nome da plataforma.
     * As $dados ficam disponíveis como variáveis dentro da tela.
     */
    public static function render(string $tela, array $dados = [], string $layout = 'publico'): void
    {
        $arquivo = self::resolver(RAIZ . '/app/telas', $tela);

        // Renderiza a tela em um buffer, capturando também o $titulo que ela definir.
        [$conteudo, $titulo] = (function () use ($arquivo, $dados): array {
            extract($dados, EXTR_SKIP);

            ob_start();
            require $arquivo;
            $html = (string) ob_get_clean();

            return [$html, $titulo ?? null];
        })();

        $arquivoLayout = RAIZ . '/app/telas/layouts/' . $layout . '.php';

        if (!is_file($arquivoLayout)) {
            throw new RuntimeException('Layout não encontrado: ' . $layout);
        }

        $titulo = $titulo ?? PLATAFORMA;

        (function () use ($arquivoLayout, $titulo, $conteudo, $dados): void {
            extract($dados, EXTR_SKIP);
            require $arquivoLayout;
        })();
    }

    /** Inclui a parcial direto na saída, com as $dados como variáveis. */
    public static function parcial(string $nome, array $dados = []): void
    {
        $arquivo = self::resolver(RAIZ . '/app/telas/parciais', $nome);

        (function () use ($arquivo, $dados): void {
            extract($dados, EXTR_SKIP);
            require $arquivo;
        })();
    }

    /** Valida o nome e devolve o caminho absoluto do arquivo, ou lança exceção. */
    private static function resolver(string $base, string $nome): string
    {
        if (!preg_match('/^[a-z0-9\/_-]+$/i', $nome) || str_contains($nome, '..')) {
            throw new RuntimeException('Nome de tela inválido: ' . $nome);
        }

        $arquivo = $base . '/' . $nome . '.php';

        if (!is_file($arquivo)) {
            throw new RuntimeException('Tela não encontrada: ' . $nome);
        }

        return $arquivo;
    }
}
