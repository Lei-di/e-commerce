<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
</head>
<body>
    <?php include __DIR__ . '/components/cabecalho.php'; ?>

    <!-- Botão para abrir o carrinho -->
    <button class="open-cart-btn" onclick="openCart()">Abrir Carrinho</button>

    <!-- Menu do Carrinho -->
    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-container">
            <div class="cart-header">
                <h1>Carrinho</h1>
                <p>Enviar para Brazil</p>
                <button class="close-cart-btn" onclick="closeCart()">X</button>
            </div>

            <div class="cart-item">
                <img src="https://via.placeholder.com/100x100" alt="Produto 1">
                <div class="item-details">
                    <p class="item-name">SHEIN Multicolorido Malhado</p>
                    <p class="item-price">R$56,95</p>
                    <p class="item-status">Quase esgotado</p>
                </div>
                <div class="item-quantity">
                    <button>-</button>
                    <p>1</p>
                    <button>+</button>
                </div>
            </div>

            <div class="cart-item">
                <img src="https://via.placeholder.com/100x100" alt="Produto 2">
                <div class="item-details">
                    <p class="item-name">Malhado Simples ocasional</p>
                    <p class="item-price">R$40,90</p>
                </div>
                <div class="item-quantity">
                    <button>-</button>
                    <p>1</p>
                    <button>+</button>
                </div>
            </div>

            <div class="cart-item">
                <img src="https://via.placeholder.com/100x100" alt="Produto 3">
                <div class="item-details">
                    <p class="item-name">Dividido Simples elegante</p>
                    <p class="item-price">R$78,99</p>
                </div>
                <div class="item-quantity">
                    <button>-</button>
                    <p>1</p>
                    <button>+</button>
                </div>
            </div>

            <div class="cart-summary">
                <p>Tudo: <span>R$176,84</span></p>
                <p>Economizar até <span>R$15,00</span></p>
                <button class="finalize-btn">FINALIZAR (3)</button>
                <p class="free-shipping">Elegível para o TRANSPORTE LIVRE!</p>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/js/script.js"></script>
</body>
</html>
