<?php
/**
 * Camada: Processamento. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: validar insumos (tipo e unidade fixos, quantidade >= 0, custo > 0, unicidade de nome por tipo) e chamar InsumoDados.
 * Sem $_POST, sem sessão e sem HTML.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */

class InsumoServico
{
    private const TIPOS = ['tecido', 'linha', 'aviamento'];
    private const UNIDADES = ['metro', 'carretel', 'peca'];

    public function __construct(private InsumoDados $dados = new InsumoDados()) {}

    /** Insumos cujo nome contém a busca. Busca vazia devolve todos. */
    public function listar(string $busca = ''): array
    {
        return $this->dados->listar($busca);
    }

    /** Um insumo, ou null se não existir. */
    public function buscar(int $id): ?array
    {
        return $this->dados->buscar($id);
    }

    /**
     * Valida e grava o insumo: cadastra com id 0 e atualiza com id maior que 0.
     * Regras: tipo e unidade da lista fixa, nome obrigatório, quantidade >= 0, custo > 0,
     * cor no formato #rrggbb e nome único dentro do mesmo tipo.
     * Devolve a lista de erros. Lista vazia significa que salvou.
     */
    public function salvar(int $id, array $c): array
    {
        $d = [
            'tipo' => trim($c['tipo'] ?? ''),
            'nome' => trim($c['nome'] ?? ''),
            'cor_hex' => trim($c['cor_hex'] ?? ''),
            'quantidade' => (float) str_replace(',', '.', $c['quantidade'] ?? '0'),
            'unidade' => trim($c['unidade'] ?? ''),
            'custo_unitario' => (float) str_replace(',', '.', $c['custo_unitario'] ?? '0'),
            'fornecedor' => trim($c['fornecedor'] ?? ''),
            'estoque_minimo' => (float) str_replace(',', '.', $c['estoque_minimo'] ?? '0'),
        ];

        $erros = [];
        if (!in_array($d['tipo'], self::TIPOS, true)) $erros[] = 'Selecione o tipo.';
        if ($d['nome'] === '') $erros[] = 'Informe o nome.';
        if (!in_array($d['unidade'], self::UNIDADES, true)) $erros[] = 'Selecione a unidade.';
        if ($d['quantidade'] < 0) $erros[] = 'A quantidade não pode ser negativa.';
        if ($d['custo_unitario'] <= 0) $erros[] = 'O custo deve ser maior que zero.';
        if ($d['cor_hex'] !== '' && !preg_match('/^#[0-9a-f]{6}$/i', $d['cor_hex'])) {
            $erros[] = 'Cor inválida.';
        }
        if ($this->dados->existeNome($d['tipo'], $d['nome'], $id)) {
            $erros[] = 'Já existe um insumo com este nome neste tipo.';
        }

        if ($erros) return $erros;

        $id ? $this->dados->atualizar($id, $d) : $this->dados->inserir($d);
        return [];
    }

    /** Exclui o insumo. */
    public function excluir(int $id): void
    {
        $this->dados->excluir($id);
    }
}