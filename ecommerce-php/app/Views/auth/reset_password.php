<?php
$token = $token ?? '';
$erro = $_SESSION['erro_reset'] ?? null;

unset($_SESSION['erro_reset']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Redefinir Senha</title>

    <link rel="stylesheet"
          href="<?php echo asset_url('css/login_e_registro.css'); ?>">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'
          rel='stylesheet'>

    <style>

        body{
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            background:#f5f5f5;
            padding:20px;
        }

        .reset-container{
            width:100%;
            max-width:420px;
            background:#fff;
            border-radius:20px;
            padding:40px;
            box-shadow:0 10px 30px rgba(0,0,0,0.1);
        }

        .reset-container h1{
            text-align:center;
            margin-bottom:10px;
            font-size:32px;
        }

        .reset-container p{
            text-align:center;
            color:#666;
            margin-bottom:25px;
        }

        .input-box{
            position:relative;
            margin-bottom:20px;
        }

        .input-box input{
            width:100%;
            padding:14px 45px 14px 15px;
            border:none;
            outline:none;
            border-radius:10px;
            background:#f1f1f1;
            font-size:16px;
        }

        .input-box i{
            position:absolute;
            right:15px;
            top:50%;
            transform:translateY(-50%);
            font-size:20px;
            color:#555;
        }

        .btn{
            width:100%;
            height:50px;
            border:none;
            border-radius:10px;
            background:#000;
            color:#fff;
            font-size:16px;
            cursor:pointer;
            transition:.3s;
        }

        .btn:hover{
            opacity:.9;
        }

        .erro{
            background:#ffe5e5;
            color:#cc0000;
            padding:12px;
            border-radius:8px;
            margin-bottom:20px;
            text-align:center;
        }

    </style>
</head>

<body>

<div class="reset-container">

    <h1>Nova senha</h1>

    <p>
        Digite sua nova senha para continuar.
    </p>

    <?php if ($erro): ?>
        <div class="erro">
            <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form method="POST"
          action="<?php echo url('reset-password'); ?>">

        <?php echo csrf_field(); ?>

        <input type="hidden"
               name="token"
               value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

        <div class="input-box">

            <input
                type="password"
                name="senha"
                placeholder="Nova senha"
                required
            >

            <i class='bx bxs-lock-alt'></i>

        </div>

        <button type="submit" class="btn">
            Alterar senha
        </button>

    </form>

</div>

</body>
</html>