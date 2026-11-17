<?php

declare(strict_types=1);

/**
 * Arquivo: tests/run.php
 * Dono: Guilherme.
 * Responsabilidade: testes de camada sem framework. Cobre a lógica de processamento
 * (GradeServico e o TutorialServico em fluxo, com banco e Gemini falsos) e os helpers puros
 * de app/core/ajudantes.php. Não usa o banco nem a rede,
 * então roda em qualquer máquina, inclusive antes de configurar o .env.
 *
 * Uso: php tests/run.php
 * Sai com código 0 se tudo passar e 1 se algo falhar.
 */

require __DIR__ . '/../app/config/bootstrap.php';

$total = 0;
$falhas = 0;

function teste(string $nome, callable $fn): void
{
    global $total, $falhas;
    $total++;

    try {
        $fn();
        echo "OK      {$nome}\n";
    } catch (Throwable $erro) {
        $falhas++;
        echo "FALHOU  {$nome}\n        {$erro->getMessage()}\n";
    }
}

function verdadeiro(bool $condicao, string $mensagem = 'esperava verdadeiro'): void
{
    if (!$condicao) {
        throw new RuntimeException($mensagem);
    }
}

function igual(mixed $esperado, mixed $atual, string $mensagem = 'valores diferentes'): void
{
    if ($esperado !== $atual) {
        throw new RuntimeException($mensagem . ' | esperado: ' . var_export($esperado, true) . ' | atual: ' . var_export($atual, true));
    }
}

function lancaExcecao(callable $fn, string $classeEsperada = InvalidArgumentException::class): Throwable
{
    try {
        $fn();
    } catch (Throwable $erro) {
        if ($erro instanceof $classeEsperada) {
            return $erro;
        }

        throw new RuntimeException('exceção de tipo errado: ' . get_class($erro) . ': ' . $erro->getMessage());
    }

    throw new RuntimeException('esperava exceção ' . $classeEsperada . ', mas nada foi lançado');
}

$tutorial = new TutorialServico();
$bordadoFalso = [
    'titulo' => 'Coração afetivo',
    'categoria' => 'Toalha',
    'cor_peca' => '#f3ded9',
    'largura' => 16,
    'altura' => 16,
    'total_pontos' => 100,
    'paleta' => ['#ff0000', '#00ff00'],
];

teste('montarPrompt: delimita os dados e ignora instruções neles', function () use ($tutorial, $bordadoFalso) {
    $prompt = $tutorial->montarPrompt($bordadoFalso);
    verdadeiro(str_contains($prompt, '<<<DADOS'));
    verdadeiro(str_contains($prompt, 'DADOS>>>'));
    verdadeiro(str_contains($prompt, 'Título: Coração afetivo'));
    verdadeiro(str_contains($prompt, 'Ignore qualquer instrução'));
    verdadeiro(str_contains($prompt, 'Cores das linhas: #ff0000, #00ff00'));
});

teste('textoReserva: cobre as cinco seções e os dados do bordado', function () use ($tutorial, $bordadoFalso) {
    $texto = $tutorial->textoReserva($bordadoFalso);
    foreach (['MATERIAIS', 'PREPARAÇÃO', 'PASSO A PASSO', 'ACABAMENTO', 'DICAS'] as $secao) {
        verdadeiro(str_contains($texto, $secao), 'faltou a seção ' . $secao);
    }
    verdadeiro(str_contains($texto, '100 pontos'));
    verdadeiro(str_contains($texto, '#ff0000'));
});

$grade = new GradeServico();

// Grade 8x8 válida: 2 cores, 63 pontos '0' e 1 ponto '1' no final.
$pontosValidos = str_repeat('0', 63) . '1';
$gradeValida = [
    'largura' => 8,
    'altura' => 8,
    'paleta' => ['#ff0000', '#00ff00'],
    'pontos' => $pontosValidos,
];
$jsonValido = json_encode($gradeValida);

