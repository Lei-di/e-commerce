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
                
                <div id="dados-view" style="margin-top: 20px;">
                    <?php if (isset($usuario)): ?>
                        <div class="info-perfil">
                            <h4>Nome Completo:</h4>
                            <p><?= htmlspecialchars($usuario['nome']) ?></p>
                        </div>
                        <div class="info-perfil">
                            <h4>Telefones:</h4>
                            <p><?= htmlspecialchars($usuario['telefone']) ?></p>
                        </div>
                        <div class="info-perfil">
                            <h4>E-mail:</h4>
                            <p><?= htmlspecialchars($usuario['email']) ?></p>
                        </div>
                        <div class="info-perfil">
                            <h4>CPF:</h4>
                            <p><?= htmlspecialchars($usuario['cpf']) ?></p>
                        </div>
                        
                        <div class="info-perfil">
                            <h4>Endereços:</h4>
                            
                            <?php if (isset($usuario['enderecos']) && !empty($usuario['enderecos'])): ?>
                                <?php foreach ($usuario['enderecos'] as $endereco): ?>
                                    <p style="margin-bottom: 15px; padding-bottom: 5px; border-bottom: 1px solid #f0f0f0;">
                                        <?= htmlspecialchars($endereco['logradouro']) ?>, 
                                        <?= htmlspecialchars($endereco['numero']) ?>
                                        <?php if (!empty($endereco['complemento'])): ?>
                                            , <?= htmlspecialchars($endereco['complemento']) ?>
                                        <?php endif; ?>
                                        <br> <?= htmlspecialchars($endereco['bairro']) ?> 
                                        - <?= htmlspecialchars($endereco['cidade']) ?>
                                        - <?= htmlspecialchars($endereco['estado']) ?>
                                        - <?= htmlspecialchars($endereco['cep']) ?>
                                    </p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum endereço cadastrado.</p>
                            <?php endif; ?>

                            <button id="btn-novo-endereco" class="btn-outline" style="margin-top: 10px;">ADICIONAR MAIS UM ENDEREÇO</button>

                            <div id="form-novo-endereco" style="display: none; background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px dashed #ccc;">
                                <h4>Novo Endereço</h4>
                                <form id="form-salvar-endereco">
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="apelido" style="display: block; margin-bottom: 5px;">Apelido (ex: Casa, Trabalho)*</label>
                                        <input type="text" id="apelido" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="cep-novo" style="display: block; margin-bottom: 5px;">CEP *</label>
                                        <input type="text" id="cep-novo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="logradouro-novo" style="display: block; margin-bottom: 5px;">Rua/Logradouro *</label>
                                        <input type="text" id="logradouro-novo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="numero-novo" style="display: block; margin-bottom: 5px;">Número *</label>
                                        <input type="text" id="numero-novo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="complemento-novo" style="display: block; margin-bottom: 5px;">Complemento</label>
                                        <input type="text" id="complemento-novo" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="bairro-novo" style="display: block; margin-bottom: 5px;">Bairro *</label>
                                        <input type="text" id="bairro-novo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="cidade-novo" style="display: block; margin-bottom: 5px;">Cidade *</label>
                                        <input type="text" id="cidade-novo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="estado-novo" style="display: block; margin-bottom: 5px;">Estado (UF) *</label>
                                        <input type="text" id="estado-novo" maxlength="2" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>

                                    <button type="submit" class="btn-primary" style="background: #f1960c; color: #fff; border: 0; padding: 10px 15px; cursor: pointer; border-radius: 8px;">Salvar Endereço</button>
                                    <button type="button" id="btn-cancelar-endereco" class="btn-outline" style="margin-left: 10px;">Cancelar</button>
                                </form>
                            </div>
                        </div>
                    
                    <?php else: ?>
                        <p>Erro ao carregar dados do usuário.</p>
                    <?php endif; ?>
                    
                    <button id="btn-editar-perfil" class="btn-primary" style="margin-top: 15px;">
                        Editar Dados
                    </button>
                </div>

                <div id="dados-edit" style="display: none; margin-top: 20px;">
                    <form id="form-salvar-perfil">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="nome-edit" style="display: block; margin-bottom: 5px;">Nome Completo *</label>
                            <input type="text" id="nome-edit" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required style="width: 100%; padding: 8px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="telefone-edit" style="display: block; margin-bottom: 5px;">Telefone *</label>
                            <input type="tel" id="telefone-edit" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>" required style="width: 100%; padding: 8px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="email-edit" style="display: block; margin-bottom: 5px;">E-mail *</label>
                            <input type="email" id="email-edit" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required style="width: 100%; padding: 8px;">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 15px; display:none;">
                            <label for="nasc-edit" style="display: block; margin-bottom: 5px;">Data de Nascimento *</label>
                            <input type="text" id="nasc-edit" value="" style="width: 100%; padding: 8px;">
                        </div>

                        <button type="submit" class="btn-primary" style="background: #000; color: #fff; border: 0; padding: 10px 15px; cursor: pointer; border-radius: 8px;">Salvar Alterações</button>
                        <button type="button" id="btn-cancelar-edicao" style="background: none; border: 1px solid #ccc; padding: 10px 15px; cursor: pointer; border-radius: 8px;">Cancelar</button>
                    </form>
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