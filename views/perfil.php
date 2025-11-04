<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/perfil.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidecart.css">
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
                <p>Nenhuma opção de pagamento cadastrada.</p>
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
    </script>
    <script src="<?= BASE_URL ?>/js/sidecart.js"></script>

</body>
</html>