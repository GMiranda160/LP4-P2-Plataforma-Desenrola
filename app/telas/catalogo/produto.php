<?php
/**
 * Tela: página do produto
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /produto?id=
 * Recebe: $titulo, $pagina, $produto
 * Campos de $produto: id, nome, descricao, preco_base, categoria e dados da peça
 * Responsabilidade: peça, dados do produto e botão Solicitar orçamento (link para o WhatsApp).
 * Sem foto própria, a área da peça renderiza o mockup; com foto (produtos.foto_path), a foto cobre a moldura.
 * Acima do conteúdo, "Voltar para o catálogo": o alvo é o próprio /catalogo com os filtros
 * do referer (ex.: ?categoria=toalhas), se veio de lá; senão, cai direto em /catalogo.
 * O título (h1) vive dentro do card; acima dele, a categoria do produto com ícone.
 * A descrição fica em uma caixa própria.
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */

$voltarCatalogo = '/catalogo';
$referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
$partes = $referer !== '' ? parse_url($referer) : false;

if (is_array($partes)
    && (($partes['path'] ?? '') === '/catalogo')
    && strcasecmp((string) ($partes['host'] ?? ''), (string) ($_SERVER['HTTP_HOST'] ?? '')) === 0) {
    $voltarCatalogo = '/catalogo' . (isset($partes['query']) && $partes['query'] !== '' ? '?' . $partes['query'] : '');
}
?>
<section class="section">
<div class="container">
<a class="product-voltar" href="<?= e($voltarCatalogo) ?>">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
  Voltar para o catálogo
</a>

<article class="product-detail">
  <div class="product-detail__media">
    <?php Visao::parcial('peca_bordada', ['item' => $produto]); ?>
  </div>

  <div class="product-detail__panel">
    <p class="product-detail__eyebrow">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      <?= e($produto['categoria'] ?? 'Bordado') ?>
    </p>
    <h1 class="product-detail__name"><?= e($pagina ?? $produto['nome']) ?></h1>
    <p class="product-detail__price"><span class="product-detail__price-label">A partir de</span> <?= moeda($produto['preco_base']) ?></p>
    <?php if (trim((string) ($produto['descricao'] ?? '')) !== ''): ?>
      <div class="product-detail__descricao">
        <span class="product-detail__descricao-label">Descrição</span>
        <p><?= e($produto['descricao']) ?></p>
      </div>
    <?php endif; ?>

    <?php
    // Prévia de cor só faz sentido onde a cor é aplicável: mockup em SVG ou foto tingível da peça.
    // Foto real do produto (ou foto enviada pelo cliente) não aceita tinta.
    $tingir = !empty($produto['foto_tingir']) && !empty($produto['foto_peca']);
    $temMockup = empty($produto['foto_peca']) && empty($produto['foto_produto'] ?? '');
    $mostrarSeletorCor = ($temMockup || $tingir) && empty($produto['foto_produto'] ?? '');
    $corAtual = strtolower((string) ($produto['cor_peca'] ?? '#691818'));
    $cores = [
        ['#f3ded9', 'Rosé claro'], ['#ffffff', 'Off-white'], ['#2e2321', 'Preto'],
        ['#691818', 'Vinho'], ['#3f5a45', 'Verde floresta'], ['#2f4a6b', 'Azul marinho'],
        ['#d9c9b8', 'Bege'], ['#b7a6d9', 'Lilás'],
    ];
    $corNaPaleta = in_array($corAtual, array_column($cores, 0), true);
    ?>
    <?php if ($mostrarSeletorCor): ?>
      <fieldset class="product-detail__cores">
        <legend class="product-detail__cores-titulo">Cor da peça <span>prévia ao vivo</span></legend>
        <div class="product-detail__swatches" role="group" aria-label="Escolha a cor da peça">
          <?php foreach ($cores as [$hex, $nomeCor]): ?>
            <?php $ativa = $corNaPaleta && strtolower($hex) === $corAtual; ?>
            <button type="button" class="product-detail__swatch<?= $ativa ? ' is-active' : '' ?>"
                    data-cor="<?= e($hex) ?>" data-nome="<?= e($nomeCor) ?>"
                    style="--swatch: <?= e($hex) ?>" aria-pressed="<?= $ativa ? 'true' : 'false' ?>"
                    aria-label="<?= e($nomeCor) ?>" title="<?= e($nomeCor) ?>"></button>
          <?php endforeach; ?>
          <label class="product-detail__cor-livre" title="Outra cor">
            <input type="color" class="product-detail__cor-input" value="<?= e($corAtual) ?>" aria-label="Outra cor">
            <span class="visually-hidden">Outra cor</span>
          </label>
        </div>
      </fieldset>
    <?php endif; ?>

    <a class="btn btn--primary btn--block product-detail__whatsapp" target="_blank" rel="noopener noreferrer"
       href="<?= e(link_whatsapp("Olá! Quero um orçamento de: {$produto['nome']}")) ?>">
      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
      Solicitar orçamento
    </a>
  </div>
</article>

<?php if ($mostrarSeletorCor): ?>
<script>
// Prévia de cor do produto: troca o currentColor da peça (mockup SVG) e o fundo da tinta
// (foto base tingida), mantendo o bordado. A cor escolhida vai junto na mensagem do WhatsApp.
(function () {
  var peca = document.querySelector('.product-detail__media .peca');
  var tinta = peca ? peca.querySelector('.peca__tinta') : null;
  var botaoWhatsApp = document.querySelector('.product-detail__whatsapp');
  var nomeProduto = <?= json_encode((string) ($produto['nome'] ?? 'produto'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  var botoes = Array.prototype.slice.call(document.querySelectorAll('.product-detail__swatch'));
  var livre = document.querySelector('.product-detail__cor-input');

  if (!peca) return;

  function aplicar(cor, nome) {
    peca.style.color = cor;
    if (tinta) tinta.style.backgroundColor = cor;

    botoes.forEach(function (botao) {
      var ativo = botao.getAttribute('data-cor') === cor;
      botao.classList.toggle('is-active', ativo);
      botao.setAttribute('aria-pressed', ativo ? 'true' : 'false');
    });

    if (botaoWhatsApp) {
      var base = botaoWhatsApp.getAttribute('href').split('?')[0];
      var texto = 'Olá! Quero um orçamento de: ' + nomeProduto + ' (cor: ' + nome + ')';
      botaoWhatsApp.setAttribute('href', base + '?text=' + encodeURIComponent(texto));
    }
  }

  botoes.forEach(function (botao) {
    botao.addEventListener('click', function () {
      aplicar(botao.getAttribute('data-cor'), botao.getAttribute('data-nome'));
    });
  });

  if (livre) {
    livre.addEventListener('input', function () {
      aplicar(livre.value, 'personalizada ' + livre.value);
    });
  }
})();
</script>
<?php endif; ?>
</div>
</section>