<?php
/**
 * Tela: recuperar senha
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: GET /recuperar-senha
 * Recebe: $titulo
 * Envia POST para /recuperar-senha, com usuario. O servidor monta usuario@example.com.
 * Responsabilidade: primeiro passo da recuperação de senha, pedindo o usuário da conta.
 * Veja INSTRUCOES_EDUARDO.md, seção 4.
 */
?>
<div class="auth-container">
    <h1 class="auth-title"><?= e($titulo) ?></h1>

    <form class="form" action="/recuperar-senha" method="post">
        <?= Csrf::campo() ?>

        <div class="form__field">
            <label class="text-field" for="usuario">Usuário</label>
            <div class="input-group">
                <input type="text" id="usuario" name="usuario" autocomplete="email" required>
                <span class="input-group__suffix">@example.com</span>
            </div>
        </div>

        <div class="form__actions">
            <button class="btn btn--primary btn--block" type="submit">Continuar</button>
        </div>
    </form>

    <div class="auth-links">
        <p>Lembrou a senha? <a href="/login">Voltar para o login</a></p>
    </div>
</div>
