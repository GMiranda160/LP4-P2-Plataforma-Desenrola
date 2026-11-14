<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras dos produtos do mostruário. Sem SQL e sem HTML.
 */
class ProdutoServico
{
    /** Todos os produtos para a gerente, inclusive inativos. */
    public function todos(): array
    {
        return (new ProdutoDados())->listar(null, false);
    }

    /** Produtos ativos para o catálogo. Passe o slug da categoria para filtrar. */
    public function catalogo(?string $categoria = null): array
    {
        if ($categoria !== null) {
            $categoria = strtolower(trim($categoria));

            if (!preg_match('/^[a-z0-9-]{1,80}$/', $categoria)) {
                $categoria = null;
            }
        }

        return (new ProdutoDados())->listar($categoria);
    }

    /** Produtos em destaque, para a home. */
    public function destaques(int $limite = 6): array
    {
        return (new ProdutoDados())->destaques($limite);
    }

    /** Um produto ativo para a página pública, ou null. */
    public function mostrar(int $id): ?array
    {
        return (new ProdutoDados())->buscarAtivo($id);
    }

    /** Categorias para navegação e filtros. */
    public function categorias(): array
    {
        return (new ProdutoDados())->categorias();
    }

    /** Categorias com id e slug, para o montador derivar a peça da combinação. */
    public function categoriasIds(): array
    {
        return (new ProdutoDados())->categoriasIds();
    }

    /** Um produto pelo id, para a tela de edição. Null se não existir. */
    public function buscar(int $id): ?array
    {
        return (new ProdutoDados())->buscar($id);
    }

    /** Combinações de bordado disponíveis para virar produto. Inclui a do produto em edição, se houver. */
    public function combinacoes(?int $atualId = null): array
    {
        return (new ProdutoDados())->combinacoes($atualId ?? 0);
    }

    /**
     * Valida e grava (cria ou atualiza) um produto a partir do POST da tela de form.
     * Devolve ['erros' => [...], 'id' => int|null]. Bordado_id duplicado é convertido em erro amigável.
     */
    public function salvar(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        $bordadoId = (int) ($post['bordado_id'] ?? 0);
        $nome = trim((string) ($post['nome'] ?? ''));
        $descricao = trim((string) ($post['descricao'] ?? ''));
        $precoBruto = $this->numeroDeReais((string) ($post['preco_base'] ?? ''));
        $preco = is_numeric($precoBruto) ? (float) $precoBruto : 0;
        $erros = [];

        if ($bordadoId <= 0) {
            $erros[] = 'Escolha a combinação de bordado.';
        } elseif (!$this->combinacaoValida($bordadoId, $id)) {
            $erros[] = 'Combinação inválida ou já usada por outro produto.';
        }

        $tamanhoNome = function_exists('mb_strlen') ? mb_strlen($nome) : strlen($nome);

        if ($nome === '') {
            $erros[] = 'Informe o nome do produto.';
        } elseif ($tamanhoNome > 120) {
            $erros[] = 'O nome deve ter no máximo 120 caracteres.';
        }

        if ($preco <= 0) {
            $erros[] = 'O preço base deve ser maior que zero.';
        } elseif ($preco > 99999999) {
            $erros[] = 'Preço base inválido.';
        }

        if ($erros) {
            return ['erros' => $erros, 'id' => null];
        }

        $dados = [
            'bordado_id' => $bordadoId,
            'nome' => $nome,
            'descricao' => $descricao !== '' ? $descricao : null,
            'preco_base' => number_format($preco, 2, '.', ''),
            'destaque' => !empty($post['destaque']),
            'ativo' => !empty($post['ativo']),
        ];

        try {
            if ($id > 0) {
                (new ProdutoDados())->atualizar($id, $dados);

                return ['erros' => [], 'id' => $id];
            }

            $novoId = (new ProdutoDados())->inserir($dados);

            return ['erros' => [], 'id' => $novoId];
        } catch (PDOException $erro) {
            if (($erro->getCode() ?? '') === '23505') {
                return ['erros' => ['Essa combinação já está vinculada a outro produto.'], 'id' => null];
            }

            throw $erro;
        }
    }

    /** Exclui o produto. $id deve existir. */
    public function excluir(int $id): void
    {
        (new ProdutoDados())->excluir($id);
    }

    /** Inverte o ativo. Devolve o novo estado, ou null se não existe. */
    public function ativar(int $id): ?bool
    {
        return (new ProdutoDados())->alternarAtivo($id);
    }

    /** A combinação existe, é tipo='produto' e não está vinculada a outro produto (exceto o de edição). */
    private function combinacaoValida(int $bordadoId, int $produtoId): bool
    {
        $consulta = Conexao::pdo()->prepare(
            "SELECT 1
             FROM bordados b
             WHERE b.id = :id AND b.tipo = 'produto'
               AND NOT EXISTS (SELECT 1 FROM produtos p
                               WHERE p.bordado_id = b.id AND p.id <> :atual)
             LIMIT 1"
        );
        $consulta->bindValue(':id', $bordadoId, PDO::PARAM_INT);
        $consulta->bindValue(':atual', $produtoId, PDO::PARAM_INT);
        $consulta->execute();

        return (bool) $consulta->fetchColumn();
    }

    /**
     * Converte texto de dinheiro em número com ponto: "R$ 1.234,56" e "1234,56" viram "1234.56";
     * "1234.56" (vindo da máscara do navegador) passa igual.
     */
    private function numeroDeReais(string $texto): string
    {
        $limpo = preg_replace('/[^\d.,]/', '', $texto) ?? '';

        if (str_contains($limpo, ',')) {
            // Formato brasileiro: ponto é milhar e vírgula é decimal.
            $limpo = str_replace(['.', ','], ['', '.'], $limpo);
        }

        return $limpo;
    }
}
