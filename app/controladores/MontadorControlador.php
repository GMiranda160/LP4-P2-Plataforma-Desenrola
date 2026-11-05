<?php
/**
 * Camada: Controlador. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: abrir o montador e salvar bordados.
 * Veja INSTRUCOES_GUILHERME.md, seção 7.
 */
class MontadorControlador
{
    /** TODO(Guilherme): mostrar montador/index com matrizes, categorias e modo. Os modos matriz e produto exigem gerente. */
    public function index(): void
    {
        throw new RuntimeException('Não implementado: MontadorControlador::index');
    }

    /** TODO(Guilherme): chamar MontadorServico. Hub vai para /hub/bordado?id=, matriz vai para /gerente/matrizes e produto vai para /gerente/produtos/form. */
    public function salvar(): void
    {
        throw new RuntimeException('Não implementado: MontadorControlador::salvar');
    }
}
