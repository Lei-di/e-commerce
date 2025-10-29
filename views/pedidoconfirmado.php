<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Elegancia Store</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/pagina_principal.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/pedido_confirmado.css">
</head>
<body>
    <?php 
    // CORREÇÃO AQUI: Trocado _DIR_ por __DIR__
    include __DIR__ . '/components/cabecalho.php'; 
    ?>

    <div class="confirmation-container">
        <div class="confirmation-box" id="confirmation-box">
            <div class="success-icon">✓</div>
            <h1>Pedido Confirmado!</h1>
            <p>Obrigado pela sua compra. Seu pedido foi recebido e está sendo processado.</p>

            <div class="order-info" id="order-info">
                <p>Carregando detalhes do pedido...</p>
            </div>

            <div class="order-items-title">Itens do Pedido</div>
            <div id="order-items">
                </div>

            <div class="order-total">
                <span>Total do Pedido:</span>
                <span id="order-total">R$ 0,00</span>
            </div>

            <div class="email-notification">
                📧 Um e-mail de confirmação foi enviado para você com todos os detalhes do pedido.
            </div>

            <div class="action-buttons">
                <a href="<?= BASE_URL ?>/" class="btn btn-primary">Continuar Comprando</a>
                <a href="<?= BASE_URL ?>/perfil" class="btn btn-secondary">Ver Meus Pedidos</a>
            </div>
        </div>
    </div>

    <script>

        const baseURL = "<?= BASE_URL ?>";

        document.addEventListener("DOMContentLoaded", async () => {
            const orderInfoContainer = document.getElementById("order-info");
            const orderItemsContainer = document.getElementById("order-items");
            const orderTotalElement = document.getElementById("order-total");
            const confirmationBox = document.getElementById("confirmation-box");

            // 1. Pega o ID do pedido da URL
            const params = new URLSearchParams(window.location.search);
            const pedidoId = params.get('id');

            if (!pedidoId) {
                confirmationBox.innerHTML = "<h1>Erro</h1><p>ID do pedido não encontrado.</p>";
                return;
            }

            try {
                const response = await fetch(`${baseURL}/api/meus-pedidos`);
                const pedidos = await response.json(); // Pega a lista de todos os pedidos

                if (!response.ok) {
                    throw new Error(pedidos.erro || "Falha ao buscar dados do pedido.");
                }


                const pedido = pedidos.find(p => p.id_pedido == pedidoId);

                if (!pedido) {
                    throw new Error("Pedido não encontrado ou não pertence a esta conta.");
                }

                const dataFormatada = new Date(pedido.data_pedido).toLocaleDateString('pt-BR');
                
                orderInfoContainer.innerHTML = `
                    <div class="order-info-row">
                        <strong>Número do Pedido:</strong>
                        <span>#${pedido.id_pedido}</span>
                    </div>
                    <div class="order-info-row">
                        <strong>Data:</strong>
                        <span>${dataFormatada}</span>
                    </div>
                    <div class="order-info-row">
                        <strong>Status:</strong>
                        <span>${pedido.status}</span>
                    </div>
                `;
                
                orderItemsContainer.innerHTML = '';
                pedido.itens.forEach(item => {
                    orderItemsContainer.innerHTML += `
                        <div class="order-item">
                            <img src="${baseURL}/assets/imagens/${item.produto_imagem}" alt="${item.produto_nome}">
                            <div class="order-item-info">
                                <div class="order-item-name">${item.produto_nome}</div>
                                <div class="order-item-qty">Quantidade: ${item.quantidade}</div>
                            </div>
                            <div class="order-item-price">R$ ${parseFloat(item.preco_unitario * item.quantidade).toFixed(2).replace(".", ",")}</div>
                        </div>
                    `;
                });

                orderTotalElement.textContent = `R$ ${parseFloat(pedido.total).toFixed(2).replace(".", ",")}`;

            } catch (error) {
                console.error("Erro ao buscar detalhes do pedido:", error);
                confirmationBox.innerHTML = `<h1>Erro</h1><p>${error.message}</p><a href="${baseURL}/" class="btn btn-primary">Voltar ao Início</a>`;
            }
        });
    </script>
</body>
</html>