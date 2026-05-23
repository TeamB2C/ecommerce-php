<?php
$erroReset = $_SESSION['erro_reset'] ?? null;
$sucessoReset = $_SESSION['sucesso_reset'] ?? null;

unset($_SESSION['erro_reset']);
unset($_SESSION['sucesso_reset']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
    <link rel="stylesheet" href="<?php echo asset_url('css/auth-recovery.css'); ?>">
</head>

<body>
    <div class="auth-card">
        <div class="auth-card__head">
            <h1 class="auth-card__title">Recuperar senha</h1>
            <p class="auth-card__subtitle">Digite seu e-mail para gerar o link de redefinição.</p>
        </div>

        <div class="auth-card__body">
            <form method="POST" action="<?php echo url('forgot-password'); ?>">
                <?php echo csrf_field(); ?>

                <label for="email" class="form-label">E-mail</label>
                <input id="email" class="form-input" type="email" name="email" placeholder="seuemail@dominio.com" required>

                <button type="submit" class="btn-primary">Enviar link</button>
            </form>

            <?php if ($erroReset): ?>
                <p class="feedback feedback--error"><?php echo htmlspecialchars($erroReset, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <?php if ($sucessoReset): ?>
                <div class="feedback feedback--success">
                    Link gerado com sucesso:
                    <a class="reset-link" href="<?php echo htmlspecialchars($sucessoReset, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($sucessoReset, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="auth-card__footer">
            <a class="btn-link" href="<?php echo url(''); ?>">Voltar para a loja</a>
            <a class="btn-link" href="<?php echo url('login'); ?>">Voltar para login</a>
        </div>
    </div>
</body>

</html>
