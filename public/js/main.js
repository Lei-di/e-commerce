document.addEventListener("DOMContentLoaded", () => {
  const listaProdutos = document.querySelector(".lista-produtos");

  // Função para renderizar produtos recebidos da API
  function renderProdutos(produtos) {
    if (!produtos || produtos.length === 0) {
      listaProdutos.innerHTML = "<p>Nenhum produto encontrado.</p>";
      return;
    }

    listaProdutos.innerHTML = produtos.map(p => `
      <div class="produto">
        <div class="foto-produto">
          <img src="/assets/imagens/${p.imagem}" alt="${p.nome}">
          <button class="btn-comprar">ADICIONAR AO CARRINHO</button>
        </div>
        <div class="info-produto">
          <p>${p.categoria}</p>
          <h4>${p.nome}</h4>
          <h3>R$ ${parseFloat(p.preco).toFixed(2).replace('.', ',')}</h3>
        </div>
      </div>
    `).join("");
  }

  // Função que busca produtos no backend
  async function filtrarProdutos(params = {}) {
    let url = '/api/produtos';

    // Se tiver categoria ou preço, usamos endpoints específicos
    if (params.categoria) {
      url = `/api/produtos/buscar/${encodeURIComponent(params.categoria)}`;
    } else if (params.preco) {
      const [min, max] = params.preco.split('-');
      url = `/api/produtos/preco/${min}/${max}`;
    }

    const response = await fetch(url);
    const data = await response.json();
    renderProdutos(data);
  }

  // Inicializa lista com todos os produtos
  filtrarProdutos();

  // Filtro por categoria (checkboxes ou select)
  const filtroCategoria = document.getElementById("filtroCategoria");
  if (filtroCategoria) {
    filtroCategoria.addEventListener("change", e => {
      filtrarProdutos({ categoria: e.target.value });
    });
  }

  // Filtro por preço (ex: "0-100", "100-200")
  const filtroPreco = document.getElementById("filtroPreco");
  if (filtroPreco) {
    filtroPreco.addEventListener("change", e => {
      filtrarProdutos({ preco: e.target.value });
    });
  }
});