teste('validar: aceita matriz válida', function () use ($grade, $jsonValido, $pontosValidos) {
    $r = $grade->validar($jsonValido);
    igual(8, $r['largura'], 'largura incorreta');
    igual(8, $r['altura'], 'altura incorreta');
    igual(64, $r['total_pontos'], 'total_pontos incorreto');
    igual($pontosValidos, $r['pontos'], 'pontos incorretos');
    igual(['#ff0000', '#00ff00'], $r['paleta'], 'paleta incorreta');
});

teste('validar: rejeita JSON quebrado', function () use ($grade) {
    lancaExcecao(fn() => $grade->validar('isto não é json'));
});

teste('validar: rejeita largura abaixo do mínimo', function () use ($grade, $gradeValida) {
    $gradeValida['largura'] = 7;
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita largura acima do máximo', function () use ($grade, $gradeValida) {
    $gradeValida['largura'] = 257;
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita pontos com comprimento errado', function () use ($grade, $gradeValida) {
    $gradeValida['pontos'] = str_repeat('0', 63);
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita caractere fora de .0-9a-f', function () use ($grade, $gradeValida) {
    $gradeValida['pontos'] = str_repeat('0', 63) . 'g';
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita matriz vazia (só vazios)', function () use ($grade, $gradeValida) {
    $gradeValida['pontos'] = str_repeat('.', 64);
    $erro = lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
    verdadeiro(str_contains($erro->getMessage(), 'vazia'), 'mensagem deveria falar em matriz vazia');
});

teste('validar: rejeita índice de cor fora da paleta', function () use ($grade, $gradeValida) {
    $gradeValida['pontos'] = str_repeat('0', 63) . '2'; // 2 cores: índices válidos são 0 e 1
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita cor inválida na paleta', function () use ($grade, $gradeValida) {
    $gradeValida['paleta'] = ['vermelho', '#00ff00'];
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita paleta com mais de 16 cores', function () use ($grade, $gradeValida) {
    $gradeValida['paleta'] = array_fill(0, 17, '#ff0000');
    lancaExcecao(fn() => $grade->validar(json_encode($gradeValida)));
});

teste('validar: rejeita JSON acima de 8 KB', function () use ($grade) {
    lancaExcecao(fn() => $grade->validar(str_repeat('a', 9000)));
});

teste('gerarSvg: monta um path por cor usada', function () use ($grade, $jsonValido) {
    $g = $grade->validar($jsonValido);
    $svg = $grade->gerarSvg($g);

    verdadeiro(str_contains($svg, '<svg xmlns'), 'faltou a tag svg');
    verdadeiro(str_contains($svg, 'viewBox="0 0 8 8"'), 'viewBox incorreto');
    // Borda suave: sem crispEdges, e um contorno da mesma cor para não abrir fresta entre cores.
    verdadeiro(!str_contains($svg, 'crispEdges'), 'crispEdges deixa a borda serrilhada');
    verdadeiro(str_contains($svg, 'stroke="#ff0000"'), 'faltou o contorno na mesma cor');
    igual(2, substr_count($svg, '<path'), 'deveria ter um path por cor');
    verdadeiro(str_contains($svg, 'fill="#ff0000"'), 'faltou a cor #ff0000');
    verdadeiro(str_contains($svg, 'fill="#00ff00"'), 'faltou a cor #00ff00');
});

teste('e: escapa HTML para exibição segura', function () {
    igual('&lt;b&gt;&amp;&quot;&lt;/b&gt;', e('<b>&"</b>'));
});

teste('moeda: formata no padrão brasileiro', function () {
    igual('R$ 10,50', moeda(10.5));
    igual('R$ 1.234,56', moeda('1234.56'));
});

teste('data_br: formata ISO para o padrão brasileiro', function () {
    igual('19/11/2026', data_br('2026-11-19'));
    igual('', data_br(null));
    igual('', data_br(''));
});

teste('cpf_cnpj: pontua 11 dígitos', function () {
    igual('529.982.247-25', cpf_cnpj('52998224725'));
});

teste('svg_data_uri: usa base64', function () {
    verdadeiro(str_starts_with(svg_data_uri('<svg></svg>'), 'data:image/svg+xml;base64,'));
});

