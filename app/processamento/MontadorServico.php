<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: validar e salvar matriz e bordado.
 * Veja INSTRUCOES_GUILHERME.md, seção 7.
 */
class MontadorServico
{
    /** TODO(Guilherme): validar título, categoria, cor e posição. Com grade_json, validar e gerar SVG; com matriz_id, aceitar só padrão ou do autor. Usar transação, limitar a 20 bordados por hora e devolver destino e id. */
    public function salvar(array $campos, array $usuario): array
    {
        throw new RuntimeException('Não implementado: MontadorServico::salvar');
    }
}
