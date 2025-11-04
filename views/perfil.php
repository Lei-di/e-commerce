<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/perfil.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidecart.css">
    <style>
        /* Estilos para as formas de pagamento */
        .payment-methods { 
            display: flex; 
            flex-direction: column; 
            gap: 16px; 
            margin-top: 12px; 
        }
        .payment-card { 
            border: 1px solid #eee; 
            border-radius: 10px; 
            padding: 16px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .payment-card:hover {
            border-color: #ccc;
        }
        .payment-card input[type="radio"]:checked + .payment-info {
            color: #000;
        }
        .payment-left { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
        }
        .payment-left img { 
            width: 40px; 
            height: 26px; 
            object-fit: contain; 
        }
        .payment-actions { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        .btn-outline { 
            background: #fff; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            padding: 6px 10px; 
            cursor: pointer;
            font-size: 12px;
        }
        .btn-outline:hover {
            background: #f5f5f5;
        }
        .btn-primary { 
            background: #000; 
            color: #fff; 
            border: 0; 
            border-radius: 8px; 
            padding: 8px 14px; 
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary:hover {
            background: #333;
        }
        .saved-badge { 
            font-size: 12px; 
            color: #888; 
            margin-top: 2px;
        }
        .payment-info {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/cabecalho.php'; ?>

    <main class="profile-main-content">
        <div class="profile-sidebar-wrapper">
            <aside class="profile-sidebar">
                <nav class="profile-nav">
                    <h2>MINHA CONTA</h2>
                    <ul>
                        <li><a href="#" class="tab-link active" data-tab="meu-perfil">Meu Perfil +</a></li>
                        <li><a href="#" class="tab-link" data-tab="meus-pedidos">Meus Pedidos +</a></li>
                        <li><a href="#" class="tab-link" data-tab="opcoes-pagamento">Opções de pagamento +</a></li>
                    </ul>
                </nav>
            </aside>
        </div>

        <section class="profile-content-area">
            <div id="meu-perfil" class="tab-content active">
                <h3>MEU PERFIL</h3>
                <p>Informações do seu perfil.</p>
                <div class="conteudo-perfil">
                    <div class="conteudo-perfil">
                        <div class="conteudo-perfil-dados">
                            <div class="info-perfil">
                                <h4>Nome Completo:</h4>
                                <p>João da Silva</p>
                            </div>

                            <div class="info-perfil">
                                <h4>Telefones:</h4>
                                <p>11 98765-4321</p>
                            </div>

                            <div class="info-perfil">
                                <h4>E-mail:</h4>
                                <p>joao.silva@email.com</p>
                            </div>

                            <div class="info-perfil">
                                <h4>Data de Nascimento:</h4>
                                <p>15/08/1990</p>
                            </div>
                        </div>

                        <div class="enderecos">
                            <h4 style="margin-top: 20px !important;">Endereços:</h4>
                            <div class="conteudo-endereco">
                                <p><strong>Residencial:</strong> Rua das Flores, 123, Apto 202, Centro - SP</p>
                            </div>
                            <div class="conteudo-endereco">
                                <p><strong>Comercial:</strong> Avenida Paulista, 1000, Sala 301, Bela Vista - SP</p>
                            </div>
                            <button>ADICIONAR MAIS UM ENDEREÇO</button>
                        </div>

                    </div>
                </div>
            </div>
            
            <div id="meus-pedidos" class="tab-content">
                <h3>MEUS PEDIDOS</h3>
                <p>Nenhum pedido encontrado.</p>
            </div>

            <div id="opcoes-pagamento" class="tab-content">
                <h3>OPÇÕES DE PAGAMENTO</h3>
                <p>Selecione uma forma de pagamento preferida:</p>
                
                <div class="payment-methods">
                    <label class="payment-card">
                        <div class="payment-left">
                            <input type="radio" name="payment" value="credito" checked>
                            <img src="<?= BASE_URL ?>/assets/icones/credito.png" alt="Crédito" onerror="this.style.display='none'">
                            <div class="payment-info">
                                <strong>Cartão de Crédito</strong>
                                <div class="saved-badge">Visa, Mastercard, Elo</div>
                            </div>
                        </div>
                        <div class="payment-actions">
                            <button class="btn-outline" type="button" onclick="alert('Função de editar cartão de crédito (simulação)')">Editar</button>
                        </div>
                    </label>

                    <label class="payment-card">
                        <div class="payment-left">
                            <input type="radio" name="payment" value="debito">
                            <img src="<?= BASE_URL ?>/assets/icones/debito.png" alt="Débito" onerror="this.style.display='none'">
                            <div class="payment-info">
                                <strong>Cartão de Débito</strong>
                                <div class="saved-badge">Visa, Mastercard, Elo</div>
                            </div>
                        </div>
                        <div class="payment-actions">
                            <button class="btn-outline" type="button" onclick="alert('Função de editar cartão de débito (simulação)')">Editar</button>
                        </div>
                    </label>

                    <label class="payment-card">
                        <div class="payment-left">
                            <input type="radio" name="payment" value="pix">
                            <img src="<?= BASE_URL ?>/assets/icones/pix.png" alt="Pix" onerror="this.style.display='none'">
                            <div class="payment-info">
                                <strong>Pix</strong>
                                <div class="saved-badge">Chave aleatória será gerada no checkout</div>
                            </div>
                        </div>
                        <div class="payment-actions">
                            <button class="btn-outline" type="button" onclick="alert('Instruções Pix:\n1. Será gerado QR Code\n2. Ou chave aleatória\n3. Pagamento instantâneo')">Instruções</button>
                        </div>
                    </label>

                    <div style="margin-top: 20px;">
                        <button class="btn-primary" type="button" onclick="salvarPreferenciaPagamento()">Salvar Preferência</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="<?= BASE_URL ?>/js/perfil.js"></script>

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

    <script>
        // Passa a URL base do PHP para o JavaScript
        const baseURL = "<?= BASE_URL ?>";
        
        // Função para salvar preferência de pagamento (simulação)
        function salvarPreferenciaPagamento() {
            const selectedPayment = document.querySelector('input[name="payment"]:checked');
            if (selectedPayment) {
                const paymentType = selectedPayment.value;
                let message = '';
                
                switch(paymentType) {
                    case 'credito':
                        message = 'Cartão de Crédito salvo como preferência!';
                        break;
                    case 'debito':
                        message = 'Cartão de Débito salvo como preferência!';
                        break;
                    case 'pix':
                        message = 'Pix salvo como preferência!';
                        break;
                }
                
                alert(message + '\n(Esta é uma simulação)');
            }
        }
    </script>
    <script src="<?= BASE_URL ?>/js/sidecart.js"></script>

</body>
</html>
