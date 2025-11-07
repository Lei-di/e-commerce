document.addEventListener('DOMContentLoaded', () => {
    // --- 1. Seletores do DOM ---
    const cartIcon = document.getElementById('carrinho'); // Ícone no cabeçalho
    const sidecartPanel = document.getElementById('sidecart'); // O painel
    const overlay = document.getElementById('sidecart-overlay'); // O fundo escuro
    const closeBtn = document.getElementById('close-cart-btn'); // Botão 'X'
    const itemsContainer = document.getElementById('sidecart-items'); // Onde os itens aparecem
    const totalContainer = document.getElementById('sidecart-total'); // Onde o total aparece
    const checkoutBtn = document.getElementById('sidecart-checkout-btn'); // Botão Finalizar
    const freeShippingMsg = document.querySelector('.free-shipping'); // Mensagem de frete

    // Checa se os elementos essenciais existem
    if (!cartIcon || !sidecartPanel || !overlay || !closeBtn || !itemsContainer || !totalContainer || !checkoutBtn) {
        console.error("Um ou mais elementos do sidecart não foram encontrados no DOM. Verifique os IDs no HTML.");
        return; // Impede a execução se algo estiver faltando
    }

    // --- 2. Funções de Abrir/Fechar ---
    function openCart() {
        // Primeiro, atualiza o conteúdo buscando da API
        updateCartView();
        // Depois, mostra o painel e o overlay
        sidecartPanel.classList.add('open');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden'; // Opcional: Trava o scroll da página principal
    }

    function closeCart() {
        sidecartPanel.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = ''; // Libera o scroll
    }

    // --- 3. Funções de API (Fetch) ---

    // ADICIONAR ITEM (Função global para ser chamada pelo main.js)
    // Modificada para aceitar 'tamanho'
    window.apiAdicionarAoCarrinho = async (produtoId, tamanho = null) => {
        try {
            const response = await fetch(`${baseURL}/api/carrinho/adicionar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    produto_id: produtoId,
                    quantidade: 1,
                    tamanho: tamanho // Envia o tamanho
                })
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.erro || 'Erro ao adicionar item.');

            openCart(); // Abre o carrinho já atualizado

        } catch (error) {
            console.error("Erro em apiAdicionarAoCarrinho:", error.message);
            alert(`Não foi possível adicionar o item: ${error.message}`);
        }
    };

    // ATUALIZAR QUANTIDADE
    async function apiAtualizarQuantidade(cartItemId, novaQuantidade) {
        // Se a quantidade for zero ou menos, chama a API de remover
        if (novaQuantidade <= 0) {
            await apiRemoverDoCarrinho(cartItemId);
            return;
        }
        try {
            const response = await fetch(`${baseURL}/api/carrinho/atualizar`, {
                method: 'PUT', // O backend espera PUT
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    cart_item_id: cartItemId, // Usa a chave única
                    quantidade: novaQuantidade
                })
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.erro || 'Erro ao atualizar quantidade.');

            updateCartView(); // Atualiza a visualização após sucesso
        } catch (error) {
            console.error("Erro em apiAtualizarQuantidade:", error.message);
            alert(`Não foi possível atualizar: ${error.message}`);
        }
    }

    // REMOVER ITEM
    async function apiRemoverDoCarrinho(cartItemId) {
        try {
            const response = await fetch(`${baseURL}/api/carrinho/remover`, {
                method: 'POST', // O backend espera POST
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_item_id: cartItemId }) // Usa a chave única
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.erro || 'Erro ao remover item.');

            updateCartView(); // Atualiza a visualização após sucesso
        } catch (error) {
            console.error("Erro em apiRemoverDoCarrinho:", error.message);
            alert(`Não foi possível remover: ${error.message}`);
        }
    }

    // --- 4. Atualizar a Visualização do Carrinho (Função principal) ---
    async function updateCartView() {
        try {
            itemsContainer.innerHTML = '<p>Carregando carrinho...</p>'; // Feedback visual
            checkoutBtn.style.display = 'none'; // Esconde botão enquanto carrega

            const response = await fetch(`${baseURL}/api/carrinho/ver`); // Busca dados da API
            if (!response.ok) throw new Error('Falha ao buscar dados do carrinho.');

            const data = await response.json();
            const carrinho = data.carrinho; // A API agora retorna { carrinho: { ... } }

            itemsContainer.innerHTML = ''; // Limpa o "Carregando..."

            if (!carrinho || !carrinho.itens || carrinho.itens.length === 0) {
                itemsContainer.innerHTML = '<p>Seu carrinho está vazio.</p>';
                totalContainer.querySelector('span').textContent = 'R$ 0,00';
                checkoutBtn.textContent = 'FINALIZAR (0)'; // Atualiza o botão
                checkoutBtn.style.display = 'block';
                checkoutBtn.href = '#'; // Impede de ir pro checkout vazio
                if (freeShippingMsg) freeShippingMsg.style.display = 'none'; // Esconde frete grátis
            } else {

                carrinho.itens.forEach(item => {
                    // --- ADICIONADO: Exibe o tamanho se ele existir ---
                    const tamanhoHtml = item.tamanho ? `<p class="item-tamanho" style="font-size: 0.9em; color: #555;">Tamanho: <strong>${item.tamanho}</strong></p>` : '';

                    // Cria o HTML para cada item
                    // --- MODIFICADO: Usa 'cart_item_id' para os botões ---
                    itemsContainer.innerHTML += `
                        <div class="cart-item" data-id="${item.id}">
                            <img src="${baseURL}/assets/imagens/${item.imagem}" alt="${item.nome}">
                            <div class="item-details">
                                <p class="item-name">${item.nome}</p>
                                ${tamanhoHtml} <p class="item-price">R$ ${parseFloat(item.preco).toFixed(2).replace('.', ',')}</p>
                            </div>
                            <div class="item-quantity">
                                <button class="qty-decrease" data-cart-item-id="${item.cart_item_id}">-</button>
                                <span>${item.quantidade}</span>
                                <button class="qty-increase" data-cart-item-id="${item.cart_item_id}">+</button>
                            </div>
                             <button class="remove-item-btn" data-cart-item-id="${item.cart_item_id}" style="background:none; border:none; color:red; font-size:1.2em; cursor:pointer; margin-left: 10px;">&times;</button>
                        </div>`;
                });

                // Atualiza o total e o botão
                totalContainer.querySelector('span').textContent = `R$ ${parseFloat(carrinho.totalPreco).toFixed(2).replace('.', ',')}`;
                checkoutBtn.textContent = `FINALIZAR (${carrinho.totalItens})`; // Mostra contagem
                checkoutBtn.style.display = 'block';
                checkoutBtn.href = `${baseURL}/checkout`; // Link correto

                // Mostra frete grátis (exemplo de condição)
                if (carrinho.totalPreco > 100 && freeShippingMsg) {
                    freeShippingMsg.style.display = 'block';
                } else if (freeShippingMsg) {
                    freeShippingMsg.style.display = 'none';
                }

                // Adiciona os eventos aos botões de quantidade e remoção DEPOIS de criar os itens
                addCartItemEvents();
            }

        } catch (error) {
            console.error("Erro ao atualizar a visualização do carrinho:", error);
            itemsContainer.innerHTML = '<p>Erro ao carregar o carrinho. Tente novamente.</p>';
        }
    }

    // --- 5. Adicionar Eventos aos Botões Internos do Carrinho ---
    function addCartItemEvents() {
        // Botões de Aumentar Quantidade (+)
        itemsContainer.querySelectorAll('.qty-increase').forEach(button => {
            button.replaceWith(button.cloneNode(true)); // Limpa listeners antigos
        });
        itemsContainer.querySelectorAll('.qty-increase').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.target.dataset.cartItemId; // Usa a chave única
                const itemDiv = e.target.closest('.cart-item');
                const qtySpan = itemDiv.querySelector('.item-quantity span');
                const currentQty = parseInt(qtySpan.textContent);
                apiAtualizarQuantidade(id, currentQty + 1);
            });
        });

        // Botões de Diminuir Quantidade (-)
        itemsContainer.querySelectorAll('.qty-decrease').forEach(button => {
            button.replaceWith(button.cloneNode(true)); // Limpa listeners antigos
        });
        itemsContainer.querySelectorAll('.qty-decrease').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.target.dataset.cartItemId; // Usa a chave única
                const itemDiv = e.target.closest('.cart-item');
                const qtySpan = itemDiv.querySelector('.item-quantity span');
                const currentQty = parseInt(qtySpan.textContent);
                apiAtualizarQuantidade(id, currentQty - 1); // A API trata se for <= 0
            });
        });

        // Botões de Remover Item (X)
        itemsContainer.querySelectorAll('.remove-item-btn').forEach(button => {
            button.replaceWith(button.cloneNode(true)); // Limpa listeners antigos
        });
        itemsContainer.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.target.dataset.cartItemId; // Usa a chave única
                if (confirm('Tem certeza que deseja remover este item?')) {
                    apiRemoverDoCarrinho(id);
                }
            });
        });
    }

    // --- 6. Eventos Principais (Abrir/Fechar) ---
    if (cartIcon) {
        cartIcon.addEventListener('click', (e) => {
            e.preventDefault();
            openCart();
        });
    }
    if (closeBtn) closeBtn.addEventListener('click', closeCart);
    if (overlay) overlay.addEventListener('click', closeCart);


    // --- 8. CARREGAMENTO INICIAL ---
    // Atualiza a visualização do carrinho ao carregar a página (sem abrir)
    updateCartView();

});