<?php
$usuario = $usuario ?? [
    'logado' => false,
    'nome' => '',
    'imagem' => '',
    'is_admin' => false,
];
$total_itens_carrinho = $total_itens_carrinho ?? 0;
$itens_carrinho = $itens_carrinho ?? [];
$total_carrinho = $total_carrinho ?? 0;
$to = static fn(string $route = ''): string => url($route);
?>
<div class="cart-overlay" id="cartOverlay" onclick="toggleCarrinho()"></div>
<header class="header-fixo">
    <div class="header-superior">

        <nav class="navbar-principal">
            <ul class="navbar-links">
                <li><a href="<?php echo $to(''); ?>">Início</a></li>
                <li class="navbar-dropdown">
                    <a href="#">Convites</a>
                    <ul class="navbar-submenu">
                        <li><a href="#">Clássicos</a></li>
                        <li><a href="#">Modernos</a></li>
                        <li><a href="#">Rústicos</a></li>
                        <li><a href="#">Digitais</a></li>
                    </ul>
                </li>
                <li class="navbar-dropdown">
                    <a href="#">Papelaria</a>
                    <ul class="navbar-submenu">
                        <li><a href="#">Cardápios</a></li>
                        <li><a href="#">Tags & Etiquetas</a></li>
                        <li><a href="#">Votos</a></li>
                        <li><a href="#">Lacres de Cera</a></li>
                    </ul>
                </li>
                <li class="navbar-dropdown">
                    <a href="#">Padrinhos</a>
                    <ul class="navbar-submenu">
                        <li><a href="#">Caixas para Padrinhos</a></li>
                        <li><a href="#">Caixas para os Pais</a></li>
                        <li><a href="#">Pulseiras</a></li>
                    </ul>
                </li>
                <li><a href="#">Lançamentos</a></li>
                <li><a href="#">Sobre nós</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </nav>
        <!-- Esquerda: hamburguer (mobile) + busca (desktop) -->
        <div class="header-left">
            <div class="menu-hamburguer" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
            <div class="search-bar-container desktop-search">
                <i class="fas fa-search search-icon-left"></i>
                <input type="text" class="search-input" placeholder="Buscar produtos...">
            </div>
        </div>

        <!-- Centro: logo -->
        <div class="logo">
            <img src="<?php echo asset_url('images/sistema/logo01.png'); ?>" alt="Logo" class="logo-img">
        </div>

        <!-- Direita: perfil + carrinho -->
        <div class="icones-header-direita">
            <div class="icone-texto-container perfil-menu-container">
                <a href="<?php echo $usuario['logado'] ? $to('perfil') : $to('login'); ?>" class="icone-link" id="perfil-link">
                    <i class='bx bx-user'></i>
                </a>

                <?php if ($usuario['logado']): ?>
                    <div class="perfil-dropdown" id="perfil-dropdown">
                        <a href="<?php echo $to('perfil'); ?>">Gerenciar Perfil</a>
                        <?php if ($usuario['is_admin']): ?>
                            <a href="<?php echo $to('admin'); ?>">Painel Admin</a>
                        <?php endif; ?>
                        <a href="<?php echo $to('logout'); ?>" class="logout-btn">Sair</a>
                    </div>
                <?php endif; ?>
            </div>

            <button onclick="toggleCarrinho()" class="icone-link sacola-link" type="button">
                <i class='bx bx-shopping-bag'></i>
                <?php if ($total_itens_carrinho > 0): ?>
                    <span class="cart-notification"><?php echo $total_itens_carrinho; ?></span>
                <?php endif; ?>
            </button>
            <nav class="menu-lateral" id="menuLateral">
                <div class="menu-lateral-conteudo">
                    <button class="fechar-menu" onclick="toggleMenu()"><i class="fas fa-times"></i></button>
                    <div class="menu-search-container">
                        <input type="text" class="menu-search-input" placeholder="Buscar produtos...">
                        <button class="menu-search-button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
        </div>

        <div class="overlay" id="overlay" onclick="toggleMenu()"></div>
</header>

