/**
 * Entrada principal dos scripts da plataforma.
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Inicializa o menu móvel, o menu do usuário, as animações de rolagem, a vitrine
 * da home e as máscaras de reais (data-moeda) e de CPF/CNPJ, telefone e CEP (data-mascara).
 */

import { queryAll } from './utils/dom.js';
import { initHeader, initMenuUsuario } from './components/header.js';
import { initScrollReveal } from './utils/scroll-reveal.js';
import { initShowcase } from './components/showcase.js';
import { initMontador } from './pages/montador.js';
import { initTutorial } from './pages/tutorial.js';
import { initProdutoForm } from './pages/produto-form.js';
import { initToasts } from './components/toast.js';
import { initGuia } from './components/guia.js';
import { initMoeda } from './components/moeda.js';
import { initMascara } from './components/mascara.js';

initHeader();
initMenuUsuario();
initScrollReveal();
queryAll('[data-showcase]').forEach(initShowcase);
queryAll('[data-montador]').forEach(initMontador);
queryAll('[data-tutorial]').forEach(initTutorial);
queryAll('[data-combinacao]').forEach(initProdutoForm);
initToasts();
initGuia();
queryAll('[data-moeda]').forEach(initMoeda);
queryAll('[data-mascara]').forEach(initMascara);