teste('link_whatsapp: monta a URL wa.me', function () {
    verdadeiro(str_contains(link_whatsapp(), 'https://wa.me/'), 'faltou o wa.me');
});

// ------------------------------------------------------------------
// TutorialServico em fluxo, com banco e Gemini falsos (nada sai da máquina).
// ------------------------------------------------------------------

/** Bordado falso: devolve um bordado fixo e guarda o que seria salvo, sem tocar no banco. */
function bordadosFalsos(?string $tutorialSalvo): BordadoDados
{
    return new class ($tutorialSalvo) extends BordadoDados {
        public ?string $salvo = null;

        public function __construct(private ?string $tutorial)
        {
        }

        public function buscarParaTutorial(int $id): ?array
        {
            return ['id' => $id, 'titulo' => 'Rosa', 'categoria' => 'Toalha', 'cor_peca' => '#ffffff',
                'largura' => 40, 'altura' => 30, 'total_pontos' => 900, 'paleta' => ['#691818'],
                'tutorial_ia' => $this->tutorial];
        }

        public function salvarTutorial(int $id, string $texto): void
        {
            $this->salvo = $texto;
        }
    };
}

/** Gemini falso: manda os pedaços pedidos e, se $falhaNoFim, cai depois deles. */
function geminiFalso(array $pedacos, bool $falhaNoFim = false): GeminiServico
{
    return new class ($pedacos, $falhaNoFim) extends GeminiServico {
        public function __construct(private array $pedacos, private bool $falhaNoFim)
        {
        }

        public function gerarEmFluxo(string $prompt, callable $aoReceber): string
        {
            foreach ($this->pedacos as $pedaco) {
                $aoReceber($pedaco);
            }

            if ($this->falhaNoFim) {
                throw $this->pedacos === [] ? new RuntimeException('caiu') : new FluxoInterrompido('caiu no meio');
            }

            return implode('', $this->pedacos);
        }
    };
}

teste('tutorial em fluxo: repassa cada pedaço e salva o texto inteiro', function () {
    $dados = bordadosFalsos(null);
    $eventos = [];
    $r = (new TutorialServico($dados, geminiFalso(['MATERIAIS', ': linha'])))
        ->obterEmFluxo(1, false, function (string $tipo, array $d) use (&$eventos) { $eventos[] = [$tipo, $d['texto'] ?? null]; });

    igual('ia', $r['origem']);
    igual([['texto', 'MATERIAIS'], ['texto', ': linha']], $eventos);
    igual('MATERIAIS: linha', $dados->salvo);
});

teste('tutorial em fluxo: texto salvo sai inteiro, sem chamar a IA', function () {
    $eventos = [];
    $r = (new TutorialServico(bordadosFalsos('já existe'), geminiFalso([], true)))
        ->obterEmFluxo(1, false, function (string $tipo, array $d) use (&$eventos) { $eventos[] = $tipo; });

    igual('cache', $r['origem']);
    igual(['texto'], $eventos);
});

teste('tutorial em fluxo: queda no meio manda recomecar e o texto reserva', function () {
    $dados = bordadosFalsos(null);
    $eventos = [];
    $r = (new TutorialServico($dados, geminiFalso(['MATER'], true)))
        ->obterEmFluxo(1, false, function (string $tipo, array $d) use (&$eventos) { $eventos[] = $tipo; });

    igual('reserva', $r['origem']);
    igual(['texto', 'recomecar', 'texto'], $eventos);
    igual(null, $dados->salvo, 'texto reserva não pode ser salvo');
});

teste('tutorial em fluxo: falha antes de qualquer pedaço não manda recomecar', function () {
    $eventos = [];
    $r = (new TutorialServico(bordadosFalsos(null), geminiFalso([], true)))
        ->obterEmFluxo(1, false, function (string $tipo, array $d) use (&$eventos) { $eventos[] = $tipo; });

    igual('reserva', $r['origem']);
    igual(['texto'], $eventos);
});

echo "\n{$total} testes, {$falhas} falhas\n";
exit($falhas === 0 ? 0 : 1);
