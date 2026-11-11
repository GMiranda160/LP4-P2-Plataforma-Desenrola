<?php
/*
 * ClienteServico.php
 * Camada de processamento dos clientes.
 */

class ClienteServico
{
    public function __construct(private ClienteDados $dados = new ClienteDados()) {}

    public function listar(string $busca = ''): array
    {
        return $this->dados->listar($busca);
    }

    public function buscar(int $id): ?array
    {
        return $this->dados->buscar($id);
    }

    /** Devolve a lista de erros. Lista vazia significa que salvou. */
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

    public function excluir(int $id): void
    {
        $this->dados->excluir($id);
    }
}