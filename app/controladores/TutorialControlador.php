<?php
/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: responder o tutorial de IA em JSON para o detalhe do bordado.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */
class TutorialControlador
{
    private const LIMITE_NOVOS = 5;
    private const JANELA_SEGUNDOS = 600;

    public function __construct(private TutorialServico $servico = new TutorialServico()) {}

    /**
     * POST /bordado/tutorial com bordado_id.
     * Responde { texto, origem }, com origem ia, cache ou reserva.
     * Limite: 5 tutoriais novos por usuário a cada 10 minutos (contados em Sessao).
     */
    public function gerar(): void
    {
        $bordadoId = (int) ($_POST['bordado_id'] ?? 0);

        if ($bordadoId <= 0) {
            Resposta::json(['erro' => 'bordado_invalido'], 400);
        }

        $bordado = (new BordadoDados())->buscarParaTutorial($bordadoId);

        if ($bordado === null) {
            Resposta::json(['erro' => 'bordado_nao_encontrado'], 404);
        }

        // Cache no banco não chama o Gemini e não conta no limite.
        $salvo = $bordado['tutorial_ia'] ?? null;

        if (is_string($salvo) && trim($salvo) !== '') {
            Resposta::json(['texto' => $salvo, 'origem' => 'cache']);
        }

        if ($this->limiteExcedido()) {
            Resposta::json([
                'erro' => 'limite_excedido',
                'texto' => 'Você pediu muitos tutoriais seguidos. Tente de novo em alguns minutos.',
            ], 429);
        }

        try {
            $resultado = $this->servico->obter($bordadoId);
        } catch (RuntimeException $erro) {
            error_log('TutorialControlador: ' . $erro->getMessage());
            Resposta::json(['erro' => 'tutorial_indisponivel'], 500);
        }

        if ($resultado['origem'] !== 'cache') {
            $this->registrarPedido();
        }

        Resposta::json($resultado);
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
