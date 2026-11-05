<?php
/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: chamar a API do Gemini por cURL.
 * A chave fica só no servidor. Usar GEMINI_API_KEY e GEMINI_MODEL.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */
class GeminiServico
{
    /** TODO(Guilherme): enviar a generateContent com cabeçalho x-goog-api-key, timeout de 25 segundos, e lançar RuntimeException se falhar. */
    public function gerar(string $prompt): string
    {
        throw new RuntimeException('Não implementado: GeminiServico::gerar');
    }
}
