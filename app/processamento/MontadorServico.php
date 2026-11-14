<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: validar e salvar matrizes e bordados vindos do montador.
 * Sem $_POST, sem sessão e sem HTML.
 * Veja INSTRUCOES_GUILHERME.md, seções 6 e 7.
 */
class MontadorServico
{
    private const MODOS = ['hub', 'matriz', 'produto'];
    private const LIMITE_POR_HORA = 20;
    // Foto da peça já vem reduzida pelo navegador (até 1600 px); 4 MB é folga para qualquer formato.
    private const FOTO_MAX = 4 * 1024 * 1024;
    private const FOTO_TIPOS = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    public function __construct(
        private BordadoDados $bordados = new BordadoDados(),
        private MatrizDados $matrizes = new MatrizDados(),
        private ProdutoDados $produtos = new ProdutoDados(),
        private GradeServico $grade = new GradeServico(),
        private StorageServico $storage = new StorageServico(),
    ) {
    }

    /**
     * Valida e grava. Devolve:
     * - erros:  lista de mensagens de validação. Vazia significa que salvou.
     * - id:     id do bordado (ou da matriz, no modo matriz), ou null em caso de erro.
     * - destino: rota para onde o controlador redireciona, ou null em caso de erro.
     *
     * Modo matriz:   só a gerente, cria só a matriz padrão (sem bordado) e manda para o modo produto
     *                com a matriz marcada, para a gerente montar a combinação.
     * Modo produto:  só a gerente, cria o bordado com tipo produto para a gerente completar.
     * Modo hub:      qualquer usuário logado, cria o bordado com tipo hub (o padrão).
     */
    public function salvar(array $campos, array $usuario): array
    {
        $usuarioId = (int) ($usuario['id'] ?? 0);
        $eGerente = ($usuario['perfil'] ?? '') === 'gerente';

        $modo = strtolower(trim((string) ($campos['modo'] ?? 'hub')));
        $titulo = trim((string) ($campos['titulo'] ?? ''));
        $descricao = trim((string) ($campos['descricao'] ?? ''));
        $corPeca = strtolower(trim((string) ($campos['cor_peca'] ?? '')));
        $inteiro = static fn(mixed $valor): int => is_numeric($valor) ? (int) $valor : -1;

        $categoriaId = $inteiro($campos['categoria_id'] ?? null);
        $posX = $inteiro($campos['pos_x'] ?? null);
        $posY = $inteiro($campos['pos_y'] ?? null);
        $escala = $inteiro($campos['escala'] ?? null);
        // Rotação em graus, de -180 a 180. Campo ausente vale 0 (bordado reto).
        $rotacaoBruta = $campos['rotacao'] ?? '';
        $rotacao = $rotacaoBruta === '' ? 0 : $inteiro($rotacaoBruta);
        if (!is_numeric($rotacaoBruta) && $rotacaoBruta !== '') {
            $rotacao = 999; // valor inválido: cai na validação abaixo
        }
        $matrizId = $inteiro($campos['matriz_id'] ?? null);
        $gradeJson = is_string($campos['grade_json'] ?? null) ? trim($campos['grade_json']) : '';
        // Peça: uma peça base cadastrada (peca_id) ou a foto da própria peça (foto_peca, data URL).
        $pecaId = $inteiro($campos['peca_id'] ?? null);
        $fotoDataUrl = is_string($campos['foto_peca'] ?? null) ? trim($campos['foto_peca']) : '';

        $erros = [];

        if (!in_array($modo, self::MODOS, true)) {
            $erros[] = 'Modo inválido.';
        }

        if (($modo === 'matriz' || $modo === 'produto') && !$eGerente) {
            $erros[] = 'Somente a gerente pode usar este modo.';
        }

        $tamanhoTitulo = function_exists('mb_strlen') ? mb_strlen($titulo) : strlen($titulo);

        if ($titulo === '') {
            $erros[] = 'Informe o título.';
        } elseif ($tamanhoTitulo > 120) {
            $erros[] = 'O título deve ter no máximo 120 caracteres.';
        }

        $tamanhoDescricao = function_exists('mb_strlen') ? mb_strlen($descricao) : strlen($descricao);

        if ($tamanhoDescricao > 500) {
            $erros[] = 'A descrição deve ter no máximo 500 caracteres.';
        }

        if (!preg_match('/^#[0-9a-f]{6}$/', $corPeca)) {
            $erros[] = 'Cor da peça inválida. Use o formato #rrggbb.';
        }

        if ($posX < 0 || $posX > 100) {
            $erros[] = 'A posição horizontal deve ficar entre 0 e 100.';
        }

        if ($posY < 0 || $posY > 100) {
            $erros[] = 'A posição vertical deve ficar entre 0 e 100.';
        }

        if ($escala < 10 || $escala > 90) {
            $erros[] = 'O tamanho deve ficar entre 10 e 90.';
        }

        if ($rotacao < -180 || $rotacao > 180) {
            $erros[] = 'A rotação deve ficar entre -180 e 180 graus.';
        }

        // Categoria só é exigida quando um bordado é criado (hub ou produto).
        if ($modo !== 'matriz') {
            if ($categoriaId <= 0) {
                $erros[] = 'Escolha a categoria.';
            } elseif (!$this->produtos->existe($categoriaId)) {
                $erros[] = 'Categoria não encontrada.';
            }
        }

        // Peça do bordado (não vale para o modo matriz, que não cria bordado).
        $foto = null;

        if ($modo !== 'matriz') {
            if ($fotoDataUrl !== '') {
                $foto = $this->lerFoto($fotoDataUrl);
                if ($foto === null) {
                    $erros[] = 'A foto da peça deve ser PNG, JPEG ou WebP de até 4 MB.';
                }
                $pecaId = 0;
            } elseif ($pecaId > 0) {
                $peca = (new PecaDados())->buscar($pecaId);
                if ($peca === null || !filter_var($peca['ativo'], FILTER_VALIDATE_BOOLEAN)) {
                    $erros[] = 'Peça não encontrada.';
                }
            }
        }

        // Matriz nova (imagem convertida) ou matriz existente.
        $grade = null;

        if ($gradeJson !== '') {
            try {
                $grade = $this->grade->validar($gradeJson);
            } catch (InvalidArgumentException $erro) {
                $erros[] = $erro->getMessage();
            }
        } elseif ($matrizId > 0) {
            if ($modo === 'matriz') {
                $erros[] = 'Para criar uma matriz, envie a imagem convertida.';
            } else {
                // Visibilidade geral: qualquer matriz cadastrada pode ser usada no bordado.
                if ($this->matrizes->buscar($matrizId) === null) {
                    $erros[] = 'Matriz não encontrada.';
                }
            }
        } else {
            $erros[] = $modo === 'matriz'
                ? 'Envie a imagem convertida para criar a matriz.'
                : 'Escolha uma matriz ou envie uma imagem.';
        }

        if ($erros) {
            return ['erros' => $erros, 'id' => null, 'destino' => null];
        }

        // Limite de abuso: 20 bordados por usuário a cada hora (não vale para o modo matriz).
        if ($modo !== 'matriz' && $this->bordados->contarRecentes($usuarioId) >= self::LIMITE_POR_HORA) {
            return [
                'erros' => ['Você criou muitos bordados na última hora. Tente novamente mais tarde.'],
                'id' => null,
                'destino' => null,
            ];
        }

        // Matriz nova: SVG e grade vão para o Storage antes da transação; o banco guarda só o caminho.
        // Se o banco recusar depois, os arquivos enviados são apagados no catch.
        $arquivos = [];

        if ($grade !== null) {
            $arquivos = [
                'svg_path' => StorageServico::novoCaminho('matrizes', 'svg'),
                'grade_path' => StorageServico::novoCaminho('matrizes', 'json'),
            ];
            $gradeLimpa = json_encode([
                'largura' => $grade['largura'],
                'altura' => $grade['altura'],
                'paleta' => $grade['paleta'],
                'pontos' => $grade['pontos'],
            ]);

            try {
                $this->storage->enviar($arquivos['svg_path'], $this->grade->gerarSvg($grade), 'image/svg+xml');
                $this->storage->enviar($arquivos['grade_path'], (string) $gradeLimpa, 'application/json');
            } catch (RuntimeException $erro) {
                error_log($erro->getMessage());
                $this->apagarArquivos($arquivos);

                return [
                    'erros' => ['Não consegui guardar o arquivo da matriz agora. Tente de novo em instantes.'],
                    'id' => null,
                    'destino' => null,
                ];
            }
        }

        // Foto da peça: também vai para o Storage antes da transação e é apagada se o banco falhar.
        if ($foto !== null) {
            $arquivos['foto_path'] = StorageServico::novoCaminho('fotos', $foto['extensao']);

            try {
                $this->storage->enviar($arquivos['foto_path'], $foto['conteudo'], $foto['tipo']);
            } catch (RuntimeException $erro) {
                error_log($erro->getMessage());
                $this->apagarArquivos($arquivos);

                return [
                    'erros' => ['Não consegui guardar a foto da peça agora. Tente de novo em instantes.'],
                    'id' => null,
                    'destino' => null,
                ];
            }
        }

        $pdo = Conexao::pdo();
        $pdo->beginTransaction();

        try {
            if ($grade !== null) {
                $matrizId = $this->matrizes->inserir([
                    'nome' => $titulo,
                    'descricao' => $descricao !== '' ? $descricao : null,
                    'categoria' => 'Geral',
                    'svg_path' => $arquivos['svg_path'],
                    'grade_path' => $arquivos['grade_path'],
                    'paleta' => json_encode(array_values($grade['paleta'])),
                    'largura' => $grade['largura'],
                    'altura' => $grade['altura'],
                    'total_pontos' => $grade['total_pontos'],
                    'tempo_medio_min' => 30,
                    // Matriz criada pela gerente (no montador ou avulsa) entra como padrão do ateliê.
                    'padrao' => $modo === 'matriz' || Auth::eGerente(),
                    'autor_id' => $usuarioId,
                ]);
            }

            if ($modo === 'matriz') {
                // Matriz padrão criada; nenhum bordado entra no Hub ou no mostruário.
                $pdo->commit();

                // Segue para montar a combinação com a matriz nova já escolhida.
                return ['erros' => [], 'id' => $matrizId, 'destino' => '/montador?modo=produto&matriz=' . $matrizId];
            }

            $tipo = $modo === 'produto' ? 'produto' : 'hub';

            $bordadoId = $this->bordados->inserir([
                'titulo' => $titulo,
                'descricao' => $descricao !== '' ? $descricao : null,
                'autor_id' => $usuarioId,
                'matriz_id' => $matrizId,
                'categoria_id' => $categoriaId,
                'cor_peca' => $corPeca,
                'pos_x' => $posX,
                'pos_y' => $posY,
                'escala' => $escala,
                'rotacao' => $rotacao,
                'peca_id' => $pecaId > 0 ? $pecaId : null,
                'foto_path' => $arquivos['foto_path'] ?? null,
                'tipo' => $tipo,
            ]);

            $pdo->commit();
        } catch (PDOException $erro) {
            $pdo->rollBack();
            $this->apagarArquivos($arquivos);

            // 23514: o banco recusou por uma regra (CHECK), por exemplo o tamanho da matriz.
            // Vira mensagem para a pessoa em vez de erro 500.
            if ($erro->getCode() === '23514') {
                return [
                    'erros' => ['O banco recusou os dados (tamanho da matriz ou posição fora do limite). Tente um tamanho menor.'],
                    'id' => null,
                    'destino' => null,
                ];
            }

            throw $erro;
        } catch (Throwable $erro) {
            $pdo->rollBack();
            $this->apagarArquivos($arquivos);
            throw $erro;
        }

        return [
            'erros' => [],
            'id' => $bordadoId,
            'destino' => $tipo === 'produto'
                ? '/gerente/produtos/form?bordado=' . $bordadoId
                : '/hub/bordado?id=' . $bordadoId,
        ];
    }

