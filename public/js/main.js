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
        <h3>${p.nome}</h3>
        <p>Categoria: ${p.categoria}</p>
        <p>Preço: R$ ${parseFloat(p.preco).toFixed(2)}</p>
      </div>
    `).join("");
  }

  // Função que busca no backend
  async function filtrarProdutos(params = {}) {
    const query = new URLSearchParams(params).toString();
    const response = await fetch(`/api/produtos?${query}`);
    const data = await response.json();
    renderProdutos(data);
  }

  // Exemplo: filtro por categoria (select com id="filtroCategoria")
  const filtroCategoria = document.getElementById("filtroCategoria");
  if (filtroCategoria) {
    filtroCategoria.addEventListener("change", e => {
      filtrarProdutos({ categoria: e.target.value });
    });
  }

  // Exemplo: filtro por preço (select com id="filtroPreco")
  const filtroPreco = document.getElementById("filtroPreco");
  if (filtroPreco) {
    filtroPreco.addEventListener("change", e => {
      filtrarProdutos({ preco: e.target.value });
    });
  }
});
