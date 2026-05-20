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
    <title>Recuperar senha</title>

    <link rel="stylesheet"
        href="<?php echo asset_url('css/login_e_registro.css'); ?>">
</head>

<body>

    <div class="container">

        <div class="form-box login">

            <form method="POST"
                action="<?php echo url('forgot-password'); ?>">

                <h1>Recuperar senha</h1>

                <?php echo csrf_field(); ?>

                <div class="input-box">
                    <input
                        type="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        required>
                </div>

                <button type="submit" class="btn">
                    Enviar link
                </button>

                <?php if ($erroReset): ?>
                    <p style="color:red; margin-top:10px;">
                        <?php echo htmlspecialchars($erroReset); ?>
                    </p>
                <?php endif; ?>

                <?php if ($sucessoReset): ?>

    <p style="color:green; margin-top:10px;">
        Link gerado com sucesso:
    </p>

    <a
        href="<?php echo htmlspecialchars($sucessoReset); ?>"
        style="color:blue; word-break: break-all;"
    >
        <?php echo htmlspecialchars($sucessoReset); ?>
    </a>

<?php endif; ?>

            </form>

        </div>

    </div>

</body>
</html>