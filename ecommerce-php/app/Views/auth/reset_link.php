<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Recuperação de Senha</title>

    <link
        rel="stylesheet"
        href="<?php echo asset_url('css/login_e_registro.css'); ?>"
    >
</head>

<body>

<div class="container">

    <div class="form-box login">

        <form>

            <h1>
                Recuperação de Senha
            </h1>

            <p
                style="
                    text-align:center;
                    margin-bottom:20px;
                    color:#666;
                "
            >
                Clique no botão abaixo para redefinir sua senha.
            </p>

            <a
                href="<?php echo htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8'); ?>"
                class="btn"
                style="
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    text-decoration:none;
                    width:100%;
                    height:48px;
                "
            >
                Redefinir senha
            </a>

            <div
                style="
                    margin-top:20px;
                    text-align:center;
                "
            >

                <a
                    href="<?php echo url('login'); ?>"
                    style="
                        color:#555;
                        text-decoration:none;
                    "
                >
                    Voltar para login
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>