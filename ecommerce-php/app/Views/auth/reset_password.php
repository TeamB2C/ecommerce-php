<?php
$token = $token ?? '';
$erro = $_SESSION['erro_reset'] ?? null;
unset($_SESSION['erro_reset']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="<?php echo asset_url('css/auth-recovery.css'); ?>">
</head>
<body>
    <div class="auth-card">
        <div class="auth-card__head">
            <h1 class="auth-card__title">Nova senha</h1>
            <p class="auth-card__subtitle">Digite sua nova senha para concluir a redefinição.</p>
        </div>
        <div class="auth-card__body">
            <form method="POST" action="<?php echo url('reset-password'); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

                <label for="senha" class="form-label">Senha</label>
                <input id="senha" class="form-input" type="password" name="senha" placeholder="Nova senha" required>

                <button type="submit" class="btn-primary">Alterar senha</button>
            </form>

            <?php if ($erro): ?>
                <p class="feedback feedback--error"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
        </div>
        <div class="auth-card__footer">
            <a class="btn-link" href="<?php echo url(''); ?>">Voltar para a loja</a>
        </div>
    </div>
</body>
</html>
