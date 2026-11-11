<?php
/**
 * Tela: home
 * Dono: Guilherme. Só Guilherme altera este arquivo.
 * Rota: GET /
 * Recebe: $titulo, $destaques, $maisCurtidos, $categorias
 *
 * Migrada de index.html: hero, categorias, vitrine com dados do banco
 * (vazia graciosamente se não houver produtos), sobre, chamada para o
 * WhatsApp e a seção Direto do Hub. Marca Desenrola, sem cidade.
 */

/** Caminho absoluto de um mockup, para embutir o SVG inline na tela. */
function mockup_svg(string $arquivo): string
{
    $arquivo = basename($arquivo);
    $caminho = RAIZ . '/public/assets/img/mockups/' . $arquivo;

    return is_file($caminho) ? (string) file_get_contents($caminho) : '';
}
?>
<!-- Hero ------------------------------------------------------------ -->
<section class="hero" aria-labelledby="hero-title">
  <div class="container hero__grid">
    <div class="hero__content" data-reveal>
      <p class="eyebrow">Bordados personalizados</p>
      <h1 id="hero-title">
        Detalhes bordados que transformam o <em>seu</em> dia a dia
      </h1>
      <p class="lead">
        Toalhas, bolsas, aventais e peças personalizadas produzidas sob encomenda.
        Escolha seu modelo no mostruário ou monte o seu bordado na plataforma.
      </p>

      <div class="hero__actions">
        <a class="btn btn--primary" href="#produtos">Ver o mostruário</a>
        <a class="btn btn--ghost" href="/catalogo">Abrir o catálogo</a>
      </div>

      <ul class="hero__points" role="list">
        <li>Personalizados</li>
        <li>Sob encomenda</li>
        <li>Você pede de onde estiver</li>
      </ul>
    </div>

    <div class="hero__media" data-reveal data-reveal-delay="140">
      <img
        src="/assets/img/banners/hero-banner.svg"
        alt="Ilustração de um ramo floral bordado sobre tecido claro"
        width="1200"
        height="1500"
      />
      <span class="hero__seal" aria-hidden="true">Bordado<br />personalizado</span>
    </div>
  </div>
</section>

<!-- Categorias ------------------------------------------------------- -->
<?php if (!empty($categorias)): ?>
<section class="section" id="categorias" aria-labelledby="categorias-title">
  <div class="container">
    <div class="section__head" data-reveal>
      <p class="eyebrow">Explore por categoria</p>
      <h2 id="categorias-title">Escolha por onde começar</h2>
      <p class="lead">
        Da mesa posta ao enxoval, cada linha de produtos pode ser adaptada às suas cores e iniciais.
      </p>
    </div>

    <ul class="category-list" role="list">
      <?php foreach ($categorias as $categoria): ?>
        <li>
          <a class="category-card" href="/catalogo?categoria=<?= e($categoria['slug']) ?>">
            <span style="display:block; aspect-ratio: 3 / 4; color: var(--color-wine-500); padding: 1.5rem;" aria-hidden="true">
              <?= mockup_svg((string) $categoria['mockup']) ?>
            </span>
            <span class="category-card__overlay">
              <span class="category-card__name"><?= e($categoria['nome']) ?></span>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php endif; ?>

<!-- Vitrine ---------------------------------------------------------- -->
<section class="section section--tinted" id="produtos" aria-labelledby="produtos-title">
  <div class="container">
    <div class="section__head" data-reveal>
      <p class="eyebrow">Vitrine</p>
      <h2 id="produtos-title">Peças prontas para encomendar</h2>
      <p class="lead">
        Uma seleção das peças que estão saindo do ateliê. A vitrine desliza sozinha e pausa
        quando você passa o mouse. Para ver tudo, abra o catálogo completo.
      </p>
    </div>

    <?php if (empty($destaques)): ?>
      <p class="empty-state">Nenhuma peça em destaque ainda. Cadastre a primeira peça pela área da gerente.</p>
    <?php else: ?>
      <div class="showcase" data-showcase data-reveal>
        <button class="showcase__edge showcase__edge--left" type="button" data-showcase-prev aria-label="Anterior">&lsaquo;</button>
        <div class="showcase__viewport">
          <div class="showcase__track">
            <div class="showcase__group">
              <?php foreach ($destaques as $item): ?>
                <div class="showcase__item">
                  <article class="product-card">
                    <a class="product-card__media" href="/produto?id=<?= (int) $item['id'] ?>" tabindex="-1">
                      <?php Visao::parcial('peca_bordada', ['item' => $item]); ?>
                    </a>
                    <div class="product-card__body">
                      <h3 class="product-card__name"><a href="/produto?id=<?= (int) $item['id'] ?>"><?= e($item['nome']) ?></a></h3>
                      <p class="product-card__price"><span class="product-card__price-label">A partir de</span> <strong><?= moeda($item['preco_base']) ?></strong></p>
                    </div>
                  </article>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="showcase__group" aria-hidden="true">
              <?php foreach ($destaques as $item): ?>
                <div class="showcase__item">
                  <article class="product-card">
                    <span class="product-card__media">
                      <?php Visao::parcial('peca_bordada', ['item' => $item]); ?>
                    </span>
                    <div class="product-card__body">
                      <span class="product-card__name"><?= e($item['nome']) ?></span>
                      <p class="product-card__price"><span class="product-card__price-label">A partir de</span> <strong><?= moeda($item['preco_base']) ?></strong></p>
                    </div>
                  </article>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <button class="showcase__edge showcase__edge--right" type="button" data-showcase-next aria-label="Próximo">&rsaquo;</button>
        <div class="showcase__footer">
          <p class="showcase__hint">Arraste para o lado para ver mais</p>
          <button class="showcase__toggle" type="button" data-showcase-toggle aria-pressed="false">
            <span class="showcase__toggle-icon" aria-hidden="true"></span>
            <span>Pausar</span>
          </button>
          <a class="btn btn--ghost showcase__action" href="/catalogo">Ver catálogo completo</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Sobre / diferenciais ----------------------------------------------- -->
