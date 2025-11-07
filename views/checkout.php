<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Elegancia Store</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/pagina_principal.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/checkout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidecart.css">
</head>
<body>
    <?php 
    include __DIR__ . '/components/cabecalho.php'; 
    ?>

    <div class="checkout-container">
        <div class="checkout-main">
            <h1>Finalizar Compra</h1>
            <div class="breadcrumb">
                <a href="<?= BASE_URL ?>/">Início</a> / Checkout
            </div>

            <div class="section-title">Informações de Entrega</div>
            <form id="checkout-form">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" required placeholder="João Silva">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" required placeholder="joao@exemplo.com">
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone *</label>
                        <input type="tel" id="telefone" required placeholder="(11) 99999-9999">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cep">CEP *</label>
                        <input type="text" id="cep" required placeholder="00000-000">
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade *</label>
                        <input type="text" id="cidade" required placeholder="São Paulo">
                    </div>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço *</label>
                    <input type="text" id="endereco" required placeholder="Rua, número">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro *</label>
                        <input type="text" id="bairro" required placeholder="Centro">
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado *</label>
                        <select id="estado" required>
                            <option value="">Selecione</option>
                            <option value="SP">São Paulo</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="BA">Bahia</option>
                        </select>
                    </div>
                </div>

                <div class="section-title" style="margin-top: 40px;">Método de Pagamento</div>
                
                <div class="payment-methods">
                    <div class="payment-method active" data-method="credit">
                        💳 Crédito
                    </div>
                    <div class="payment-method" data-method="debit">
                        💳 Débito
                    </div>
                    <div class="payment-method" data-method="pix">
                        📱 PIX
                    </div>
                </div>

                <div id="card-info">
                    <div class="form-group">
                        <label for="cartao">Número do Cartão *</label>
                        <input type="text" id="cartao" placeholder="0000 0000 0000 0000" maxlength="19">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="validade">Validade *</label>
                            <input type="text" id="validade" placeholder="MM/AA" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV *</label>
                            <input type="text" id="cvv" placeholder="123" maxlength="3">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="checkout-sidebar">
            <div class="order-summary-title">Resumo do Pedido</div>
            
            <div id="order-items">
                <p>Carregando carrinho...</p>
            </div>

            <div class="order-totals">
                <div class="order-total-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">R$ 0,00</span>
                </div>
                <div class="order-total-row">
                    <span>Frete:</span>
                    <span id="frete">Grátis</span>
                </div>
                <div class="order-total-row final">
                    <span>Total:</span>
                    <span id="total">R$ 0,00</span>
                </div>
            </div>

            <button class="btn-finalizar" id="btn-finalizar">Confirmar Pedido</button>
            
            <div class="secure-checkout">
                🔒 Pagamento 100% seguro
            </div>
        </div>
    </div>

    <script>
        // Script original da página de checkout (com baseURL)
        const baseURL = "<?= BASE_URL ?>";

        document.addEventListener("DOMContentLoaded", async () => {
            const orderItemsContainer = document.getElementById('order-items');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const btnFinalizar = document.getElementById('btn-finalizar');

            try {
                const response = await fetch(`${baseURL}/api/carrinho/ver`);
                const data = await response.json(); // data = { carrinho: { ... } }

                if (!response.ok) {
                    throw new Error(data.erro || 'Falha ao carregar carrinho');
                }

                // --- INÍCIO DA CORREÇÃO ---

                // 1. Desempacota o objeto 'carrinho' que vem da API
                const carrinho = data.carrinho; 

                // 2. Verifica 'carrinho.itens', não 'data.itens'
                if (!carrinho || !carrinho.itens || carrinho.itens.length === 0) { 
                    alert("Seu carrinho está vazio!");
                    window.location.href = baseURL + "/"; // Garante a barra
                    return;
                }
                
                orderItemsContainer.innerHTML = '';
                
                // 3. Itera sobre 'carrinho.itens', não 'data.itens'
                carrinho.itens.forEach(item => {
                    // 4. Calcula o subtotal de CADA item (item.subtotal não existia)
                    const subtotalItem = parseFloat(item.preco) * parseInt(item.quantidade);

                    const itemHTML = `
                        <div class="order-item">
                            <img src="${baseURL}/assets/imagens/${item.imagem}" alt="${item.nome}">
                            <div class="order-item-info">
                                <div class="order-item-name">${item.nome}</div>
                                <div class_item-qty">Quantidade: ${item.quantidade}</div>
                            </div>
                            <div class_item-price">R$ ${subtotalItem.toFixed(2).replace(".", ",")}</div>
                        </div>
                    `;
                    orderItemsContainer.innerHTML += itemHTML;
                });
                
                // 6. Usa 'carrinho.totalPreco', não 'data.total'
                subtotalEl.textContent = `R$ ${parseFloat(carrinho.totalPreco).toFixed(2).replace(".", ",")}`;
                // 7. Usa 'carrinho.totalPreco', não 'data.total'
                totalEl.textContent = `R$ ${parseFloat(carrinho.totalPreco).toFixed(2).replace(".", ",")}`;

                // --- FIM DA CORREÇÃO ---

            } catch (error) {
                console.error("Erro ao carregar carrinho:", error);
                orderItemsContainer.innerHTML = '<p>Erro ao carregar seu carrinho. Tente novamente.</p>';
                btnFinalizar.disabled = true;
            }

            btnFinalizar.addEventListener('click', async (e) => {
                e.preventDefault();

                const form = document.getElementById('checkout-form');
                if (!form.checkValidity()) {
                    alert('Por favor, preencha todos os campos obrigatórios!');
                    form.reportValidity();
                    return;
                }
                
                btnFinalizar.textContent = 'Processando...';
                btnFinalizar.disabled = true;

                try {
                    const response = await fetch(`${baseURL}/api/pedido/finalizar`, {
                        method: 'POST'
                    });
                    
                    const result = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(result.erro || 'Não foi possível finalizar o pedido');
                    }
                    
                    const pedidoId = result.pedidoId;
                    window.location.href = `${baseURL}/pedido_confirmado?id=${pedidoId}`;

                } catch (error) {
                    console.error("Erro ao finalizar pedido:", error);
                    alert(`Erro: ${error.message}`);
                    btnFinalizar.textContent = 'Confirmar Pedido';
                    btnFinalizar.disabled = false;
                }
            });

            const paymentMethods = document.querySelectorAll('.payment-method');
            const cardInfo = document.getElementById('card-info');
            
            paymentMethods.forEach(method => {
                method.addEventListener('click', () => {
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    method.classList.add('active');
                    const selectedMethod = method.getAttribute('data-method');

                    if (selectedMethod === 'pix') {
                        cardInfo.style.display = 'none';
                    } else {
                        cardInfo.style.display = 'block';
                    }
                });
            });
        });
    </script>

    <div id="sidecart-overlay" class="sidecart-overlay"></div>
    <div id="sidecart" class="sidecart">
        <div class="sidecart-container"> 
            <div class="sidecart-header">
                <h1>Carrinho</h1>
                <button id="close-cart-btn" class="close-cart-btn">&times;</button> 
            </div>

            <div id="sidecart-items" class="sidecart-items">
                <p>Seu carrinho está vazio.</p> 
            </div>

            <div class="sidecart-footer"> 
                <p id="sidecart-total">Tudo: <span>R$ 0,00</span></p>
                <a href="<?= BASE_URL ?>/checkout" id="sidecart-checkout-btn" class="finalize-btn">FINALIZAR (0)</a>
                <p class="free-shipping" style="display: none;">Elegível para o TRANSPORTE LIVRE!</p>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/js/sidecart.js"></script>

</body>
</html>