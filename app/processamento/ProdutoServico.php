<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras dos produtos do mostruário. Sem SQL e sem HTML.
 * Foto do produto: PNG, JPEG ou WebP de até 2 MB, enviada para a pasta produtos/ do
 * bucket; o banco guarda só o caminho em produtos.foto_path. Sem foto, a página usa
 * a renderização da peça bordada.
 */
class ProdutoServico
{
    private const FOTO_MAX = 2 * 1024 * 1024;
    private const TIPOS = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp'];
    // Fotos de produto ficam em produtos/. As originais (base/ e pecas/) nunca são apagadas daqui.
    private const PASTA = 'produtos';

    public function __construct(private StorageServico $storage = new StorageServico()) {}

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

    /** Combinações e bordados de gerente disponíveis para virar produto. Inclui a combinação do produto em edição, se houver. */
    public function combinacoes(?int $atualId = null): array
    {
        return (new ProdutoDados())->combinacoes($atualId ?? 0);
    }

    /**
     * Valida e grava (cria ou atualiza) um produto a partir do POST da tela de form.
     * $foto é o item de $_FILES (ou null); $removerFoto apaga a foto atual (se houver) e
     * devolve o produto à renderização da peça. Devolve ['erros' => [...], 'id' => int|null].
     * Bordado_id duplicado é convertido em erro amigável.
     */
    public function salvar(array $post, ?array $foto = null, bool $removerFoto = false): array
    {
        $id = (int) ($post['id'] ?? 0);
        $bordadoId = (int) ($post['bordado_id'] ?? 0);
        $nome = trim((string) ($post['nome'] ?? ''));
        $descricao = trim((string) ($post['descricao'] ?? ''));
        $precoBruto = $this->numeroDeReais((string) ($post['preco_base'] ?? ''));
        $preco = is_numeric($precoBruto) ? (float) $precoBruto : 0;
        $erros = [];

        $atual = $id > 0 ? (new ProdutoDados())->buscar($id) : null;

        if ($id > 0 && $atual === null) {
            return ['erros' => ['Produto não encontrado.'], 'id' => null];
        }

        if ($bordadoId <= 0) {
            $erros[] = 'Escolha a combinação de bordado.';
        } elseif (!(new ProdutoDados())->combinacaoDisponivel($bordadoId, $id)) {
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

        $temFoto = $foto !== null && ($foto['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        $extensao = null;

        if ($temFoto) {
            [$extensao, $erroFoto] = $this->conferirFoto($foto);
            if ($erroFoto !== null) {
                $erros[] = $erroFoto;
            }
        }

        if ($erros) {
            return ['erros' => $erros, 'id' => null];
        }

        $fotoAtual = (string) ($atual['foto_path'] ?? '');
        // Sem foto nova e sem pedido de remoção, a atual se mantém.
        $caminho = $removerFoto ? '' : $fotoAtual;

        if ($temFoto) {
            $novo = StorageServico::novoCaminho(self::PASTA, (string) $extensao);

            try {
                $this->storage->enviar($novo, (string) file_get_contents($foto['tmp_name']), (string) $this->tipoReal($foto));
            } catch (RuntimeException $erro) {
                error_log($erro->getMessage());

                return ['erros' => ['Não consegui guardar a foto agora. Tente de novo em instantes.'], 'id' => null];
            }

            $caminho = $novo;
        }

        $dados = [
            'bordado_id' => $bordadoId,
            'nome' => $nome,
            'descricao' => $descricao !== '' ? $descricao : null,
            'preco_base' => number_format($preco, 2, '.', ''),
            'destaque' => !empty($post['destaque']),
            'ativo' => !empty($post['ativo']),
            'foto_path' => $caminho !== '' ? $caminho : null,
        ];

        try {
            if ($id > 0) {
                (new ProdutoDados())->atualizar($id, $dados);
                $novoId = $id;
            } else {
                $novoId = (new ProdutoDados())->inserir($dados);
            }
        } catch (PDOException $erro) {
            if ($temFoto) {
                $this->storage->remover($caminho);
            }

            if (($erro->getCode() ?? '') === '23505') {
                return ['erros' => ['Essa combinação já está vinculada a outro produto.'], 'id' => null];
            }

            throw $erro;
        }

        // Foto trocada ou removida: a antiga sai do bucket.
        if ($fotoAtual !== '' && $fotoAtual !== $caminho && str_starts_with($fotoAtual, self::PASTA . '/')) {
            $this->storage->remover($fotoAtual);
        }

        return ['erros' => [], 'id' => $novoId];
    }

    /** Exclui o produto e a foto dele (se houver). $id deve existir. */
    public function excluir(int $id): void
    {
        $caminho = (new ProdutoDados())->excluir($id);

        if ($caminho !== null && $caminho !== '' && str_starts_with($caminho, self::PASTA . '/')) {
            $this->storage->remover($caminho);
        }
    }

    /** Inverte o ativo. Devolve o novo estado, ou null se não existe. */
    public function ativar(int $id): ?bool
    {
        return (new ProdutoDados())->alternarAtivo($id);
    }

    /** Devolve [extensão, null] para foto válida ou [null, mensagem de erro]. */
    private function conferirFoto(array $foto): array
    {
        $erro = (int) ($foto['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($erro === UPLOAD_ERR_INI_SIZE || $erro === UPLOAD_ERR_FORM_SIZE || (int) ($foto['size'] ?? 0) > self::FOTO_MAX) {
            return [null, 'A foto deve ter no máximo 2 MB.'];
        }

        if ($erro !== UPLOAD_ERR_OK || !is_uploaded_file((string) ($foto['tmp_name'] ?? ''))) {
            return [null, 'Não recebi a foto. Tente enviar de novo.'];
        }

        $tipo = $this->tipoReal($foto);

        if (!isset(self::TIPOS[$tipo])) {
            return [null, 'A foto deve ser PNG, JPEG ou WebP.'];
        }

        return [self::TIPOS[$tipo], null];
    }

    /** Tipo do arquivo lido do conteúdo, não do nome nem do que o navegador disse. */
    private function tipoReal(array $foto): ?string
    {
        $info = @getimagesize((string) ($foto['tmp_name'] ?? ''));

        return is_array($info) ? (string) ($info['mime'] ?? '') : null;
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