<section class="section" id="sobre" aria-labelledby="sobre-title">
  <div class="container">
    <div class="section__head" data-reveal>
      <p class="eyebrow">O ateliê</p>
      <h2 id="sobre-title">Bordado sob encomenda, no seu tempo</h2>
      <p class="lead">
        Cada peça é bordada sob encomenda e com atenção aos detalhes. Você escolhe o modelo
        e acompanha o pedido de perto.
      </p>
    </div>

    <div class="features">
      <article class="feature" data-reveal>
        <span class="feature__number" aria-hidden="true">01</span>
        <h3>Bordado personalizado</h3>
        <p>Nomes, iniciais e detalhes bordados do jeito que você escolher.</p>
      </article>

      <article class="feature" data-reveal data-reveal-delay="90">
        <span class="feature__number" aria-hidden="true">02</span>
        <h3>Sob encomenda</h3>
        <p>Cada peça é produzida depois da confirmação do pedido, sem estoque pronto.</p>
      </article>

      <article class="feature" data-reveal data-reveal-delay="180">
        <span class="feature__number" aria-hidden="true">03</span>
        <h3>Você pede de onde estiver</h3>
        <p>Combine o melhor formato de entrega com o ateliê pelo WhatsApp.</p>
      </article>
    </div>
  </div>
</section>

<!-- Direto do Hub ------------------------------------------------------ -->
<section class="section" id="hub" aria-labelledby="hub-title">
  <div class="container">
    <div class="section__head" data-reveal>
      <p class="eyebrow">Comunidade</p>
      <h2 id="hub-title">Direto do Hub</h2>
      <p class="lead">O que a comunidade está bordando agora.</p>
    </div>
    <?php if (empty($maisCurtidos)): ?>
      <p class="empty-state">O Hub ainda não tem posts públicos. Ative sua conta e publique a primeira peça.</p>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($maisCurtidos as $item): ?>
          <article class="product-card">
            <a class="product-card__media" href="/hub/bordado?id=<?= (int) $item['id'] ?>">
              <?php Visao::parcial('peca_bordada', ['item' => $item]); ?>
            </a>
            <div class="product-card__body">
              <span class="product-card__category"><?= e($item['categoria']) ?></span>
              <h3 class="product-card__name"><a href="/hub/bordado?id=<?= (int) $item['id'] ?>"><?= e($item['titulo']) ?></a></h3>
              <p class="product-card__price"><?= (int) $item['curtidas'] ?> curtidas · <?= (int) $item['comentarios'] ?> comentários</p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Chamada final -------------------------------------------------------- -->
<section class="section cta-band" id="personalizados" aria-labelledby="personalizados-title">
  <div class="container cta-band__inner">
    <div data-reveal>
      <p class="eyebrow">Personalizados</p>
      <h2 id="personalizados-title">Tem uma ideia? A gente borda.</h2>
      <p class="lead">
        Enxovais, presentes, peças para eventos e pedidos em quantidade. Conte o que você
        imaginou e receba um orçamento pelo WhatsApp.
      </p>
    </div>

    <a class="btn btn--primary" href="<?= e(link_whatsapp('Olá! Quero encomendar um bordado personalizado.')) ?>" target="_blank" rel="noopener noreferrer" data-reveal data-reveal-delay="150">
      Falar no WhatsApp
    </a>
  </div>
</section>
