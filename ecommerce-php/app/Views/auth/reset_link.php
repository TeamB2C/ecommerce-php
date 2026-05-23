<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de senha</title>
    <link rel="stylesheet" href="<?php echo asset_url('css/auth-recovery.css'); ?>">
</head>
<body>
    <div class="auth-card">
        <div class="auth-card__head">
            <h1 class="auth-card__title">Recuperação de senha</h1>
            <p class="auth-card__subtitle">Clique no botão para abrir a tela de redefinição.</p>
        </div>
        <div class="auth-card__body">
            <a href="<?php echo htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn-primary">Redefinir senha</a>
            <p class="help">Se o botão não abrir, copie este link: <?php echo htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="auth-card__footer">
            <a class="btn-link" href="<?php echo url(''); ?>">Voltar para a loja</a>
        </div>
    </div>
</body>
</html>
