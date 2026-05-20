<?php
$erroLogin = $_SESSION['erro_login'] ?? null;
unset($_SESSION['erro_login']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulário</title>
  <link rel="stylesheet" href="<?php echo asset_url('css/login_e_registro.css'); ?>">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <div class="container" id="container">
    <div class="form-box login">
      <form method="POST" action="<?php echo url('login'); ?>">
        <h1>Entrar</h1>
        <?php if ($erroLogin): ?>
          <p class="erro"><?php echo htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php echo csrf_field(); ?>
        <input type="hidden" name="acao" value="login">
        <div class="input-box">
          <input type="email" name="email" placeholder="Email" required>
          <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
          <input type="password" name="senha" placeholder="Senha" required>
          <i class="bx bxs-lock-alt"></i>
        </div>
        <div class="forgot-link">
          <a href="<?php echo url('forgot-password'); ?>">
              Esqueceu a senha?
          </a>
        </div>
        <button type="submit" class="btn">Entrar</button>
      </form>
    </div>

    <div class="form-box registro">
      <?php if (!empty($erroRegistro)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($erroRegistro, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>

      <form method="POST" action="<?php echo url('login'); ?>" enctype="multipart/form-data">
        <h1>Crie sua conta</h1>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="acao" value="registrar">
        <div class="input-box">
          <input type="text" name="nome" placeholder="Nome" required>
          <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
          <input type="email" name="email" placeholder="Email" required>
          <i class="bx bxs-envelope"></i>
        </div>
        <div class="input-box">
          <input type="password" name="senha" placeholder="Senha" required>
          <i class="bx bxs-lock-alt"></i>
        </div>
        <button type="submit" class="btn">Registrar</button>
      </form>
    </div>

    <div class="toggle-box">
      <div class="toggle-panel toggle-left">
        <h1>Bem-vindo de volta!</h1>
        <p>Não tem uma conta?</p>
        <button class="btn" id="register">Registrar-se</button>
      </div>

      <div class="toggle-panel toggle-right">
        <h1>Olá, bem-vindo!</h1>
        <p>Já tem uma conta?</p>
        <button class="btn" id="login">Entrar</button>
      </div>
    </div>
  </div>
<script src="<?php echo asset_url('js/login_registro.js'); ?>"></script>
</body>
</html>


