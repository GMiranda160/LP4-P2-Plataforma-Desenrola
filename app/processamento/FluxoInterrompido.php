<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: sinalizar que o Gemini caiu no meio do fluxo, depois de já ter mandado
 * parte do texto. O TutorialServico usa isso para avisar a tela que ela precisa recomeçar.
 * Veja INSTRUCOES_GUILHERME.md, seção 8.
 */
class FluxoInterrompido extends RuntimeException
{
}
