<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: encontrar a rota e chamar o controlador.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Roteador
{
    /** TODO(Guilherme): guardar a lista de rotas. */
    public function __construct(array $rotas)
    {
        throw new RuntimeException('Não implementado: Roteador::__construct');
    }

    /** TODO(Guilherme): comparar método e caminho, validar CSRF em POST pelo campo _csrf ou cabeçalho X-CSRF-Token, conferir o acesso e chamar o controlador. Login: redirecionar para /login, ou 401 se Accept pedir JSON. Gerente: mostrar 403. Sem rota: mostrar 404. */
    public function despachar(): void
    {
        throw new RuntimeException('Não implementado: Roteador::despachar');
    }
}
