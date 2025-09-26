document.addEventListener("DOMContentLoaded", () => {
  const listaProdutos = document.querySelector(".lista-produtos");
  const categoryLinks = document.querySelectorAll(".category-link");

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

  async function filtrarProdutos(params = {}) {
    let url = `${baseURL}/api/produtos`;

    // AQUI ESTAVA O ERRO - AGORA CORRIGIDO
    if (params.categoria) {
      // Usar a rota correta para buscar por categoria
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

  categoryLinks.forEach(link => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const category = link.dataset.category;
      filtrarProdutos({ categoria: category });
    });
  });

  // Mantém a inicialização com todos os produtos
  filtrarProdutos({ categoria: 'novidades' });
});