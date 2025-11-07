document.addEventListener("DOMContentLoaded", () => {
  const listaProdutos = document.querySelector(".lista-produtos");
  const categoryLinks = document.querySelectorAll(".category-link");

  // --- NOVO: rolar para a seção de produtos quando trocar de categoria/buscar/aplicar filtros ---
  const areaProduto = document.querySelector('.area-produto');
  function scrollToProdutos() {
    if (areaProduto) {
      areaProduto.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  // --- NOVO: captura do formulário de busca do cabeçalho ---
  const formBusca = document.getElementById('form-busca');
  const inputBusca = document.getElementById('input-busca');
  if (formBusca && inputBusca) {
    formBusca.addEventListener('submit', (e) => {
      e.preventDefault();
      const termo = (inputBusca.value || '').trim();
      if (termo.length === 0) return;
      filtrarProdutos({ busca: termo });
      scrollToProdutos();
    });
  }

  // --- NOVO: SELEÇÃO E EVENTO DO BOTÃO DE FILTRO ---
  const btnAplicarFiltros = document.getElementById("btn-aplicar-filtros");
  // Seleciona o H1 para atualizar o título (movido para cá)
  const tituloH1 = document.querySelector('.area-produto h1');

  if (btnAplicarFiltros) {
      btnAplicarFiltros.addEventListener("click", () => {
          // 1. Coletar dados dos filtros da sidebar
          
          // --- INÍCIO DA ATUALIZAÇÃO ---
          // Seleciona os inputs
          let minPrecoInput = document.querySelector(".filtro .preco input[name='minimo']");
          let maxPrecoInput = document.querySelector(".filtro .preco input[name='maximo']");
          
          let minPreco = minPrecoInput.value;
          let maxPreco = maxPrecoInput.value;

          // NOVO: Verificação de valores negativos
          // Se for negativo, define como "0" e atualiza o campo
          if (parseFloat(minPreco) < 0) {
              minPreco = "0";
              minPrecoInput.value = "0"; 
          }
          if (parseFloat(maxPreco) < 0) {
              maxPreco = "0";
              maxPrecoInput.value = "0";
          }
          // --- FIM DA VERIFICAÇÃO DE NEGATIVOS ---
          // --- FIM DA ATUALIZAÇÃO ---


          // Categorias (MODIFICADO para radio button)
          // Seleciona pelo 'name' do radio button
          const categoriaRadio = document.querySelector(".filtro .opcoes input[name='categoria_filtro']:checked");
          // Lê o 'value' em vez do 'id'
          const categoria = categoriaRadio ? categoriaRadio.value : null; 

          // 2. Definir os parâmetros para a função filtrarProdutos
          let params = {};

          // --- LÓGICA DE FILTROS COMBINADOS (ATUALIZADA) ---
          
          // Adiciona a categoria (se selecionada)
          if (categoria) {
              params.categoria = categoria;
          }

          // Adiciona o preço (se preenchido)
          if (minPreco || maxPreco) { 
              // Define padrões se um dos campos estiver vazio
              if (minPreco === "") {
                  minPreco = "0"; // Preço mínimo padrão
              }
              if (maxPreco === "") {
                  maxPreco = "999999"; // Preço máximo padrão (um valor bem alto)
              }

              // --- NOVO: Garantir que min não seja maior que max ---
              let minVal = parseFloat(minPreco);
              let maxVal = parseFloat(maxPreco);

              if (minVal > maxVal) {
                  let temp = minPreco;
                  minPreco = maxPreco;
                  maxPreco = temp;
                  minPrecoInput.value = minPreco;
                  maxPrecoInput.value = maxPreco;
              }
              // --- FIM DA GARANTIA ---

              params.min = minPreco;
              params.max = maxPreco;
          }
          // --- FIM DA LÓGICA ATUALIZADA ---

          // 3. Chamar a função de filtro existente (agora com todos os params)
          filtrarProdutos(params);
          // --- NOVO: rola para a seção de produtos ---
          scrollToProdutos();
      });
  }
  // --- FIM DO NOVO BLOCO ---


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
    let url = ""; // A URL será definida pela lógica abaixo
    
    // O H1 agora é pego fora da função
    if (!tituloH1) {
        console.error("Elemento H1 do título não encontrado.");
        return;
    }

    // --- LÓGICA MODIFICADA PARA USAR O NOVO ENDPOINT ---

    // Caso 1: É uma busca (veio da barra de pesquisa)
    if (params.busca) {
        url = `${baseURL}/api/produtos/buscar/${encodeURIComponent(params.busca)}`;
        tituloH1.textContent = `Busca por: "${params.busca}"`;

    } 
    // Caso 2: É um filtro (veio do botão, link de categoria ou carregamento inicial)
    else {
        url = `${baseURL}/api/produtos/filtrar?`; // Novo endpoint
        
        let queryParams = []; // Array para os parâmetros da URL
        let titulo = []; // Array para construir o título

        // Adiciona categoria ao filtro
        if (params.categoria && params.categoria !== 'novidades') {
            queryParams.push(`categoria=${encodeURIComponent(params.categoria)}`);
            titulo.push(params.categoria.charAt(0).toUpperCase() + params.categoria.slice(1));
        }

        // Adiciona preço ao filtro
        if (params.min && params.max) {
            queryParams.push(`min=${params.min}`);
            queryParams.push(`max=${params.max}`);
            titulo.push(`Preço entre R$${params.min} e R$${params.max}`);
        }

        // Junta os parâmetros na URL
        if (queryParams.length > 0) {
            url += queryParams.join('&');
        }

        // Define o título H1
        if (titulo.length > 0) {
            tituloH1.textContent = titulo.join(' | '); // Ex: "Feminino | Preço entre R$0 e R$100"
        } else {
            tituloH1.textContent = "Novidades"; // Título padrão se nenhum filtro
        }
    }
    // --- FIM DA MODIFICAÇÃO ---


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

  // Event listener para os links de categoria (agora chama a função de filtro unificada)
  categoryLinks.forEach(link => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const category = link.dataset.category;
      // Chama a função de filtro APENAS com a categoria
      filtrarProdutos({ categoria: category });
      // --- NOVO: rola para a seção de produtos ---
      scrollToProdutos();
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
      scrollToProdutos();
  } else {
      // Senão, usa a lógica de categoria que já existia
      // (Isso também atualiza o H1 inicial corretamente)
      filtrarProdutos({ categoria: categoriaInicial });
      // --- NOVO: rola para a seção de produtos ao carregar vindo de um link de categoria ---
      if (categoriaInicial && categoriaInicial !== 'novidades') {
        scrollToProdutos();
      }
  }
  // --- FIM DO CARREGAMENTO INICIAL --- //

}); // Fim do DOMContentLoaded