/**
 * Entrada principal dos scripts da plataforma.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Inicializa o menu móvel, as animações de rolagem e a vitrine da home.
 */

import { queryAll } from './utils/dom.js';
import { initHeader } from './components/header.js';
import { initScrollReveal } from './utils/scroll-reveal.js';
import { initShowcase } from './components/showcase.js';

initHeader();
initScrollReveal();
queryAll('[data-showcase]').forEach(initShowcase);
