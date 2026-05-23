<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Um Convite de Casamento</title>
  <link rel="icon" href="<?php echo asset_url('images/sistema/carta_fechada.png'); ?>" type="image/png">

  <link rel="stylesheet"
    href="<?php echo asset_url('css/style.css');         ?>?v=<?php echo asset_version('assets/css/style.css'); ?>" />
  <link rel="stylesheet"
    href="<?php echo asset_url('css/header.css');        ?>?v=<?php echo asset_version('assets/css/header.css'); ?>" />
  <link rel="stylesheet"
    href="<?php echo asset_url('css/carrossel.css');     ?>?v=<?php echo asset_version('assets/css/carrossel.css'); ?>" />
  <link rel="stylesheet"
    href="<?php echo asset_url('css/footer.css');        ?>?v=<?php echo asset_version('assets/css/footer.css'); ?>" />
  <link rel="stylesheet"
    href="<?php echo asset_url('css/menu-lateral.css'); ?>?v=<?php echo asset_version('assets/css/menu-lateral.css'); ?>" />

  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

  <?php include __DIR__ . '/../partials/store/header.php'; ?>

  <main class="pagina-container">

    <!-- BANNER CARROSSEL -->
    <section class="banner-section">
      <div class="slider">
        <div class="slides">
          <input type="radio" name="radio-btn" id="radio1" checked>
          <input type="radio" name="radio-btn" id="radio2">
          <input type="radio" name="radio-btn" id="radio3">
          <input type="radio" name="radio-btn" id="radio4">
          <input type="radio" name="radio-btn" id="radio5">
          <div class="slide first"><img src="<?php echo asset_url('images/sistema/banner1.png'); ?>" alt="Banner 1">
          </div>
          <div class="slide"><img src="<?php echo asset_url('images/sistema/banner2.png'); ?>" alt="Banner 2"></div>
          <div class="slide"><img src="<?php echo asset_url('images/sistema/banner3.png'); ?>" alt="Banner 3"></div>
          <div class="slide"><img src="<?php echo asset_url('images/sistema/banner4.png'); ?>" alt="Banner 4"></div>
          <div class="slide"><img src="<?php echo asset_url('images/sistema/banner5.png'); ?>" alt="Banner 5"></div>
          <div class="navigation-auto">
            <div class="auto-btn1"></div>
            <div class="auto-btn2"></div>
            <div class="auto-btn3"></div>
            <div class="auto-btn4"></div>
            <div class="auto-btn5"></div>
          </div>
        </div>
        <div class="manual-navigation">
          <label for="radio1" class="manual-btn"></label>
          <label for="radio2" class="manual-btn"></label>
          <label for="radio3" class="manual-btn"></label>
          <label for="radio4" class="manual-btn"></label>
          <label for="radio5" class="manual-btn"></label>
        </div>
      </div>
    </section>

    <div class="catalogo-container">

      <section class="destaque-section">
        <h2 class="section-title">
          Destaques
          <a href="#">Ver todos →</a>
        </h2>
        <div class="destaque-grid">
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab77b4f6ea.png'); ?>" alt="Convites Clássicos">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Coleção</span>
              <span class="destaque-card__name">Convites Clássicos</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab78d4b55f.png'); ?>" alt="Papelaria Rústica">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Tendência</span>
              <span class="destaque-card__name">Papelaria Rústica</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab79b22cbf.png'); ?>" alt="Caixas para Padrinhos">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Especial</span>
              <span class="destaque-card__name">Caixas para Padrinhos</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab7a60056c.png'); ?>" alt="Lacres de Cera">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Novo</span>
              <span class="destaque-card__name">Lacres de Cera</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
        </div>
      </section>

      <!-- BANNER DUPLO -->
      <div class="banner-duplo">
        <div class="banner-duplo__item">
          <img src="<?php echo asset_url('images/produtos/682ab7ca4bc91.png'); ?>" alt="Convites Modernos">
          <div class="banner-duplo__item__text">
            <h3>Convites<br>Modernos</h3>
            <a href="#">Explorar</a>
          </div>
        </div>
        <div class="banner-duplo__item">
          <img src="<?php echo asset_url('images/produtos/682ab7d6f2298.png'); ?>" alt="Papelaria Completa">
          <div class="banner-duplo__item__text">
            <h3>Papelaria<br>Completa</h3>
            <a href="#">Explorar</a>
          </div>
        </div>
      </div>

      <!-- NOVIDADES -->
      <section class="destaque-section">
        <h2 class="section-title">
          Novidades
          <a href="#">Ver todos →</a>
        </h2>
        <div class="destaque-grid">
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab7ef100ec.png'); ?>" alt="Tags e Etiquetas">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Novo</span>
              <span class="destaque-card__name">Tags & Etiquetas</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab80485780.png'); ?>" alt="Cardápios">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Destaque</span>
              <span class="destaque-card__name">Cardápios</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab81a3e077.png'); ?>" alt="Votos de Casamento">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Especial</span>
              <span class="destaque-card__name">Votos de Casamento</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
          <div class="destaque-card">
            <img src="<?php echo asset_url('images/produtos/682ab82557f09.png'); ?>" alt="Pulseiras">
            <div class="destaque-card__overlay">
              <span class="destaque-card__label">Novo</span>
              <span class="destaque-card__name">Pulseiras</span>
              <a href="#" class="destaque-card__cta">Ver mais</a>
            </div>
          </div>
        </div>
      </section>

      <!-- TODOS OS PRODUTOS -->
      <section class="catalogo">
        <h2 class="section-title">Todos os Produtos</h2>
        <div class="conteudo-produto">
          <?php foreach ($produtos as $produto): ?>
            <div class="produto">
              <a href="<?php echo url('produto'); ?>?id=<?php echo (int)$produto['id']; ?>" class="produto-link-detalhe">
                <img
                  src="<?php echo asset_url('images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                  alt="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>" />
              </a>
              <div class="info-preco-sacola">
                <?php if ((float)$produto['preco'] > 0): ?>
                  <span class="preco">R$ <?php echo number_format((float)$produto['preco'], 2, ',', '.'); ?></span>
                <?php else: ?>
                  <span class="preco indisponivel">Preço indisponível</span>
                <?php endif; ?>
              </div>
              <h3>
                <a href="<?php echo url('produto'); ?>?id=<?php echo (int)$produto['id']; ?>">
                  <?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
              </h3>
              <?php if ((float)$produto['preco'] > 0): ?>
                <form action="<?php echo url('adicionar_ao_carrinho.php'); ?>" method="POST" class="js-add-cart">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="produto_id" value="<?php echo (int) $produto['id']; ?>">
                  <input type="hidden" name="quantidade" value="1">
                  <button type="submit" class="btn-adicionar-sacola">Adicionar à sacola</button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

    </div>
  </main>

  <?php include __DIR__ . '/../partials/store/footer.php'; ?>

  <script src="<?php echo asset_url('js/carrossel.js'); ?>"></script>
  <?php include __DIR__ . '/../partials/store/scripts.php'; ?>

</body>

</html>
