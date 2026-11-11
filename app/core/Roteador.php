<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: encontrar a rota e chamar o controlador.
 *
 * O roteador é o único ponto que decide:
 * - Qual controlador atende a requisição (método e caminho exatos).
 * - Se o CSRF precisa ser validado (todo POST).
 * - Se o visitante precisa estar logado ou ser gerente.
 */
class Roteador
{
    /** @var array<int, array{0: string, 1: string, 2: array{class-string, string}, 3: string}> */
    private array $rotas;

    /** Guarda a lista de rotas devolvida por app/config/rotas.php. */
    public function __construct(array $rotas)
    {
        $this->rotas = $rotas;
    }

    /**
     * Compara método e caminho (exatos, sem barra final), valida CSRF em
     * POST pelo campo _csrf ou pelo cabeçalho X-CSRF-Token, confere o acesso
     * e chama o controlador. Sem rota, mostra a tela 404.
     */
    public function despachar(): void
    {
        $metodo = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $caminho = $this->normalizarCaminho((string) ($_SERVER['REQUEST_URI'] ?? '/'));

        $rota = $this->encontrar($metodo, $caminho);

        if ($rota === null) {
            $this->erro(404);
            return;
        }

        // CSRF vale para todo POST, antes de qualquer regra de acesso.
        if ($metodo === 'POST' && !$this->csrfValido()) {
            $this->erro(403);
            return;
        }

        $acesso = $rota[3];

        if ($acesso === 'login' && Auth::usuario() === null) {
            $this->requerLogin();
            return;
        }

        if ($acesso === 'gerente' && !Auth::eGerente()) {
            $this->erro(403);
            return;
        }

        [$classe, $acao] = $rota[2];
        (new $classe())->$acao();
    }

    /** Normaliza o caminho: tira a query string, força / inicial e remove a barra final. */
    private function normalizarCaminho(string $uri): string
    {
        $caminho = parse_url($uri, PHP_URL_PATH);

        if (!is_string($caminho) || $caminho === '') {
            return '/';
        }

        $caminho = '/' . trim($caminho, '/');

        return $caminho === '' ? '/' : $caminho;
    }

    /** Procura a rota pelo método e pelo caminho exatos. */
    private function encontrar(string $metodo, string $caminho): ?array
    {
        foreach ($this->rotas as $rota) {
            if ($rota[0] === $metodo && $rota[1] === $caminho) {
                return $rota;
            }
        }

        return null;
    }

    /** O token pode vir do campo _csrf do formulário ou do cabeçalho X-CSRF-Token (fetch). */
    private function csrfValido(): bool
    {
        $token = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        return is_string($token) && Csrf::valido($token);
    }

    /** Sem login: redireciona para /login, ou responde 401 se o cliente pediu JSON. */
    private function requerLogin(): void
    {
        $aceita = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');

        if (str_contains($aceita, 'application/json')) {
            Resposta::json(['erro' => 'login_necessario'], 401);
        }

        Resposta::redirecionar('/login');
    }

    /** Exibe a tela de erro 403 ou 404. Se a tela falhar, o tratador de exceções assume. */
    private function erro(int $codigo): void
    {
        http_response_code($codigo);
        Visao::render($codigo === 404 ? 'erros/404' : 'erros/403');
    }
}
