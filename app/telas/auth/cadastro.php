<?php
/**
 * Tela: cadastro de usuário
 * Dono: Eduardo. Guilherme montou esta tela como exemplo de referência.
 * Rota: GET /cadastro
 * Recebe: $titulo, $perguntas, $velhos
 * Envia POST para /cadastro.
 * Campos: nome, usuario, senha, confirmar_senha, pergunta_seguranca, resposta.
 *
 * Exemplo de tela para o Eduardo: o campo usuario recebe o prefixo do e-mail,
 * com o sufixo @example.com ao lado (classe .input-group). O e-mail completo
 * é montado no servidor. Use autocomplete correto em cada campo.
 */

/** Devolve o valor velho de um campo, já escapado para HTML. */
function velho(array $velhos, string $campo, string $padrao = ''): string
{
    return e((string) ($velhos[$campo] ?? $padrao));
}
?>
<section class="section">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow">Junte-se à comunidade</p>
      <h1><?= e($titulo) ?></h1>
      <p class="lead">Crie sua conta para publicar no Hub, curtir e montar bordados.</p>
    </div>

    <div class="auth-container">
      <form class="form" method="post" action="/cadastro">
        <?= Csrf::campo() ?>

        <div class="form__grid">
          <div class="form__field">
            <label for="nome">Nome completo</label>
            <input class="text-field" id="nome" name="nome" value="<?= velho($velhos, 'nome') ?>"
                   autocomplete="name" required />
          </div>
        
          <div class="form__field">
            <label for="usuario">Usuário</label>
            <div class="input-group">
              <input class="text-field" id="usuario" name="usuario" value="<?= velho($velhos, 'usuario') ?>"
                     pattern="[A-Za-z0-9._-]{3,30}" title="Use de 3 a 30 caracteres: letras, números, ponto, hífen ou sublinhado."
                     autocomplete="username" required />
              <span class="input-group__suffix" aria-hidden="true">@example.com</span>
            </div>
            <p class="form-hint">Sua conta será criada como usuario@example.com.</p>
          </div>
        </div>

        <div class="form__grid">
          <div class="form__field">
            <label for="senha">Senha</label>
            <input class="text-field" type="password" id="senha" name="senha" minlength="6"
                   autocomplete="new-password" required />
          </div>

          <div class="form__field">
            <label for="confirmar_senha">Repita a senha</label>
            <input class="text-field" type="password" id="confirmar_senha" name="confirmar_senha" minlength="6"
                   autocomplete="new-password" required />
          </div>
        </div>

        <div class="form__grid">
          <div class="form__field">
            <label for="pergunta_seguranca">Pergunta de segurança</label>
            <select class="text-field" id="pergunta_seguranca" name="pergunta_seguranca" required>
              <option value="">Selecione</option>
              <?php foreach ($perguntas as $pergunta): ?>
                <option value="<?= e($pergunta) ?>" <?= ($velhos['pergunta_seguranca'] ?? '') === $pergunta ? 'selected' : '' ?>>
                  <?= e($pergunta) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form__field">
            <label for="resposta">Resposta</label>
            <input class="text-field" id="resposta" name="resposta" value="<?= velho($velhos, 'resposta') ?>"
                   autocomplete="off" required />
            <p class="form-hint">A resposta é normalizada em minúsculas e nunca é exibida.</p>
          </div>
        </div>

        <div class="form__actions">
          <button class="btn btn--primary" type="submit">Criar conta</button>
          <a class="btn btn--ghost" href="/login">Já tenho conta</a>
        </div>
      </form>
    </div>
  </div>
</section>
