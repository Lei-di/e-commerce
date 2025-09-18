document.addEventListener('DOMContentLoaded', function() {
    const listaProdutos = document.querySelector('.lista-produtos');

    // Usa a baseURL (definida no HTML) para montar o endereço correto da API
    fetch(`${baseURL}/api/produtos`)
        .then(response => {
            if (!response.ok) {
                throw new Error('A resposta da rede não foi OK');
            }
            return response.json();
        })
        .then(produtos => {
            listaProdutos.innerHTML = ''; // Limpa a lista
            produtos.forEach(produto => {
                // Cria o HTML para cada produto e insere na página
                const produtoHTML = `
                    <div class="produto">
                        <div class="foto-produto">
                            <img src="${baseURL}/assets/imagens/${produto.imagem}" alt="${produto.nome}">
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
        .catch(error => {
            console.error('Erro ao buscar produtos:', error);
            listaProdutos.innerHTML = '<p>Não foi possível carregar os produtos. Tente novamente mais tarde.</p>';
        });
});