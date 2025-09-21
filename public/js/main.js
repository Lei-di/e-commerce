document.addEventListener('DOMContentLoaded', function() {
    const listaProdutos = document.querySelector('.lista-produtos');
    const tituloCategoria = document.querySelector('.area-produto h1'); // Seleciona o título "Todos"

    // Pega os parâmetros da URL
    const params = new URLSearchParams(window.location.search);
    const categoria = params.get('categoria');

    let apiUrl = `${baseURL}/api/produtos`;

    // Se houver uma categoria na URL, adiciona à chamada da API
    if (categoria) {
        apiUrl += `?categoria=${categoria}`;
        // Atualiza o título da página com o nome da categoria (primeira letra maiúscula)
        tituloCategoria.textContent = categoria.charAt(0).toUpperCase() + categoria.slice(1);
    } else {
        tituloCategoria.textContent = "Todos";
    }

    // Usa a baseURL (definida no HTML da pagina_principal.php) para montar os endereços corretos
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('A resposta da rede não foi OK');
            }
            return response.json();
        })
        .then(produtos => {
            listaProdutos.innerHTML = ''; // Limpa a lista
            if (produtos.length === 0) {
                 listaProdutos.innerHTML = '<p>Nenhum produto encontrado nesta categoria.</p>';
                 return;
            }
            produtos.forEach(produto => {
                // Cria o HTML para cada produto e insere na página
                const produtoHTML = `
                    <div class="produto">
                        <div class="foto-produto">
                            <img src="${baseURL}/assets/imagens/${produto.imagem}" alt="${produto.nome}">
                            <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
                        </div>
                        <div class="info-produto">
                            <p>${(produto.categoria || 'CATEGORIA').toUpperCase()}</p>
                            <h4>${produto.nome}</h4>
                            <h3>R$ ${parseFloat(produto.preco).toFixed(2)}</h3>
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
