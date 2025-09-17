document.addEventListener('DOMContentLoaded', function() {
        const listaProdutos = document.querySelector('.lista-produtos');

        // Faz uma chamada para a API de produtos
        fetch('/api/produtos')
            .then(response => response.json())
            .then(produtos => {
                listaProdutos.innerHTML = ''; // Limpa a lista
                produtos.forEach(produto => {
                    // Cria o HTML para cada produto e insere na página
                    const produtoHTML = `
                        <div class="produto">
                            <div class="foto-produto">
                                <img src="assets/imagens/${produto.imagem}" alt="${produto.nome}">
                                <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                            </div>
                            <div class="info-produto">
                                <p>CATEGORIA</p>
                                <h4>${produto.nome}</h4>
                                <h3>R$ ${produto.preco.toFixed(2)}</h3>
                            </div>
                        </div>`;
                    listaProdutos.innerHTML += produtoHTML;
                });
            })
            .catch(error => console.error('Erro ao buscar produtos:', error));
    });
