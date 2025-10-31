document.addEventListener("DOMContentLoaded", () => {
  const listaProdutos = document.querySelector(".lista-produtos");
  const categoryLinks = document.querySelectorAll(".category-link");

  function renderProdutos(produtos) {
    // Verifica se o elemento onde os produtos serão listados existe
    if (!listaProdutos) {
        console.error("Elemento .lista-produtos não encontrado.");
        return;
    }

    if (!produtos || produtos.length === 0) {
      // MUDANÇA: Mensagem mais genérica
      listaProdutos.innerHTML = "<p>Nenhum produto encontrado.</p>";
      return;
    }

    listaProdutos.innerHTML = produtos.map(p => `
      <div class="produto">
        <div class="foto-produto">
          <img src="${baseURL}/assets/imagens/${p.imagem}" alt="${p.nome}">
          <button class="btn-comprar" data-id-produto="${p.id}">ADICIONAR AO CARRINHO</button>
        </div>
        <div class="info-produto">
          <p>${(p.categoria || 'CATEGORIA').toUpperCase()}</p>
          <h4>${p.nome}</h4>
          <h3>R$ ${parseFloat(p.preco).toFixed(2).replace('.', ',')}</h3>
        </div>
      </div>
    `).join("");

    // <-- ADICIONADO: Chama a função para adicionar eventos aos botões recém-criados -->
    addEventListenersToBuyButtons();
  }

  // --- NOVA FUNÇÃO ---  // <-- ADICIONADO: Função inteira -->
  // Adiciona o evento de clique aos botões "ADICIONAR AO CARRINHO"
  function addEventListenersToBuyButtons() {
    const buyButtons = document.querySelectorAll('.btn-comprar');

    buyButtons.forEach(button => {
        // Remove eventuais listeners antigos para evitar duplicação, clonando o botão
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);

        // Adiciona o novo listener
        newButton.addEventListener('click', (e) => {
            const produtoId = e.target.dataset.idProduto; // Pega o ID do produto do atributo data-*

            // Verifica se a função apiAdicionarAoCarrinho (do sidecart.js) está disponível
            if (typeof window.apiAdicionarAoCarrinho === 'function') {
              // Chama a função do sidecart.js, passando o ID do produto
              window.apiAdicionarAoCarrinho(produtoId);
            } else {
              // Mensagem de erro caso sidecart.js não tenha sido carregado corretamente
              console.error("Função apiAdicionarAoCarrinho não encontrada. Verifique se sidecart.js está incluído e carregado ANTES de main.js.");
              alert("Erro ao adicionar produto ao carrinho.");
            }
        });
    });
  }
  // --- FIM DA NOVA FUNÇÃO --- // <-- ADICIONADO -->


  // --- FUNÇÃO MODIFICADA ---
  async function filtrarProdutos(params = {}) {
    let url = `${baseURL}/api/produtos`;
    
    // NOVO: Seleciona o H1 para atualizar o título
    const tituloH1 = document.querySelector('.area-produto h1');

    // Se uma categoria foi passada E não é 'novidades' (que significa 'todos')
    if (params.categoria && params.categoria !== 'novidades') {
      // Usar a rota correta para buscar por categoria
      url = `${baseURL}/api/produtos/categoria/${encodeURIComponent(params.categoria)}`;
      // NOVO: Atualiza o título H1
      if(tituloH1) tituloH1.textContent = params.categoria.charAt(0).toUpperCase() + params.categoria.slice(1);

    } else if (params.busca) { // <-- MUDANÇA AQUI: Adicionado 'else if' para busca
        url = `${baseURL}/api/produtos/buscar/${encodeURIComponent(params.busca)}`;
        // NOVO: Atualiza o título H1
        if(tituloH1) tituloH1.textContent = `Busca por: "${params.busca}"`;

    } else if (params.preco) { // Lógica de preço mantida como estava
      const [min, max] = params.preco.split('-');
      url = `${baseURL}/api/produtos/preco/${min}/${max}`;
    } else {
        // NOVO: Garante um título padrão (caso params.categoria seja 'novidades')
        if(tituloH1) tituloH1.textContent = "Novidades";
    }

    try {
       // <-- ADICIONADO: Verifica se listaProdutos existe e mostra feedback -->
      if (!listaProdutos) return;
      listaProdutos.innerHTML = '<p>Carregando produtos...</p>';

      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Erro na requisição: ${response.statusText}`);
      }
      const data = await response.json();
      renderProdutos(data); // Renderiza os produtos recebidos
    } catch (error) {
      console.error("Falha ao buscar produtos:", error);
       // <-- ADICIONADO: Verifica listaProdutos antes de alterar -->
      if (listaProdutos) listaProdutos.innerHTML = "<p>Ocorreu um erro ao carregar os produtos.</p>";
    }
  }

  // Event listener para os links de categoria (sem alterações)
  categoryLinks.forEach(link => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const category = link.dataset.category;
      // <-- A atualização do H1 agora é feita dentro de filtrarProdutos -->
      filtrarProdutos({ categoria: category });
    });
  });

  // --- BLOCO MODIFICADO ---
  // --- CARREGAMENTO INICIAL --- // 
  const urlParams = new URLSearchParams(window.location.search);
  
  const termoBusca = urlParams.get('busca');
  const categoriaInicial = urlParams.get('categoria') || 'novidades';

  if (termoBusca) {
      // Se houver um termo de busca, filtra por ele
      filtrarProdutos({ busca: termoBusca });
  } else {
      // Senão, usa a lógica de categoria que já existia
      // (Isso também atualiza o título H1 inicial corretamente)
      filtrarProdutos({ categoria: categoriaInicial });
  }
  // --- FIM DO CARREGAMENTO INICIAL --- //

}); // Fim do DOMContentLoaded