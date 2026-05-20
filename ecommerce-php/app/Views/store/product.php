<?php
$produto = $produto ?? null;
$relacionados = $relacionados ?? [];

if (!$produto) {
    http_response_code(404);
    die('Produto não encontrado.');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?> | Um Convite de Casamento</title>
    <link rel="icon" href="<?php echo asset_url('images/sistema/carta_fechada.png'); ?>" type="image/png">

    <link rel="stylesheet"
        href="<?php echo asset_url('css/header.css'); ?>?v=<?php echo asset_version('../assets/css/header.css'); ?>">
    <link rel="stylesheet"
        href="<?php echo asset_url('css/style.css'); ?>?v=<?php echo asset_version('../assets/css/style.css'); ?>">
    <link rel="stylesheet"
        href="<?php echo asset_url('css/produto.css'); ?>?v=<?php echo asset_version('../assets/css/produto.css'); ?>">
    <link rel="stylesheet"
        href="<?php echo asset_url('css/menu-lateral.css'); ?>?v=<?php echo asset_version('../assets/css/menu-lateral.css'); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="<?php echo asset_url('css/footer.css'); ?>?v=<?php echo asset_version('../assets/css/footer.css'); ?>">
</head>

<body>
    <?php include __DIR__ . '/../partials/store/header.php'; ?>

    <main class="produto-detalhe-container">
        <a href="<?php echo url(''); ?>" class="produto-voltar" aria-label="Voltar para a página principal">
            ← Voltar para a página principal
        </a>

        <div class="produto-detalhe">
            <div class="imagem-produto">
                <img
                    src="<?php echo asset_url('images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                    alt="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="info-produto">
                <h1><?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="categoria">Categoria:
                    <?php echo htmlspecialchars($produto['categoria'] ?? 'Não especificada', ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="preco">R$ <?php echo number_format((float) $produto['preco'], 2, ',', '.'); ?></p>

                <form action="<?php echo url('adicionar_ao_carrinho.php'); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="produto_id" value="<?php echo (int) $produto['id']; ?>">
                    <input type="hidden" name="quantidade" value="1">
                    <button type="submit" class="btn-adicionar-sacola">Adicionar à sacola</button>
                </form>
            </div>
        </div>

        <?php if (!empty($relacionados)): ?>
            <section class="produtos-relacionados">
                <h2>Produtos Relacionados</h2>
                <div class="lista-relacionados">
                    <?php foreach ($relacionados as $item): ?>
                        <div class="produto-relacionado" onclick="abrirProduto(<?php echo (int) $item['id']; ?>)">
                            <img
                                src="<?php echo asset_url('images/produtos/' . htmlspecialchars($item['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                                alt="<?php echo htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8'); ?>">
                            <h3><?php echo htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <span class="preco">R$ <?php echo number_format((float) $item['preco'], 2, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <script>
        function abrirProduto(id) {
            window.location.href = '<?php echo url('produto'); ?>?id=' + id;
        }
    </script>
    <?php include __DIR__ . '/../partials/store/scripts.php'; ?>
</body>

</html>