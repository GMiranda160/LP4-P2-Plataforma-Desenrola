<?php
/**
 * Tela: login
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /login
 * Recebe: $titulo, $velhos
 * Envia POST para /login, com usuario e senha. O servidor monta usuario@example.com.
 * Responsabilidade: formulário de login, com links para /cadastro e /recuperar-senha.
 * Veja INSTRUCOES_EDUARDO.md, seção 4.
 */
?>
<div class="auth-container">
    <h1 class="auth-title"><?= e($titulo) ?></h1>

    <form class="form" action="/login" method="post">
        <?= Csrf::campo() ?>

        <div class="form__field">
            <label class="text-field" for="usuario">Usuário</label>
            <div class="input-group">
                <input type="text" id="usuario" name="usuario" value="<?= e($velhos['usuario'] ?? '') ?>" autocomplete="email" required>
                <span class="input-group__suffix">@example.com</span>
            </div>
        </div>

        <div class="form__field">
            <label class="text-field" for="senha">Senha</label>
            <input type="password" id="senha" name="senha" autocomplete="current-password" required>
        </div>

        <div class="form__actions">
            <button class="btn btn--primary btn--block" type="submit">Entrar</button>
        </div>
    </form>

    <div class="auth-links">
        <p>Não tem uma conta? <a href="/cadastro">Cadastre-se</a></p>
        <p><a href="/recuperar-senha">Esqueceu sua senha?</a></p>
    </div>
</div>
