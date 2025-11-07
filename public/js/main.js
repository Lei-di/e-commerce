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


          // Categorias
          const categoriaCheckbox = document.querySelector(".filtro .opcoes input[type='checkbox']:checked");
          const categoria = categoriaCheckbox ? categoriaCheckbox.id : null; // 'feminino', 'masculino', etc.

          // 2. Definir os parâmetros para a função filtrarProdutos
          let params = {};

          // --- INÍCIO DA ATUALIZAÇÃO LÓGICA ---
          // Verifica se *pelo menos um* dos campos de preço foi preenchido
          if (minPreco || maxPreco) { 
              
              // Define padrões se um dos campos estiver vazio
              if (minPreco === "") {
                  minPreco = "0"; // Preço mínimo padrão
              }
              if (maxPreco === "") {
                  maxPreco = "999999"; // Preço máximo padrão (um valor bem alto)
              }

              // --- NOVO: Garantir que min não seja maior que max ---
              // Converte para número para comparar corretamente
              let minVal = parseFloat(minPreco);
              let maxVal = parseFloat(maxPreco);

              if (minVal > maxVal) {
                  // Se min for maior que max, inverte os valores
                  let temp = minPreco;
                  minPreco = maxPreco;
                  maxPreco = temp;
                  
                  // Atualiza os campos de input visualmente
                  minPrecoInput.value = minPreco;
                  maxPrecoInput.value = maxPreco;
              }
              // --- FIM DA GARANTIA ---

              // Prioridade 1: Filtro por preço (ignora o resto, pois a API não combina)
              params.preco = `${minPreco}-${maxPreco}`;
              if(tituloH1) tituloH1.textContent = `Preço entre R$${minPreco} e R$${maxPreco}`;
          
          } else if (categoria) {
              // Prioridade 2: Filtro por categoria
              params.categoria = categoria;
          } else {
              // Se nada for selecionado, apenas recarrega as novidades (todos)
              params.categoria = 'novidades';
          }
          // --- FIM DA ATUALIZAÇÃO LÓGICA ---

          // 3. Chamar a função de filtro existente
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


  // --- FUNÇÃO MODIFICAÇÃO ---
  async function filtrarProdutos(params = {}) {
    let url = `${baseURL}/api/produtos`;
    
    // O H1 agora é pego fora da função
    // const tituloH1 = document.querySelector('.area-produto h1'); // REMOVIDO DAQUI

    // --- LÓGICA MODIFICADA PARA PRIORIZAR O PREÇO ---
    // Se um parâmetro de preço foi passado (pelo botão)
    if (params.preco) {
      const [min, max] = params.preco.split('-');
      url = `${baseURL}/api/produtos/preco/${min}/${max}`;
      // O título já foi atualizado no listener do botão
    }
    // Senão, se uma categoria foi passada...
    else if (params.categoria && params.categoria !== 'novidades') {
      url = `${baseURL}/api/produtos/categoria/${encodeURIComponent(params.categoria)}`;
      if(tituloH1) tituloH1.textContent = params.categoria.charAt(0).toUpperCase() + params.categoria.slice(1);

    } else if (params.busca) { // Lógica de busca mantida
        url = `${baseURL}/api/produtos/buscar/${encodeURIComponent(params.busca)}`;
        if(tituloH1) tituloH1.textContent = `Busca por: "${params.busca}"`;

    } else {
        // Garante um título padrão
        if(tituloH1) tituloH1.textContent = "Novidades";
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

  // Event listener para os links de categoria (sem alterações)
  categoryLinks.forEach(link => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const category = link.dataset.category;
      // <-- A atualização do H1 agora é feita dentro de filtrarProdutos -->
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
      // (Isso também atualiza o título H1 inicial corretamente)
      filtrarProdutos({ categoria: categoriaInicial });
      // --- NOVO: rola para a seção de produtos ao carregar vindo de um link de categoria ---
      if (categoriaInicial && categoriaInicial !== 'novidades') {
        scrollToProdutos();
      }
  }
  // --- FIM DO CARREGAMENTO INICIAL --- //

}); // Fim do DOMContentLoaded