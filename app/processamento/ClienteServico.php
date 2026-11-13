<?php
/**
 * Camada: Processamento. Dono: Arthur. Só Arthur altera este arquivo.
 * Responsabilidade: validar clientes (nome, CPF/CNPJ de 11 ou 14 dígitos, e-mail, telefone e unicidade de CPF/CNPJ) e chamar ClienteDados.
 * Sem $_POST, sem sessão e sem HTML.
 * Veja INSTRUCOES_ARTHUR.md, seção 6.
 */

class ClienteServico
{
    public function __construct(private ClienteDados $dados = new ClienteDados()) {}

    /** Clientes cujo nome ou CPF/CNPJ contém a busca. Busca vazia devolve todos. */
    public function listar(string $busca = ''): array
    {
        return $this->dados->listar($busca);
    }

    /** Um cliente, ou null se não existir. */
    public function buscar(int $id): ?array
    {
        return $this->dados->buscar($id);
    }

    /**
     * Valida e grava o cliente: cadastra com id 0 e atualiza com id maior que 0.
     * CPF/CNPJ e CEP são gravados só com dígitos. Regras: nome, e-mail válido e telefone
     * obrigatórios; CPF com 11 dígitos ou CNPJ com 14; CPF/CNPJ único.
     * Devolve a lista de erros. Lista vazia significa que salvou.
     */
    public function salvar(int $id, array $c): array
    {
        $d = [
            'nome' => trim($c['nome'] ?? ''),
            'cpf_cnpj' => preg_replace('/\D/', '', $c['cpf_cnpj'] ?? ''),
            'email' => trim($c['email'] ?? ''),
            'telefone' => trim($c['telefone'] ?? ''),
            'endereco' => trim($c['endereco'] ?? ''),
            'cep' => preg_replace('/\D/', '', $c['cep'] ?? ''),
        ];

        $erros = [];
        if ($d['nome'] === '') $erros[] = 'Informe o nome.';

        $tamanhoCpf = strlen($d['cpf_cnpj']);
        if ($tamanhoCpf === 0) {
            $erros[] = 'Informe o CPF ou CNPJ.';
        } elseif ($tamanhoCpf !== 11 && $tamanhoCpf !== 14) {
            $erros[] = 'CPF deve ter 11 dígitos e CNPJ deve ter 14.';
        }

        if ($d['email'] === '') {
            $erros[] = 'Informe o e-mail.';
        } elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido.';
        }

        if ($d['telefone'] === '') $erros[] = 'Informe o telefone.';

        if (($tamanhoCpf === 11 || $tamanhoCpf === 14)
            && $this->dados->existeCpfCnpj($d['cpf_cnpj'], $id)) {
            $erros[] = 'Já existe um cliente com este CPF/CNPJ.';
        }

        if ($erros) return $erros;

        $id ? $this->dados->atualizar($id, $d) : $this->dados->inserir($d);
        return [];
    }

    /** Exclui o cliente. */
    public function excluir(int $id): void
    {
        $this->dados->excluir($id);
    }
}