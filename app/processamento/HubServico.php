<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras do Hub (paginação, ordenação, quem vê post oculto, quem exclui,
 * curtidas). Sem SQL, sem $_POST, sem sessão e sem HTML.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class HubServico
{
    /** Bordados por página no feed. */
    public const POR_PAGINA = 12;

    /** Ordenações aceitas. A URL só escolhe a chave; o SQL vem daqui. */
    private const ORDENS = [
        'recentes' => 'b.criado_em DESC, b.id DESC',
        'curtidos' => 'curtidas DESC, b.criado_em DESC, b.id DESC',
    ];

    public function __construct(private HubDados $dados = new HubDados()) {}

    /** Normaliza ?ordem=. Valor desconhecido vira recentes. */
    public function ordem(?string $ordem): string
    {
        return isset(self::ORDENS[$ordem ?? '']) ? (string) $ordem : 'recentes';
    }

    /** Normaliza ?categoria=. Slug inválido vira null (todas). */
    public function categoria(?string $slug): ?string
    {
        $slug = strtolower(trim((string) $slug));

        return preg_match('/^[a-z0-9-]{1,80}$/', $slug) ? $slug : null;
    }

    /**
     * Uma página do feed. Busca POR_PAGINA + 1 para saber se existe próxima página.
     * Devolve ['bordados' => [...], 'temMais' => bool].
     */
    public function feed(int $usuario, ?string $categoria, string $ordem, int $pagina): array
    {
        $pagina = max(1, $pagina);
        $linhas = $this->dados->feed(
            $usuario,
            $categoria,
            self::ORDENS[$this->ordem($ordem)],
            self::POR_PAGINA + 1,
            ($pagina - 1) * self::POR_PAGINA
        );

        return [
            'bordados' => array_slice($linhas, 0, self::POR_PAGINA),
            'temMais' => count($linhas) > self::POR_PAGINA,
        ];
    }

    /** Os mais curtidos do Hub, para a home. Visitante usa :usuario = 0. */
    public function maisCurtidos(int $limite = 6, int $usuario = 0): array
    {
        return $this->dados->maisCurtidos($limite, $usuario);
    }

    /** Bordados do Hub do próprio usuário, inclusive ocultos. */
    public function doAutor(int $autorId): array
    {
        return $this->dados->doAutor($autorId);
    }

    /**
     * Bordado para a página de detalhe, já com a paleta decodificada.
     * Devolve null se não existir ou se a pessoa não puder ver: post oculto só abre para a gerente.
     */
    public function detalhe(int $id, int $usuario, bool $eGerente): ?array
    {
        $bordado = $id > 0 ? $this->dados->detalhe($id, $usuario) : null;

        if ($bordado === null || ($bordado['oculto'] && !$eGerente)) {
            return null;
        }

        $grade = json_decode((string) $bordado['grade'], true);
        $bordado['paleta'] = is_array($grade['paleta'] ?? null) ? array_values($grade['paleta']) : [];
        unset($bordado['grade']);

        return $bordado;
    }

    /** Autor do bordado ou gerente podem excluir. */
    public function podeExcluir(array $bordado, ?int $usuario, bool $eGerente): bool
    {
        return $eGerente || ($usuario !== null && (int) $bordado['autor_id'] === $usuario);
    }

    /**
     * Alterna a curtida. Devolve ['curtiu' => bool, 'curtidas' => int],
     * ou null se o bordado não existir, não for do Hub ou estiver oculto.
     */
    public function curtir(int $usuarioId, int $bordadoId): ?array
    {
        $bordado = $bordadoId > 0 ? $this->dados->buscarDono($bordadoId) : null;

        if ($bordado === null || $bordado['tipo'] !== 'hub' || $bordado['oculto']) {
            return null;
        }

        $curtiu = $this->dados->inserirCurtida($usuarioId, $bordadoId);
        if (!$curtiu) {
            $this->dados->removerCurtida($usuarioId, $bordadoId);
        }

        return ['curtiu' => $curtiu, 'curtidas' => $this->dados->curtidasDo($bordadoId)];
    }

    /**
     * Exclui um bordado, se a pessoa puder. Devolve uma mensagem de erro, ou null se excluiu.
     * Combinação usada em produto é protegida pelo banco (ON DELETE RESTRICT).
     */
    public function excluir(int $id, int $usuario, bool $eGerente): ?string
    {
        $bordado = $id > 0 ? $this->dados->buscarDono($id) : null;

        if ($bordado === null) {
            return 'Bordado não encontrado.';
        }
        if (!$this->podeExcluir($bordado, $usuario, $eGerente)) {
            return 'Você não pode excluir este bordado.';
        }

        try {
            $this->dados->excluir($id);
        } catch (PDOException $e) {
            if ($e->getCode() === '23503') {
                return 'Este bordado está em um produto do mostruário. Exclua o produto antes.';
            }
            throw $e;
        }

        return null;
    }

    /** Inverte o oculto. Devolve false se o bordado não existir. */
    public function alternarOculto(int $id): bool
    {
        if ($id <= 0 || $this->dados->buscarDono($id) === null) {
            return false;
        }

        $this->dados->alternarOculto($id);

        return true;
    }
}
