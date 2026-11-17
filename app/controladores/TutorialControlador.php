<?php
/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: responder o tutorial de IA do bordado, em JSON ou em fluxo NDJSON (a IA digitando).
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */
class TutorialControlador
{
    private const LIMITE_NOVOS = 5;
    private const JANELA_SEGUNDOS = 600;

    public function __construct(private TutorialServico $servico = new TutorialServico()) {}

    /**
     * POST /bordado/tutorial com bordado_id. forcar=1 regenera mesmo havendo texto salvo.
     * Responde { texto, origem }, com origem ia, cache ou reserva. Com Accept: application/x-ndjson,
     * o texto novo chega em fluxo, pedaço por pedaço (veja gerarEmFluxo). Cache e erros são sempre JSON.
     * Limite: 5 tutoriais novos por usuário a cada 10 minutos (contados em Sessao).
     */
    public function gerar(): void
    {
        $bordadoId = (int) ($_POST['bordado_id'] ?? 0);
        $forcar = (string) ($_POST['forcar'] ?? '0') === '1';

        if ($bordadoId <= 0) {
            Resposta::json(['erro' => 'bordado_invalido'], 400);
        }

        $bordado = (new BordadoDados())->buscarParaTutorial($bordadoId);

        if ($bordado === null) {
            Resposta::json(['erro' => 'bordado_nao_encontrado'], 404);
        }

        // Cache no banco não chama o Gemini e não conta no limite; com forcar=1, regenera mesmo assim.
        $salvo = $bordado['tutorial_ia'] ?? null;

        if (!$forcar && is_string($salvo) && trim($salvo) !== '') {
            Resposta::json(['texto' => $salvo, 'origem' => 'cache']);
        }

        if ($this->limiteExcedido()) {
            Resposta::json([
                'erro' => 'limite_excedido',
                'texto' => 'Você pediu muitos tutoriais seguidos. Tente de novo em alguns minutos.',
            ], 429);
        }

        if ($this->querFluxo()) {
            $this->gerarEmFluxo($bordadoId, $forcar);
        }

        // Principal e fallback somados podem passar dos 30 s padrão do PHP.
        set_time_limit(70);

        try {
            $resultado = $this->servico->obter($bordadoId, $forcar);
        } catch (RuntimeException $erro) {
            error_log('TutorialControlador: ' . $erro->getMessage());
            Resposta::json(['erro' => 'tutorial_indisponivel'], 500);
        }

        if ($resultado['origem'] !== 'cache') {
            $this->registrarPedido();
        }

        Resposta::json($resultado);
    }

    /**
     * Responde em fluxo NDJSON, para a tela mostrar a IA digitando. Linhas, na ordem:
     * {tipo: inicio}, várias {tipo: texto, texto}, talvez {tipo: recomecar}, e {tipo: fim, origem}.
     * O pedido conta no limite antes de começar, porque a sessão é liberada durante o fluxo.
     */
    private function gerarEmFluxo(int $bordadoId, bool $forcar): never
    {
        $this->registrarPedido();
        Sessao::liberar();

        // Dois modelos em fluxo podem somar até 2 minutos. Se a pessoa fechar a aba,
        // o PHP segue até o fim para salvar o texto em bordados.tutorial_ia.
        set_time_limit(150);
        ignore_user_abort(true);

        Resposta::fluxo(function (callable $enviar) use ($bordadoId, $forcar): void {
            $enviar(['tipo' => 'inicio']);

            $resultado = $this->servico->obterEmFluxo(
                $bordadoId,
                $forcar,
                fn (string $evento, array $dados) => $enviar(['tipo' => $evento] + $dados)
            );

            $enviar(['tipo' => 'fim', 'origem' => $resultado['origem']]);
        });
    }

    /** O tutorial.js pede fluxo com Accept: application/x-ndjson; sem isso, a resposta é JSON inteiro. */
    private function querFluxo(): bool
    {
        return str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/x-ndjson');
    }

    private function limiteExcedido(): bool
    {
        return count($this->pedidosRecentes()) >= self::LIMITE_NOVOS;
    }

    private function registrarPedido(): void
    {
        $pedidos = $this->pedidosRecentes();
        $pedidos[] = time();
        Sessao::definir('tutoriais_novos', $pedidos);
    }

    /** Timestamps dos tutoriais novos pedidos na janela; remove os que saíram da janela. */
    private function pedidosRecentes(): array
    {
        $guardados = Sessao::obter('tutoriais_novos', []);
        $limite = time() - self::JANELA_SEGUNDOS;
        $recentes = array_values(array_filter(
            is_array($guardados) ? $guardados : [],
            fn ($t) => is_int($t) && $t > $limite
        ));

        if (count($recentes) !== count((array) $guardados)) {
            Sessao::definir('tutoriais_novos', $recentes);
        }

        return $recentes;
    }
}
