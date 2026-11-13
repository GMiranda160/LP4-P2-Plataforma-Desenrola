<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: regras de matrizes (nome obrigatório, tempo médio > 0, não excluir matriz em uso)
 * e chamar MatrizDados. Sem $_POST, sem sessão e sem HTML.
 * Veja INSTRUCOES_GUILHERME.md, seção 14.
 */
class MatrizServico
{
    private const NOME_MAX = 80;
    private const CATEGORIA_MAX = 40;
    private const DESCRICAO_MAX = 500;

    public function __construct(private MatrizDados $dados = new MatrizDados()) {}

    /** Matrizes padrão do ateliê, para a gestão da gerente e para o montador. */
    public function listarPadrao(): array
    {
        return $this->dados->listarPadrao();
    }

    /** Matrizes criadas por usuários, para moderação da gerente. Limitada às mais recentes. */
    public function listarDeUsuarios(int $limite = 50): array
    {
        return $this->dados->listarDeUsuarios($limite);
    }

    /** Uma matriz, ou null se não existir. */
    public function buscar(int $id): ?array
    {
        return $id > 0 ? $this->dados->buscar($id) : null;
    }

    /**
     * Atualiza nome, descrição, categoria e tempo médio de uma matriz existente.
     * Matriz nova não passa por aqui: ela nasce no montador, com a grade.
     * Só matrizes padrão são editáveis: nome e descrição de matriz de usuário pertencem ao autor.
     * Devolve a lista de erros. Lista vazia significa que salvou.
     */
    public function salvarDados(int $id, array $campos): array
    {
        $matriz = $id > 0 ? $this->dados->buscar($id) : null;

        if ($matriz === null) {
            return ['Matriz não encontrada.'];
        }
        if (!$matriz['padrao']) {
            return ['Matrizes criadas por usuários não podem ser editadas, apenas excluídas.'];
        }

        $tempo = filter_var(trim((string) ($campos['tempo_medio_min'] ?? '')), FILTER_VALIDATE_INT);
        $d = [
            'nome' => trim((string) ($campos['nome'] ?? '')),
            'descricao' => trim((string) ($campos['descricao'] ?? '')),
            'categoria' => trim((string) ($campos['categoria'] ?? '')),
            'tempo_medio_min' => $tempo === false ? 0 : $tempo,
        ];

        $erros = [];
        if ($d['nome'] === '') {
            $erros[] = 'Informe o nome.';
        } elseif (mb_strlen($d['nome']) > self::NOME_MAX) {
            $erros[] = 'O nome pode ter no máximo ' . self::NOME_MAX . ' caracteres.';
        }
        if (mb_strlen($d['categoria']) > self::CATEGORIA_MAX) {
            $erros[] = 'A categoria pode ter no máximo ' . self::CATEGORIA_MAX . ' caracteres.';
        }
        if (mb_strlen($d['descricao']) > self::DESCRICAO_MAX) {
            $erros[] = 'A descrição pode ter no máximo ' . self::DESCRICAO_MAX . ' caracteres.';
        }
        if ($d['tempo_medio_min'] <= 0) {
            $erros[] = 'O tempo médio deve ser um número inteiro maior que zero.';
        }

        if ($erros) {
            return $erros;
        }

        // Mesmos padrões do banco: categoria vazia vira Geral e descrição vazia vira NULL.
        $d['categoria'] = $d['categoria'] === '' ? 'Geral' : $d['categoria'];
        $d['descricao'] = $d['descricao'] === '' ? null : $d['descricao'];

        $this->dados->atualizarDados($id, $d);

        return [];
    }

    /**
     * Exclui a matriz, se nenhum bordado a usar. Devolve true se excluiu.
     * O banco também protege (ON DELETE RESTRICT): se um bordado for criado entre a checagem
     * e o DELETE, a exceção de chave estrangeira (23503) vira um false aqui.
     */
    public function excluir(int $id): bool
    {
        if ($id <= 0 || $this->dados->emUso($id)) {
            return false;
        }

        try {
            $this->dados->excluir($id);
        } catch (PDOException $e) {
            if ($e->getCode() === '23503') {
                return false;
            }
            throw $e;
        }

        return true;
    }
}
