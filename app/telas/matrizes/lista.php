<?php
/**
 * Tela: lista de matrizes
 * Dono: Arthur. Só Arthur altera este arquivo.
 * Rota: GET /gerente/matrizes
 * Recebe: $titulo, $matrizes, $matrizesUsuarios, $limiteUsuarios
 * Campos de $matrizes (padrão): id, nome, categoria, largura, altura, total_pontos, tempo_medio_min, svg
 * Campos de $matrizesUsuarios: os mesmos, mais autor (pode ser null) e criado_em
 * Responsabilidade: listar matrizes em dois grupos.
 *   1. Padrão do ateliê: miniatura, nome, categoria, tamanho, pontos, tempo, editar e excluir.
 *   2. Criadas por usuários: miniatura, nome, autor, tamanho, pontos, data, editar e excluir (moderação).
 * Veja INSTRUCOES_ARTHUR.md, seção 4.
 */
?>
<div class="page-head">
  <h1><?= e($titulo) ?></h1>
  <a class="btn btn--primary btn--sm" href="/montador?modo=matriz">Nova matriz</a>
</div>

<section class="cadastro-grupo" aria-labelledby="grupo-padrao">
  <h2 class="cadastro-grupo__titulo" id="grupo-padrao">
    Padrão do ateliê <span class="badge"><?= count($matrizes) ?></span>
  </h2>

  <?php if (!$matrizes): ?>
    <p class="empty-state">Nenhuma matriz padrão cadastrada.</p>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table table--centrada">
        <thead>
          <tr>
            <th>Miniatura</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Tamanho</th>
            <th>Pontos</th>
            <th>Tempo</th>
            <th class="table__acao-head">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($matrizes as $m): ?>
            <tr>
              <td>
                <img src="<?= e(svg_data_uri($m['svg'])) ?>" alt="<?= e($m['nome']) ?>">
              </td>
              <td><?= e($m['nome']) ?></td>
              <td><?= e($m['categoria']) ?></td>
              <td><?= e($m['largura']) ?> x <?= e($m['altura']) ?></td>
              <td><?= e($m['total_pontos']) ?></td>
              <td><?= e($m['tempo_medio_min']) ?> min</td>
              <td class="table__actions table__actions--centro">
                <a class="btn btn--ghost btn--icon" href="/gerente/matrizes/form?id=<?= (int) $m['id'] ?>" aria-label="Editar matriz" title="Editar">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.85 0 0 1 4 4L8 20l-5 1 1-5L17 3Z"/></svg>
                </a>
                <form method="post" action="/gerente/matrizes/excluir"
                      onsubmit="return confirm('Tem certeza que deseja excluir esta matriz?')">
                  <?= Csrf::campo() ?>
                  <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                  <button class="btn btn--ghost btn--icon" type="submit" aria-label="Excluir matriz" title="Excluir">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<section class="cadastro-grupo" aria-labelledby="grupo-usuarios">
  <h2 class="cadastro-grupo__titulo" id="grupo-usuarios">
    Criadas por usuários <span class="badge"><?= count($matrizesUsuarios) ?></span>
  </h2>
  <p class="cadastro-grupo__nota">
    Mostra as <?= (int) $limiteUsuarios ?> mais recentes. Matriz usada em um bordado não pode ser
    excluída; para tirar um post do ar, oculte-o no Hub.
  </p>

  <?php if (!$matrizesUsuarios): ?>
    <p class="empty-state">Nenhum usuário criou matrizes ainda.</p>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table table--centrada">
        <thead>
          <tr>
            <th>Miniatura</th>
            <th>Nome</th>
            <th>Autor</th>
            <th>Tamanho</th>
            <th>Pontos</th>
            <th>Criada em</th>
            <th class="table__acao-head">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($matrizesUsuarios as $m): ?>
            <tr>
              <td>
                <img src="<?= e(svg_data_uri($m['svg'])) ?>" alt="<?= e($m['nome']) ?>">
              </td>
              <td><?= e($m['nome']) ?></td>
              <td><?= e($m['autor'] ?? 'Conta excluída') ?></td>
              <td><?= e($m['largura']) ?> x <?= e($m['altura']) ?></td>
              <td><?= e($m['total_pontos']) ?></td>
              <td><?= e(data_br(substr((string) $m['criado_em'], 0, 10))) ?></td>
              <td class="table__actions table__actions--centro">
                <a class="btn btn--ghost btn--icon" href="/gerente/matrizes/form?id=<?= (int) $m['id'] ?>" aria-label="Editar matriz" title="Editar">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.85 0 0 1 4 4L8 20l-5 1 1-5L17 3Z"/></svg>
                </a>
                <form method="post" action="/gerente/matrizes/excluir"
                      onsubmit="return confirm('Tem certeza que deseja excluir esta matriz?')">
                  <?= Csrf::campo() ?>
                  <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                  <button class="btn btn--ghost btn--icon" type="submit" aria-label="Excluir matriz" title="Excluir">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
