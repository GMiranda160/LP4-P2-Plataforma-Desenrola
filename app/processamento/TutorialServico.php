<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: obter o tutorial uma só vez por bordado.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */
class TutorialServico
{
    /** TODO(Guilherme): devolver o texto salvo, ou chamar o Gemini e salvar. Em falha, devolver o texto reserva sem salvar. */
    public function obter(int $bordadoId): array
    {
        throw new RuntimeException('Não implementado: TutorialServico::obter');
    }

    /** TODO(Guilherme): montar o prompt com os dados delimitados, tratados só como descrição. */
    public function montarPrompt(array $bordado): string
    {
        throw new RuntimeException('Não implementado: TutorialServico::montarPrompt');
    }

    /** TODO(Guilherme): montar um tutorial simples com os dados do bordado. */
    public function textoReserva(array $bordado): string
    {
        throw new RuntimeException('Não implementado: TutorialServico::textoReserva');
    }
}
