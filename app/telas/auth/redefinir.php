<?php
/**
 * Tela: redefinir senha
 * Dono: Eduardo. Só Eduardo altera este arquivo.
 * Rota: aparece depois de POST /recuperar-senha
 * Recebe: $titulo, $pergunta
 * Envia POST para /recuperar-senha/redefinir.
 * Campos: resposta, senha, confirmar_senha
 *
 * TODO(Eduardo):
 * 1. Mostrar a pergunta de segurança com e().
 * 2. Criar o formulário com Csrf::campo().
 * Veja INSTRUCOES_EDUARDO.md, seção 4.
 */
?>

<div class="auth-container">
    <h1 class="auth-title"><?= e($titulo) ?></h1>

    <form class="form" action="/recuperar-senha/redefinir" method="post">
        <?= Csrf::campo() ?>

        <div class="form__field">
            <label class="text-field" for="resposta">Pergunta de segurança</label>
            <p class="form-hint"><?= e($pergunta) ?></p>
            <input type="text" id="resposta" name="resposta" required>
        </div>

        <div class="form__field">
            <label class="text-field" for="senha">Nova senha</label>
            <input type="password" id="senha" name="senha" autocomplete="new-password" required>
        </div>

        <div class="form__field">
            <label class="text-field" for="confirmar_senha">Confirmar nova senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" autocomplete="new-password" required>
        </div>

        <div class="form__actions">
            <button class="btn btn--primary btn--block" type="submit">Redefinir senha</button>
        </div>
    </form>
</div>