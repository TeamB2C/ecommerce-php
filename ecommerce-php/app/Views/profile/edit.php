<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="<?php echo asset_url('css/profile-edit.css'); ?>">
    <script src="<?php echo asset_url('js/validacoes.js'); ?>"></script>
</head>
<body>
    <?php
        $profileImage = trim((string) ($usuario['imagem'] ?? ''));
        $profileImageUrl = $profileImage !== '' ? upload_url($profileImage) : '';
    ?>
    <div class="profile-card">
        <div class="topbar">
            <a class="back-link" href="<?php echo url(''); ?>" aria-label="Voltar para a loja">
                <img src="<?php echo asset_url('images/sistema/back.png'); ?>" alt="Voltar" />
            </a>
            <div class="topbar-title">
                <h1 class="title">Editar Perfil</h1>
                <p class="subtitle">Atualize seus dados com segurança.</p>
            </div>
        </div>

        <?php if (isset($_SESSION['sucesso']) || isset($_SESSION['erro'])): ?>
            <div class="alert <?php echo isset($_SESSION['sucesso']) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($_SESSION['sucesso'] ?? $_SESSION['erro'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['sucesso'], $_SESSION['erro']); ?>
        <?php endif; ?>

        <form action="<?php echo url('perfil'); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario();">
            <?php echo csrf_field(); ?>

            <div class="avatar-wrap">
                <p class="avatar-hint">Clique no botão para escolher uma nova imagem.</p>
                <div class="avatar-box">
                    <img
                        id="previewFoto"
                        src="<?php echo htmlspecialchars($profileImageUrl, ENT_QUOTES, 'UTF-8'); ?>"
                        alt=""
                        <?php echo $profileImageUrl === '' ? 'style="display:none;"' : ''; ?>
                        onerror="this.style.display='none';"
                    />
                    <input type="file" id="foto" name="foto" accept="image/jpeg, image/png" style="display:none;" onchange="mostrarPreview(event)">
                </div>
                <label for="foto" class="change-photo-link">Trocar foto</label>
            </div>

            <div class="form-grid">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>" />
                    <span id="msg_nome"></span>
                </div>

                <div>
                    <label for="senha">Nova senha</label>
                    <input type="password" id="senha" name="senha" />
                </div>

                <div>
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" onkeyup="validarSenhas()" />
                    <span id="msg_senha"></span>
                </div>

                <button type="submit" class="submit-btn">Salvar alterações</button>
            </div>
        </form>
    </div>
</body>
</html>
