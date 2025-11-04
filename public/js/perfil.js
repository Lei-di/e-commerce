document.addEventListener("DOMContentLoaded", function() {
    const tabLinks = document.querySelectorAll(".tab-link");
    const tabContents = document.querySelectorAll(".tab-content");
    const wishlistGridSidebar = document.querySelector(".wishlist-grid-sidebar");

    // Função para mostrar a aba correta
    function showTab(tabId) {
        tabContents.forEach(content => {
            content.classList.remove("active");
        });
        document.getElementById(tabId).classList.add("active");
    }

    // Adicionar evento de clique aos links das abas
    tabLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            tabLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
            showTab(this.dataset.tab);
        });
    });

    // Preencher a lista de desejos com placeholders
    function populateWishlistPlaceholders(count) {
        
        // -------- INÍCIO DA CORREÇÃO --------
        // Se o elemento não existir, não faça nada e evite o erro
        if (!wishlistGridSidebar) {
            return; 
        }
        // -------- FIM DA CORREÇÃO --------

        for (let i = 0; i < count; i++) {
            const placeholder = document.createElement("div");
            placeholder.classList.add("wishlist-item-placeholder");
            wishlistGridSidebar.appendChild(placeholder);
        }
    }

    // Inicializar a página
    showTab("meu-perfil"); // Mostrar a aba Meu Perfil por padrão
    populateWishlistPlaceholders(6); // Esta chamada agora é segura
    
    
    // --- LÓGICA PARA ADICIONAR ENDEREÇO ---
    // (Agora o script vai conseguir ler este código)

    // 1. Seleciona os elementos do DOM
    const btnNovoEndereco = document.getElementById("btn-novo-endereco");
    const formNovoEndereco = document.getElementById("form-novo-endereco");
    const btnCancelarEndereco = document.getElementById("btn-cancelar-endereco");
    const formSalvarEndereco = document.getElementById("form-salvar-endereco");

    // 2. Verifica se os elementos existem na página antes de adicionar eventos
    if (btnNovoEndereco && formNovoEndereco && btnCancelarEndereco && formSalvarEndereco) {

        // 3. Evento para MOSTRAR o formulário
        btnNovoEndereco.addEventListener("click", () => {
            formNovoEndereco.style.display = "block"; // Mostra o formulário
            btnNovoEndereco.style.display = "none";  // Esconde o botão "Adicionar"
        });

        // 4. Evento para ESCONDER o formulário (no botão Cancelar)
        btnCancelarEndereco.addEventListener("click", () => {
            formNovoEndereco.style.display = "none";  // Esconde o formulário
            btnNovoEndereco.style.display = "block"; // Mostra o botão "Adicionar" de volta
        });

        // 5. Evento para "Salvar" o formulário
        formSalvarEndereco.addEventListener("submit", async (e) => {
            e.preventDefault(); // Impede o recarregamento da página

            // Pega os dados do formulário
            const apelido = document.getElementById("apelido").value;
            const cep = document.getElementById("cep-novo").value;
            const rua = document.getElementById("rua-nova").value;
            const numero = document.getElementById("numero-novo").value;

            // Feedback visual temporário (simulação)
            alert("Salvando endereço... (Simulação de Front-End)");

            // --- PONTO IMPORTANTE PARA O BACKEND ---
            // Aqui é onde você chamaria a API do backend:
            /*
            try {
                const response = await fetch(`${baseURL}/api/endereco/adicionar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ apelido, cep, rua, numero })
                });

                const resultado = await response.json();

                if (response.ok) {
                    alert("Endereço salvo com sucesso!");
                    // Aqui você deveria recarregar a lista de endereços ou adicionar o novo dinamicamente
                    formNovoEndereco.style.display = "none";  // Esconde o formulário
                    btnNovoEndereco.style.display = "block"; // Mostra o botão "Adicionar"
                } else {
                    alert("Erro ao salvar: " + resultado.erro);
                }
            } catch (error) {
                console.error("Erro na API:", error);
                alert("Erro grave ao tentar salvar endereço.");
            }
            */
            // --- FIM DA PARTE DO BACKEND ---
        });
    }
        // --- FIM DA LÓGICA DE ENDEREÇO ---
        
        // --- LÓGICA PARA EDITAR PERFIL ---

    // 1. Seleciona os novos elementos
    const btnEditarPerfil = document.getElementById("btn-editar-perfil");
    const btnCancelarEdicao = document.getElementById("btn-cancelar-edicao");
    const dadosView = document.getElementById("dados-view");
    const dadosEdit = document.getElementById("dados-edit");
    const formSalvarPerfil = document.getElementById("form-salvar-perfil");

    // 2. Verifica se os elementos existem
    if (btnEditarPerfil && btnCancelarEdicao && dadosView && dadosEdit && formSalvarPerfil) {

        // 3. Evento para ENTRAR no modo de edição
        btnEditarPerfil.addEventListener("click", () => {
            // Esconde a visualização e mostra a edição
            dadosView.style.display = "none";
            dadosEdit.style.display = "block";

            // Futuramente: pré-carregar os campos com dados reais
            // document.getElementById("nome-edit").value = dadosReais.nome;
        });

        // 4. Evento para SAIR do modo de edição (Cancelando)
        btnCancelarEdicao.addEventListener("click", () => {
            // Esconde a edição e mostra a visualização
            dadosEdit.style.display = "none";
            dadosView.style.display = "block";
        });

        // 5. Evento para SALVAR os dados
        formSalvarPerfil.addEventListener("submit", async (e) => {
            e.preventDefault();

            // Pega os dados dos campos
            const nome = document.getElementById("nome-edit").value;
            const telefone = document.getElementById("telefone-edit").value;
            const email = document.getElementById("email-edit").value;
            const nascimento = document.getElementById("nasc-edit").value;

            alert("Simulação de Front-End: Salvando dados...");

            // --- TAREFA DO BACKEND ---
            // O código real faria um fetch para a API
            /*
            try {
                const response = await fetch(`${baseURL}/api/usuario/atualizar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nome, telefone, email, nascimento })
                });

                if (response.ok) {
                    alert("Dados atualizados com sucesso!");
                    // Aqui você atualizaria os <p> do modo-view com os novos dados
                    // E voltaria para o modo de visualização
                    dadosEdit.style.display = "none";
                    dadosView.style.display = "block";
                } else {
                    alert("Erro ao atualizar dados.");
                }
            } catch (error) {
                console.error("Erro na API:", error);
                alert("Erro grave ao tentar salvar.");
            }
            */
            // --- FIM DA TAREFA DO BACKEND ---
        });
    }
    // --- FIM DA LÓGICA DE EDITAR PERFIL ---
});