<aside class="cart-sidebar" id="cartSidebar" aria-label="Sacola de compras">

    <div class="cart-sidebar__header">
        <div class="cart-sidebar__title-wrap">
            <span class="cart-sidebar__icon">
                <i class='bx bx-shopping-bag'></i>
            </span>
            <h2 class="cart-sidebar__title">Minha Sacola</h2>
        </div>
        <button class="cart-sidebar__close" onclick="toggleCarrinho()" aria-label="Fechar sacola">
            <i class='bx bx-x'></i>
        </button>
    </div>

    <div class="cart-sidebar__body">
        <?php if (empty($itens_carrinho)): ?>
            <div class="cart-sidebar__empty">
                <i class='bx bx-shopping-bag cart-sidebar__empty-icon'></i>
                <p>Sua sacola está vazia.</p>
                <button onclick="toggleCarrinho()" class="cart-sidebar__continue-btn">Continuar comprando</button>
            </div>
        <?php else: ?>
            <ul class="cart-sidebar__list">
                <?php foreach ($itens_carrinho as $item):
                    $produto = $item['produto'] ?? null;
                    if (!$produto) continue;
                    $quantidade = $item['quantidade'] ?? 0;
                    $subtotal = $item['subtotal'] ?? 0;
                    $produto_id = $produto['id'] ?? null;
                ?>
                    <li class="cart-sidebar__item">
                        <div class="cart-sidebar__item-img">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="<?php echo asset_url('images/produtos/' . $produto['imagem']); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php else: ?>
                                <div class="cart-sidebar__item-img-placeholder">
                                    <i class='bx bx-image-alt'></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cart-sidebar__item-info">
                            <p class="cart-sidebar__item-name">
                                <?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                            <p class="cart-sidebar__item-price">
                                R$ <?php echo number_format((float) $produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <div class="cart-sidebar__item-qty">
                                <form action="<?php echo url('atualizar_carrinho.php'); ?>" method="POST" class="cart-qty-form">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="produto_id" value="<?php echo (int) $produto_id; ?>">
                                    <button type="button" class="qty-btn qty-btn--minus" onclick="alterarQtd(this, -1)">−</button>
                                    <input type="number" name="quantidade" value="<?php echo (int) $quantidade; ?>" min="1" class="qty-input"
                                        readonly>
                                    <button type="button" class="qty-btn qty-btn--plus" onclick="alterarQtd(this, 1)">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="cart-sidebar__item-right">
                            <span class="cart-sidebar__item-subtotal">
                                R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                            </span>
                            <form action="<?php echo url('remover_do_carrinho.php'); ?>" method="POST" class="cart-remove-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="produto_id" value="<?php echo (int) $produto_id; ?>">
                                <button type="submit" class="cart-sidebar__remove-btn" aria-label="Remover item">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php if (!empty($itens_carrinho)): ?>
        <div class="cart-sidebar__footer">
            <div class="cart-sidebar__total-row">
                <span>Total</span>
                <span class="cart-sidebar__total-value">
                    R$ <?php echo number_format((float) $total_carrinho, 2, ',', '.'); ?>
                </span>
            </div>

            <button id="cartBtnOrcamento" class="cart-sidebar__whatsapp-btn">
                <i class='bx bxl-whatsapp'></i>
                Enviar Orçamento pelo WhatsApp
            </button>

            <button onclick="toggleCarrinho()" class="cart-sidebar__continue-link">
                ← Continuar comprando
            </button>
        </div>
    <?php endif; ?>
</aside>

<div id="cartModal" class="cart-modal">
    <div class="cart-modal__content">
        <button class="cart-modal__close" id="cartModalClose" aria-label="Fechar">
            <i class='bx bx-x'></i>
        </button>
        <h3 class="cart-modal__title">Finalizar Orçamento</h3>
        <p class="cart-modal__subtitle">Preencha suas informações para receber o orçamento</p>

        <form id="form-orcamento" class="cart-modal__form" novalidate>
            <div class="cart-modal__field">
                <label for="cm-nome">Nome completo *</label>
                <input type="text" id="cm-nome" placeholder="Seu nome completo" required>
            </div>
            <div class="cart-modal__field">
                <label for="cm-tel">Telefone com DDD *</label>
                <input 
                    type="text"
                    id="cm-tel"
                    placeholder="Ex: 11999999999"
                    maxlength="11"
                    inputmode="numeric"
                    pattern="[0-9]{11}"
                    required>
            </div>
            <div class="cart-modal__field">
                <label for="cm-data">Data do casamento *</label>
                <input type="date" id="cm-data" required>
            </div>
            <div class="cart-modal__row">
                <div class="cart-modal__field">
                    <label for="cm-entrega">Entrega *</label>
                    <select id="cm-entrega" required>
                        <option value="">Selecione</option>
                        <option value="Retirar no local">Retirar no local</option>
                        <option value="Entrega em domicílio">Entrega em domicílio</option>
                    </select>
                </div>
                <div class="cart-modal__field">
                    <label for="cm-pag">Pagamento *</label>
                    <select id="cm-pag" required>
                        <option value="">Selecione</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão">Cartão</option>
                        <option value="Pix">Pix</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="cart-modal__submit-btn">
                <i class='bx bxl-whatsapp'></i> Enviar pelo WhatsApp
            </button>
        </form>
    </div>
</div>

<style>
    :root {
        --cart-width: 420px;
        --cart-bg: #ffffff;
        --cart-border: #f0ece8;
        --cart-accent: pink;
        --cart-accent-hover: pink;
        --cart-green: #25d366;
        --cart-green-hover: #1db954;
        --cart-text: #1a1a1a;
        --cart-muted: #888;
        --cart-shadow: 0 8px 40px rgba(0, 0, 0, 0.18);
        --cart-z: 1100;
        --cart-transition: 0.38s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cart-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: calc(var(--cart-z) - 1);
        opacity: 0;
        pointer-events: none;
        transition: opacity var(--cart-transition);
        backdrop-filter: blur(2px);
    }

    .cart-overlay.is-active {
        opacity: 1;
        pointer-events: all;
    }

    .cart-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: var(--cart-width);
        max-width: 100vw;
        height: 100dvh;
        background: var(--cart-bg);
        z-index: var(--cart-z);
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform var(--cart-transition);
        box-shadow: var(--cart-shadow);
        border-left: 1px solid var(--cart-border);
    }

    .cart-sidebar.is-open {
        transform: translateX(0);
    }

    .cart-sidebar__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px;
        border-bottom: 1px solid var(--cart-border);
        flex-shrink: 0;
    }

    .cart-sidebar__title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-sidebar__icon {
        font-size: 1.5rem;
        color: var(--cart-accent);
        line-height: 1;
    }

    .cart-sidebar__title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--cart-text);
        margin: 0;
        letter-spacing: 0.01em;
    }

    .cart-sidebar__close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.6rem;
        color: var(--cart-muted);
        line-height: 1;
        padding: 4px;
        border-radius: 50%;
        transition: color 0.2s, background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-sidebar__close:hover {
        color: var(--cart-text);
        background: #f5f5f5;
    }

    .cart-sidebar__body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 24px;
        scrollbar-width: thin;
        scrollbar-color: #ddd transparent;
    }

    .cart-sidebar__body::-webkit-scrollbar {
        width: 4px;
    }

    .cart-sidebar__body::-webkit-scrollbar-track {
        background: transparent;
    }

    .cart-sidebar__body::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 2px;
    }

    .cart-sidebar__empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        gap: 16px;
        text-align: center;
        padding: 48px 16px;
        color: var(--cart-muted);
    }

    .cart-sidebar__empty-icon {
        font-size: 3.5rem;
        color: #ddd;
    }

    .cart-sidebar__empty p {
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
    }

    .cart-sidebar__continue-btn {
        display: block;
        width: 100%;
        background: pink;
        border: none;
        color: #fff;
        padding: 11px 22px;
        border-radius: 4px;
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .cart-sidebar__continue-btn:hover {
        background: #9e1562;
    }

    .cart-sidebar__list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    .cart-sidebar__item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 0;
        border-bottom: 1px solid var(--cart-border);
        animation: cartItemIn 0.3s ease both;
    }

    @keyframes cartItemIn {
        from {
            opacity: 0;
            transform: translateX(12px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .cart-sidebar__item-img {
        width: 72px;
        height: 72px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f5f0ee;
        border: 1px solid var(--cart-border);
    }

    .cart-sidebar__item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-sidebar__item-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 1.6rem;
    }

    .cart-sidebar__item-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .cart-sidebar__item-name {
        font-family: 'Poppins', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--cart-text);
        line-height: 1.3;
        margin: 0;
    }

    .cart-sidebar__item-price {
        font-size: 0.82rem;
        color: var(--cart-muted);
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .cart-sidebar__item-qty {
        display: flex;
        align-items: center;
        margin-top: 8px;
    }

    .cart-qty-form {
        display: flex;
        align-items: center;
        border: 1.5px solid #e8e0db;
        border-radius: 8px;
        overflow: hidden;
    }

    .qty-btn {
        background: none;
        border: none;
        width: 30px;
        height: 30px;
        font-size: 1rem;
        cursor: pointer;
        color: var(--cart-text);
        transition: background 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
    }

    .qty-btn:hover {
        background: #f5f0ee;
    }

    .qty-input {
        width: 36px;
        text-align: center;
        border: none;
        border-left: 1.5px solid #e8e0db;
        border-right: 1.5px solid #e8e0db;
        font-size: 0.88rem;
        font-family: 'Poppins', sans-serif;
        color: var(--cart-text);
        background: #fff;
        padding: 0;
        height: 30px;
        -moz-appearance: textfield;
    }

    .qty-input::-webkit-inner-spin-button,
    .qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }

    .cart-sidebar__item-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
        flex-shrink: 0;
    }

    .cart-sidebar__item-subtotal {
        font-family: 'Poppins', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--cart-text);
    }

    .cart-sidebar__remove-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #ccc;
        font-size: 1.1rem;
        padding: 0;
        transition: color 0.2s;
        display: flex;
        align-items: center;
    }

    .cart-sidebar__remove-btn:hover {
        color: #e74c3c;
    }

    .cart-remove-form {
        display: inline;
    }

    .cart-sidebar__footer {
        padding: 20px 24px 28px;
        border-top: 1px solid var(--cart-border);
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cart-sidebar__total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    }

    .cart-sidebar__total-row span:first-child {
        font-size: 0.95rem;
        color: var(--cart-muted);
        font-weight: 500;
    }

    .cart-sidebar__total-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--cart-text);
    }

    .cart-sidebar__whatsapp-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #2edb10;
        color: #fff;
        border: none;
        padding: 11px;
        border-radius: 4px;
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
    }

    .cart-sidebar__whatsapp-btn i {
        font-size: 1rem;
    }

    .cart-sidebar__whatsapp-btn:hover {
        background: black;
    }

    .cart-sidebar__continue-link {
        display: block;
        width: 100%;
        background: pink;
        border: none;
        color: #fff;
        padding: 11px;
        border-radius: 4px;
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .cart-sidebar__continue-link:hover {
        background: #be1a77;
    }

    button.icone-link.sacola-link {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        position: relative;
    }

    .cart-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: calc(var(--cart-z) + 10);
        background: rgba(0, 0, 0, 0.55);
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(3px);
    }

    .cart-modal.is-open {
        display: flex;
    }

    .cart-modal__content {
        background: #fff;
        border-radius: 16px;
        padding: 32px 28px;
        width: 100%;
        max-width: 480px;
        max-height: 90dvh;
        overflow-y: auto;
        position: relative;
        animation: modalSlideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) both;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2);
    }

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(24px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .cart-modal__close {
        position: absolute;
        top: 14px;
        right: 14px;
        background: none;
        border: none;
        font-size: 1.6rem;
        color: #aaa;
        cursor: pointer;
        line-height: 1;
        padding: 4px;
        border-radius: 50%;
        transition: color 0.2s, background 0.2s;
        display: flex;
        align-items: center;
    }

    .cart-modal__close:hover {
        color: #333;
        background: #f5f5f5;
    }

    .cart-modal__title {
        font-family: 'Great Vibes', cursive;
        font-size: 1.9rem;
        color: var(--cart-accent);
        margin: 0 0 4px;
        text-align: center;
    }

    .cart-modal__subtitle {
        font-family: 'Poppins', sans-serif;
        font-size: 0.83rem;
        color: var(--cart-muted);
        text-align: center;
        margin: 0 0 20px;
    }

    .cart-modal__form {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .cart-modal__field {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .cart-modal__row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .cart-modal__field label {
        font-family: 'Poppins', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        color: #444;
    }

    .cart-modal__field input,
    .cart-modal__field select {
        padding: 10px 12px;
        border: 1.5px solid #e8e0db;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: var(--cart-text);
        background: #fdfaf9;
        transition: border-color 0.2s;
        outline: none;
        width: 100%;
    }

    .cart-modal__field input:focus,
    .cart-modal__field select:focus {
        border-color: var(--cart-accent);
        background: #fff;
    }

    .cart-modal__submit-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--cart-green);
        color: #fff;
        border: none;
        padding: 13px 20px;
        border-radius: 30px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 6px;
        transition: background 0.2s;
    }

    .cart-modal__submit-btn:hover {
        background: var(--cart-green-hover);
    }

    .cart-modal__submit-btn i {
        font-size: 1.15rem;
    }

    @media (max-width: 480px) {
        .cart-sidebar {
            width: 100vw;
        }

        .cart-modal__row {
            grid-template-columns: 1fr;
        }
    }

 /* =========================
   MENU PERFIL
========================= */

.perfil-menu-container{
    position: relative;
    display: flex;
    align-items: center;
}

/* DROPDOWN */
.perfil-dropdown{
    position: absolute;

    top: calc(100% + 14px);
    right: 0;

    min-width: 230px;

    background: #fff;

    border-radius: 16px;

    border: 1px solid #f2f2f2;

    overflow: hidden;

    z-index: 9999;

    box-shadow:
        0 12px 35px rgba(0,0,0,.08),
        0 2px 10px rgba(0,0,0,.04);

    /* Estado fechado */
    opacity: 0;
    visibility: hidden;

    transform:
        translateY(12px)
        scale(.98);

    pointer-events: none;

    transition:
        opacity .32s ease,
        transform .32s cubic-bezier(.22, 1, .36, 1),
        visibility .32s ease;
}

/* Área invisível entre ícone e menu */
.perfil-dropdown::before{
    content: '';

    position: absolute;

    top: -20px;
    left: 0;

    width: 100%;
    height: 20px;
}

/* Abrir menu */
.perfil-menu-container:hover .perfil-dropdown{
    opacity: 1;
    visibility: visible;

    transform:
        translateY(0)
        scale(1);

    pointer-events: auto;
}

/* Links */
.perfil-dropdown a{
    display: flex;
    align-items: center;

    width: 100%;

    padding: 14px 18px;

    text-decoration: none;

    color: #333;

    font-family: 'Poppins', sans-serif;
    font-size: .92rem;
    font-weight: 500;

    background: #fff;

    transition:
        background .22s ease,
        color .22s ease,
        padding-left .22s ease;
}

/* Hover dos links */
.perfil-dropdown a:hover{
    background: #fafafa;
    color: #000;

    padding-left: 22px;
}

/* Linha divisória */
.perfil-dropdown a:not(:last-child){
    border-bottom: 1px solid #f5f5f5;
}

/* Logout */
.logout-btn{
    color: #d62828 !important;
}

.logout-btn:hover{
    background: #fff5f5 !important;
    color: #b00020 !important;
}
</style>

<script>
    function toggleCarrinho() {
        const sidebar = document.getElementById('cartSidebar');
        const overlay = document.getElementById('cartOverlay');
        const isOpen = sidebar.classList.contains('is-open');
        sidebar.classList.toggle('is-open', !isOpen);
        overlay.classList.toggle('is-active', !isOpen);
        document.body.style.overflow = isOpen ? '' : 'hidden';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('cartSidebar');
            if (sidebar.classList.contains('is-open')) toggleCarrinho();
            fecharModal();
        }
    });

    function renderizarCarrinho({
        itens,
        total,
        total_itens
    }) {
        const sacola = document.querySelector('.sacola-link');
        let badge = sacola?.querySelector('.cart-notification');
        if (total_itens > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'cart-notification';
                sacola.appendChild(badge);
            }
            badge.textContent = total_itens;
        } else {
            badge?.remove();
        }

        const body = document.querySelector('.cart-sidebar__body');
        const sidebar = document.getElementById('cartSidebar');

        if (!itens.length) {
            body.innerHTML = `
                <div class="cart-sidebar__empty">
                    <i class='bx bx-shopping-bag cart-sidebar__empty-icon'></i>
                    <p>Sua sacola está vazia.</p>
                    <button onclick="toggleCarrinho()" class="cart-sidebar__continue-btn">Continuar comprando</button>
                </div>`;
            sidebar.querySelector('.cart-sidebar__footer')?.remove();
            return;
        }

        body.innerHTML = `
            <ul class="cart-sidebar__list">
                ${itens.map(item => `
                    <li class="cart-sidebar__item">
                        <div class="cart-sidebar__item-img">
                            ${item.imagem
                                ? `<img src="<?php echo asset_url('images/produtos/'); ?>${item.imagem}" alt="${item.nome}">`
                                : `<div class="cart-sidebar__item-img-placeholder"><i class='bx bx-image-alt'></i></div>`}
                        </div>
                        <div class="cart-sidebar__item-info">
                            <p class="cart-sidebar__item-name">${item.nome}</p>
                            <p class="cart-sidebar__item-price">
                                R$ ${item.preco.toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                            </p>
                            <div class="cart-sidebar__item-qty">
                                <div class="cart-qty-form">
                                    <button type="button" class="qty-btn qty-btn--minus"
                                        onclick="alterarQtd(this, -1, ${item.id}, ${item.quantidade})">−</button>
                                    <input type="number" value="${item.quantidade}" min="1" class="qty-input" readonly>
                                    <button type="button" class="qty-btn qty-btn--plus"
                                        onclick="alterarQtd(this, 1, ${item.id}, ${item.quantidade})">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="cart-sidebar__item-right">
                            <span class="cart-sidebar__item-subtotal">
                                R$ ${item.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                            </span>
                            <button class="cart-sidebar__remove-btn" onclick="removerItem(${item.id})" aria-label="Remover item">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </li>`).join('')}
            </ul>`;

        let footer = sidebar.querySelector('.cart-sidebar__footer');
        if (!footer) {
            footer = document.createElement('div');
            footer.className = 'cart-sidebar__footer';
            sidebar.appendChild(footer);
        }
        footer.innerHTML = `
            <div class="cart-sidebar__total-row">
                <span>Total</span>
                <span class="cart-sidebar__total-value">
                    R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                </span>
            </div>
            <button id="cartBtnOrcamento" class="cart-sidebar__whatsapp-btn" onclick="abrirModal()">
                <i class='bx bxl-whatsapp'></i> Enviar Orçamento pelo WhatsApp
            </button>
            <button onclick="toggleCarrinho()" class="cart-sidebar__continue-link">← Continuar comprando</button>`;
    }

    async function adicionarAoCarrinho(produtoId, quantidade = 1, csrfToken) {
        const form = new FormData();
        form.append('produto_id', produtoId);
        form.append('quantidade', quantidade);
        form.append('_csrf', csrfToken);

        const res = await fetch('<?php echo url("adicionar_ao_carrinho.php"); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: form,
        });
        const data = await res.json();
        renderizarCarrinho(data);
        toggleCarrinho();
    }

    async function alterarQtd(btn, delta, produtoId, qtdAtual) {
        const novaQtd = Math.max(1, qtdAtual + delta);
        const input = btn.closest('.cart-qty-form').querySelector('.qty-input');
        input.value = novaQtd;

        const form = new FormData();
        form.append('produto_id', produtoId);
        form.append('quantidade', novaQtd);
        form.append('_csrf', '<?php echo csrf_token(); ?>');

        const res = await fetch('<?php echo url("atualizar_carrinho.php"); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: form,
        });
        const data = await res.json();
        renderizarCarrinho(data);
    }

    async function removerItem(produtoId) {
        const form = new FormData();
        form.append('produto_id', produtoId);
        form.append('_csrf', '<?php echo csrf_token(); ?>');

        const res = await fetch('<?php echo url("remover_do_carrinho.php"); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: form,
        });
        const data = await res.json();
        renderizarCarrinho(data);
    }

    const cartModal = document.getElementById('cartModal');
    const telefoneInput = document.getElementById('cm-tel');

    telefoneInput?.addEventListener('input', function () {

    this.value = this.value
        .replace(/\D/g, '')
        .slice(0, 11);

});
    const cartModalClose = document.getElementById('cartModalClose');

    function abrirModal() {
        cartModal.classList.add('is-open');
    }

    function fecharModal() {
        cartModal.classList.remove('is-open');
    }

    cartModalClose?.addEventListener('click', fecharModal);
    cartModal.addEventListener('click', e => {
        if (e.target === cartModal) fecharModal();
    });

    document.getElementById('form-orcamento')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const nome = document.getElementById('cm-nome').value.trim();
        const tel = document.getElementById('cm-tel').value.trim();
        const dataVal = document.getElementById('cm-data').value;
        const entrega = document.getElementById('cm-entrega').value;
        const pag = document.getElementById('cm-pag').value;

        if (!nome || !tel || !dataVal || !entrega || !pag) {
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        if (tel.length !== 11) {
    alert('O telefone deve conter exatamente 11 números com DDD.');
    return;
}

        const [ano, mes, dia] = dataVal.split('-').map(Number);
        const dataCasamento = new Date(ano, mes - 1, dia);
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);
        if (dataCasamento <= hoje) {
            alert('A data do casamento deve ser futura!');
            return;
        }

        const dataFmt = `${String(dia).padStart(2,'0')}/${String(mes).padStart(2,'0')}/${ano}`;
        const hora = new Date().toLocaleString('pt-BR', {
            timeZone: 'America/Sao_Paulo',
            hour: 'numeric',
            hour12: false
        });
        const h = parseInt(hora, 10);
        const saud = h >= 5 && h < 12 ? 'Bom dia!' : h >= 12 && h < 18 ? 'Boa tarde!' : 'Boa noite!';

        const itens = [];
        document.querySelectorAll('.cart-sidebar__item').forEach(li => {
            const nome = li.querySelector('.cart-sidebar__item-name')?.textContent.trim() ?? '';
            const qtd = li.querySelector('.qty-input')?.value ?? '1';
            const preco = li.querySelector('.cart-sidebar__item-price')?.textContent.replace('R$', '').trim() ?? '0';
            const subtotal = li.querySelector('.cart-sidebar__item-subtotal')?.textContent.replace('R$', '').trim() ??
                '0';
            if (nome) itens.push({
                nome,
                qtd,
                preco,
                subtotal
            });
        });

        const totalEl = document.querySelector('.cart-sidebar__total-value');
        const total = totalEl ? totalEl.textContent.replace('R$', '').trim() : '0';

        let msg = `${saud} Esse é meu pedido abaixo:\n\n*Produtos:*\n`;
        itens.forEach(item => {
            msg += `• ${item.nome} - ${item.qtd}x - R$ ${item.preco}\n`;
        });
        msg += `\n*Total: R$* ${total}\n\n`;
        msg += `*Informações Pessoais:*\n`;
        msg +=
            `• Nome: ${nome}\n• Telefone: ${tel}\n• Data do Casamento: ${dataFmt}\n• Entrega: ${entrega}\n• Pagamento: ${pag}`;

        window.open(`https://wa.me/5511972093780?text=${encodeURIComponent(msg)}`, '_blank');
        fecharModal();
    });
</script>