    /** Apaga do Storage os arquivos de uma matriz que não chegou a ser gravada no banco. */
    private function apagarArquivos(array $arquivos): void
    {
        foreach ($arquivos as $caminho) {
            $this->storage->remover((string) $caminho);
        }
    }

    /**
     * Lê a foto da peça enviada como data URL (data:image/...;base64,...).
     * Devolve conteudo, tipo e extensao, ou null se não for imagem PNG, JPEG ou WebP válida.
     * O tipo vem do conteúdo do arquivo, não do que o navegador declarou.
     */
    private function lerFoto(string $dataUrl): ?array
    {
        if (!preg_match('#^data:image/[a-z+]+;base64,#', $dataUrl, $inicio)) {
            return null;
        }

        $conteudo = base64_decode(substr($dataUrl, strlen($inicio[0])), true);

        if ($conteudo === false || $conteudo === '' || strlen($conteudo) > self::FOTO_MAX) {
            return null;
        }

        $info = @getimagesizefromstring($conteudo);
        $tipo = is_array($info) ? (string) ($info['mime'] ?? '') : '';

        if (!isset(self::FOTO_TIPOS[$tipo])) {
            return null;
        }

        return ['conteudo' => $conteudo, 'tipo' => $tipo, 'extensao' => self::FOTO_TIPOS[$tipo]];
    }
}
