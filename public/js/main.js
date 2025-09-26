document.addEventListener("DOMContentLoaded", () => {
  const listaProdutos = document.querySelector(".lista-produtos");
  const categoryLinks = document.querySelectorAll(".category-link"); // Seleciona os links de categoria

  // Função para renderizar produtos recebidos da API
  function renderProdutos(produtos) {
    if (!produtos || produtos.length === 0) {
      listaProdutos.innerHTML = "<p>Nenhum produto encontrado para esta categoria.</p>";
      return;
    }

    listaProdutos.innerHTML = produtos.map(p => `
      <div class="produto">
        <div class="foto-produto">
          <img src="${baseURL}/assets/imagens/${p.imagem}" alt="${p.nome}">
          <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
        </div>
        <div class="info-produto">
          <p>${p.categoria.toUpperCase()}</p>
          <h4>${p.nome}</h4>
          <h3>R$ ${parseFloat(p.preco).toFixed(2).replace('.', ',')}</h3>
        </div>
      </div>
    `).join("");
  }

  // Função que busca produtos no backend
  async function filtrarProdutos(params = {}) {
    let url = `${baseURL}/api/produtos`; // URL padrão

    // Se uma categoria foi passada, monta a URL correta para a API de categorias
    if (params.categoria) {
      // CORREÇÃO: A URL da sua API de categoria é '/api/produtos/categoria/...'
      url = `${baseURL}/api/produtos/categoria/${encodeURIComponent(params.categoria)}`;
    } else if (params.preco) {
      const [min, max] = params.preco.split('-');
      url = `${baseURL}/api/produtos/preco/${min}/${max}`;
    }

    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Erro na requisição: ${response.statusText}`);
      }
      const data = await response.json();
      renderProdutos(data);
    } catch (error) {
      console.error("Falha ao buscar produtos:", error);
      listaProdutos.innerHTML = "<p>Ocorreu um erro ao carregar os produtos.</p>";
    }
  }

  // NOVO: Adiciona o evento de clique para cada link de categoria
  categoryLinks.forEach(link => {
    link.addEventListener("click", (event) => {
      event.preventDefault(); // Impede o recarregamento da página
      const category = link.dataset.category; // Pega a categoria do atributo data-category
      
      // Chama a função de filtro com a categoria clicada
      filtrarProdutos({ categoria: category });
    });
  });


  // Inicializa a lista com todos os produtos ("Novidades")
  filtrarProdutos({ categoria: 'novidades' });

});