document.addEventListener("DOMContentLoaded", () => {
  const listaProdutos = document.querySelector(".lista-produtos");
  const categoryLinks = document.querySelectorAll(".category-link");
  const areaProduto = document.querySelector('.area-produto');
  
  // --- INÍCIO: Seletores do Modal de Tamanho (Atualizado) ---
  const modalOverlay = document.getElementById("tamanho-modal-overlay");
  const modal = document.getElementById("tamanho-modal");
  const modalTitulo = document.getElementById("tamanho-modal-titulo"); // Novo seletor
  const modalOpcoes = document.getElementById("tamanho-modal-opcoes");
  const btnConfirmarTamanho = document.getElementById("btn-confirmar-tamanho");
  const btnCancelarTamanho = document.getElementById("btn-cancelar-tamanho");
  // --- FIM: Seletores do Modal ---

  function scrollToProdutos() {
    if (areaProduto) {
      areaProduto.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  // --- Funções do Modal de Tamanho (Atualizadas) ---

  /**
   * Abre o modal e popula com as opções corretas (roupa ou calçado)
   * @param {string} produtoId - O ID do produto
   * @param {string} categoria - A categoria do produto (ex: 'Feminino', 'Calçados')
   */
  function openTamanhoModal(produtoId, categoria) {
      // Armazena o ID e a categoria no modal
      modal.dataset.produtoId = produtoId;
      modal.dataset.categoria = categoria; // Armazena a categoria
      
      let optionsHtml = '';
      
      // Limpa opções anteriores
      modalOpcoes.innerHTML = '';

      if (categoria === 'Feminino' || categoria === 'Masculino') {
          modalTitulo.textContent = "Selecione o Tamanho";
          const tamanhosRoupa = ['PP', 'P', 'M', 'G', 'GG', 'XG'];
          
          optionsHtml = tamanhosRoupa.map(t => 
              `<label><input type="radio" name="tamanho_modal" value="${t}"> ${t}</label>`
          ).join('');

      } else if (categoria === 'Calçados') {
          modalTitulo.textContent = "Selecione a Numeração";
          const numerosCalcado = [];
          for (let i = 34; i <= 44; i++) {
              numerosCalcado.push(i.toString());
          }

          optionsHtml = numerosCalcado.map(n => 
              `<label><input type="radio" name="tamanho_modal" value="${n}"> ${n}</label>`
          ).join('');
      }
      
      // Insere as opções geradas no modal
      modalOpcoes.innerHTML = optionsHtml;

      // Mostra o modal e o overlay
      modalOverlay.classList.add("open");
      modal.classList.add("open");
  }

  function closeTamanhoModal() {
      modalOverlay.classList.remove("open");
      modal.classList.remove("open");
      // Limpa os dados do modal
      delete modal.dataset.produtoId;
      delete modal.dataset.categoria;
  }

  // Evento de clique no botão CONFIRMAR do modal (Atualizado)
  if (btnConfirmarTamanho) {
    btnConfirmarTamanho.addEventListener("click", () => {
        const produtoId = modal.dataset.produtoId;
        const categoria = modal.dataset.categoria; // Pega a categoria
        const selectedRadio = modalOpcoes.querySelector('input[name="tamanho_modal"]:checked');
        
        if (!selectedRadio) {
            // Alerta dinâmico
            const alertMsg = (categoria === 'Calçados') 
                ? "Por favor, selecione uma numeração." 
                : "Por favor, selecione um tamanho.";
            alert(alertMsg);
            return;
        }

        const tamanho = selectedRadio.value; // 'M' ou '39', etc.

        // Chama a API do carrinho com o tamanho/numeração
        if (typeof window.apiAdicionarAoCarrinho === 'function') {
            window.apiAdicionarAoCarrinho(produtoId, tamanho);
            closeTamanhoModal(); // Fecha o modal após confirmar
        } else {
            console.error("Função apiAdicionarAoCarrinho não encontrada.");
            alert("Erro ao adicionar produto.");
        }
    });
  }

  // Eventos para FECHAR o modal
  if (btnCancelarTamanho) btnCancelarTamanho.addEventListener("click", closeTamanhoModal);
  if (modalOverlay) modalOverlay.addEventListener("click", closeTamanhoModal);
  // --- Fim da lógica do Modal ---


  // --- Lógica de Busca (sem alterações) ---
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

  // --- Lógica de Filtros (sem alterações) ---
  const btnAplicarFiltros = document.getElementById("btn-aplicar-filtros");
  const tituloH1 = document.querySelector('.area-produto h1');
  if (btnAplicarFiltros) {
      btnAplicarFiltros.addEventListener("click", () => {
          let minPrecoInput = document.querySelector(".filtro .preco input[name='minimo']");
          let maxPrecoInput = document.querySelector(".filtro .preco input[name='maximo']");
          let minPreco = minPrecoInput.value;
          let maxPreco = maxPrecoInput.value;
          if (parseFloat(minPreco) < 0) minPreco = "0";
          if (parseFloat(maxPreco) < 0) maxPreco = "0";
          
          const categoriaRadio = document.querySelector(".filtro .opcoes input[name='categoria_filtro']:checked");
          const categoria = categoriaRadio ? categoriaRadio.value : null; 

          let params = {};
          if (categoria) params.categoria = categoria;

          if (minPreco || maxPreco) { 
              if (minPreco === "") minPreco = "0";
              if (maxPreco === "") maxPreco = "999999";
              let minVal = parseFloat(minPreco);
              let maxVal = parseFloat(maxPreco);
              if (minVal > maxVal) { 
                  [minPreco, maxPreco] = [maxPreco, minPreco];
                  minPrecoInput.value = minPreco;
                  maxPrecoInput.value = maxPreco;
              }
              params.min = minPreco;
              params.max = maxPreco;
          }
          
          filtrarProdutos(params);
          scrollToProdutos();
      });
  }


  function renderProdutos(produtos) {
    if (!listaProdutos) {
        console.error("Elemento .lista-produtos não encontrado.");
        return;
    }

    if (!produtos || produtos.length === 0) {
      listaProdutos.innerHTML = "<p>Nenhum produto encontrado.</p>";
      return;
    }

    listaProdutos.innerHTML = produtos.map(p => `
      <div class="produto" data-categoria="${p.categoria || ''}">
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

    addEventListenersToBuyButtons();
  }

  // --- FUNÇÃO MODIFICADA ---
  // Adiciona o evento de clique aos botões "ADICIONAR AO CARRINHO"
  function addEventListenersToBuyButtons() {
    const buyButtons = document.querySelectorAll('.btn-comprar');

    buyButtons.forEach(button => {
        // Clonagem para limpar listeners antigos
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);

        // Adiciona o novo listener
        newButton.addEventListener('click', (e) => {
            const button = e.target;
            const produtoId = button.dataset.idProduto;
            const produtoDiv = button.closest('.produto'); // Pega o card pai
            
            // --- INÍCIO DA NOVA LÓGICA DO MODAL (Atualizada) ---
            const categoria = produtoDiv.dataset.categoria;

            // Se for Feminino, Masculino OU Calçados, abre o modal
            if (categoria === 'Feminino' || categoria === 'Masculino' || categoria === 'Calçados') {
                openTamanhoModal(produtoId, categoria); // Passa a categoria
            } else {
                // Para outras categorias (Acessórios, etc.), adiciona direto
                if (typeof window.apiAdicionarAoCarrinho === 'function') {
                    window.apiAdicionarAoCarrinho(produtoId, null); // Envia 'null' como tamanho
                } else {
                    console.error("Função apiAdicionarAoCarrinho não encontrada.");
                    alert("Erro ao adicionar produto.");
                }
            }
            // --- FIM DA NOVA LÓGICA DO MODAL ---
        });
    });
  }
  // --- FIM DA FUNÇÃO MODIFICADA ---


  // --- FUNÇÃO DE FILTRAGEM (sem alterações) ---
  async function filtrarProdutos(params = {}) {
    let url = ""; 
    if (!tituloH1) {
        console.error("Elemento H1 do título não encontrado.");
        return;
    }

    if (params.busca) {
        url = `${baseURL}/api/produtos/buscar/${encodeURIComponent(params.busca)}`;
        tituloH1.textContent = `Busca por: "${params.busca}"`;

    } else {
        url = `${baseURL}/api/produtos/filtrar?`; 
        let queryParams = []; 
        let titulo = []; 

        if (params.categoria && params.categoria !== 'novidades') {
            queryParams.push(`categoria=${encodeURIComponent(params.categoria)}`);
            titulo.push(params.categoria.charAt(0).toUpperCase() + params.categoria.slice(1));
        }

        if (params.min && params.max) {
            queryParams.push(`min=${params.min}`);
            queryParams.push(`max=${params.max}`);
            titulo.push(`Preço entre R$${params.min} e R$${params.max}`);
        }

        if (queryParams.length > 0) url += queryParams.join('&');

        if (titulo.length > 0) {
            tituloH1.textContent = titulo.join(' | ');
        } else {
            tituloH1.textContent = "Novidades";
        }
    }

    try {
      if (!listaProdutos) return;
      listaProdutos.innerHTML = '<p>Carregando produtos...</p>';
      const response = await fetch(url);
      if (!response.ok) throw new Error(`Erro na requisição: ${response.statusText}`);
      const data = await response.json();
      renderProdutos(data);
    } catch (error) {
      console.error("Falha ao buscar produtos:", error);
      if (listaProdutos) listaProdutos.innerHTML = "<p>Ocorreu um erro ao carregar os produtos.</p>";
    }
  }

  // --- LISTENERS DE CATEGORIA (sem alterações) ---
  categoryLinks.forEach(link => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const category = link.dataset.category;
      filtrarProdutos({ categoria: category });
      scrollToProdutos();
    });
  });

  // --- CARREGAMENTO INICIAL (sem alterações) ---
  const urlParams = new URLSearchParams(window.location.search);
  const termoBusca = urlParams.get('busca');
  const categoriaInicial = urlParams.get('categoria') || 'novidades';

  if (termoBusca) {
      filtrarProdutos({ busca: termoBusca });
      scrollToProdutos();
  } else {
      // Adiciona os listeners aos botões já na carga inicial
      addEventListenersToBuyButtons();

      // (A lógica de filtragem inicial foi movida para o backend, 
      // mas se precisar de filtro inicial via JS, descomente a linha abaixo)
      // filtrarProdutos({ categoria: categoriaInicial }); 

      if (categoriaInicial && categoriaInicial !== 'novidades') {
        scrollToProdutos();
      }
  }
  
  // Garante que os botões dos produtos carregados inicialmente (via PHP) 
  // também tenham os listeners
  addEventListenersToBuyButtons();

}); // Fim do DOMContentLoaded