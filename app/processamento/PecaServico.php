<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras das peças base (nome obrigatório, categoria existente, foto PNG, JPEG
 * ou WebP de até 2 MB) e envio da foto para o Storage. Peça não tem custo: custo é de insumo.
 * Sem $_POST, sem sessão e sem HTML: o controlador passa os campos e o arquivo enviado.
 */
class PecaServico
{
    private const NOME_MAX = 80;
    // O php.ini de produção do Docker limita o upload a 2 MB.
    private const FOTO_MAX = 2 * 1024 * 1024;
    private const TIPOS = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp'];
    // Fotos enviadas por esta tela ficam em pecas/. As originais (base/) nunca são apagadas daqui.
    private const PASTA = 'pecas';

    public function __construct(
        private PecaDados $dados = new PecaDados(),
        private StorageServico $storage = new StorageServico(),
    ) {
    }

    /** Todas as peças, para a lista da gerente. */
    public function listar(): array
    {
        return $this->dados->listar();
    }

    /** Peças ativas, para o montador. */
    public function ativas(): array
    {
        return $this->dados->listar(true);
    }

    /** Uma peça, ou null se não existir. */
    public function buscar(int $id): ?array
    {
        return $id > 0 ? $this->dados->buscar($id) : null;
    }

    /**
     * Valida e grava a peça: cadastra com id 0 e atualiza com id maior que 0.
     * $foto é o item de $_FILES (ou null). Na edição, sem foto nova, mantém a atual.
     * Devolve a lista de erros. Lista vazia significa que salvou.
     */
    public function salvar(int $id, array $campos, ?array $foto): array
    {
        $atual = $id > 0 ? $this->dados->buscar($id) : null;

        if ($id > 0 && $atual === null) {
            return ['Peça não encontrada.'];
        }

        $nome = trim((string) ($campos['nome'] ?? ''));
        $categoriaId = (int) ($campos['categoria_id'] ?? 0);
        $erros = [];

        if ($nome === '') {
            $erros[] = 'Informe o nome.';
        } elseif (mb_strlen($nome) > self::NOME_MAX) {
            $erros[] = 'O nome pode ter no máximo ' . self::NOME_MAX . ' caracteres.';
        }

        if ($categoriaId <= 0 || !(new ProdutoDados())->existe($categoriaId)) {
            $erros[] = 'Escolha a categoria.';
        }

        $temFoto = $foto !== null && ($foto['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        $extensao = null;

        if ($temFoto) {
            [$extensao, $erroFoto] = $this->conferirFoto($foto);
            if ($erroFoto !== null) {
                $erros[] = $erroFoto;
            }
        } elseif ($atual === null) {
            $erros[] = 'Envie a foto da peça.';
        }

        if ($erros) {
            return $erros;
        }

        $caminho = $atual['imagem_path'] ?? '';

        if ($temFoto) {
            $caminho = StorageServico::novoCaminho(self::PASTA, (string) $extensao);

            try {
                $this->storage->enviar($caminho, (string) file_get_contents($foto['tmp_name']), (string) $this->tipoReal($foto));
            } catch (RuntimeException $erro) {
                error_log($erro->getMessage());

                return ['Não consegui guardar a foto agora. Tente de novo em instantes.'];
            }
        }

        $dados = [
            'nome' => $nome,
            'categoria_id' => $categoriaId,
            'imagem_path' => $caminho,
            'ativo' => !empty($campos['ativo']),
        ];

        try {
            $id > 0 ? $this->dados->atualizar($id, $dados) : $this->dados->inserir($dados);
        } catch (Throwable $erro) {
            if ($temFoto) {
                $this->storage->remover($caminho);
            }
            throw $erro;
        }

        // Foto trocada: a antiga sai do bucket.
        if ($temFoto && $atual !== null) {
            $this->removerFoto((string) $atual['imagem_path']);
        }

        return [];
    }

    /** Exclui a peça e a foto dela. Devolve false se a peça não existia. */
    public function excluir(int $id): bool
    {
        $caminho = $id > 0 ? $this->dados->excluir($id) : null;

        if ($caminho === null) {
            return false;
        }

        $this->removerFoto($caminho);

        return true;
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

    private function removerFoto(string $caminho): void
    {
        if (str_starts_with($caminho, self::PASTA . '/')) {
            $this->storage->remover($caminho);
        }
    }
}
