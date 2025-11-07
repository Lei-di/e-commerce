document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.dataset.tab;

            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // --- Lógica de Edição de Perfil ---
    const btnEditarPerfil = document.getElementById('btn-editar-perfil');
    const btnCancelarEdicao = document.getElementById('btn-cancelar-edicao');
    const dadosView = document.getElementById('dados-view');
    const dadosEdit = document.getElementById('dados-edit');
    const formSalvarPerfil = document.getElementById('form-salvar-perfil');

    // Ao clicar em "Editar Dados", mostra o formulário de edição
    if (btnEditarPerfil) {
        btnEditarPerfil.addEventListener('click', function() {
            dadosView.style.display = 'none';
            dadosEdit.style.display = 'block';
        });
    }

    // Ao clicar em "Cancelar", esconde o formulário e mostra a visualização
    if (btnCancelarEdicao) {
        btnCancelarEdicao.addEventListener('click', function() {
            dadosView.style.display = 'block';
            dadosEdit.style.display = 'none';
        });
    }

    // Lógica para enviar o formulário de atualização do perfil
    if (formSalvarPerfil) {
        formSalvarPerfil.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o recarregamento da página

            const nome = document.getElementById('nome-edit').value;
            const telefone = document.getElementById('telefone-edit').value;
            const email = document.getElementById('email-edit').value;
            // O CPF e data de nascimento não são editáveis na sua view atual, 
            // mas você pode adicioná-los aqui se mudar de ideia no futuro.

            // Criar um objeto com os dados a serem enviados
            const dadosAtualizados = {
                nome: nome,
                telefone: telefone,
                email: email
            };

            // Enviar os dados para o servidor usando fetch API
            fetch(baseURL + '/perfil/atualizar', { // Use a constante baseURL definida no perfil.php
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dadosAtualizados)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Perfil atualizado com sucesso!');
                    // Opcional: Recarregar a página para mostrar os novos dados
                    window.location.reload(); 
                } else {
                    alert('Erro ao atualizar perfil: ' + (data.message || 'Erro desconhecido.'));
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Ocorreu um erro ao tentar salvar as alterações.');
            });
        });
    }

    // --- Lógica de Edição de Endereço (Mantida, mas o botão no final foi removido) ---
    const btnNovoEndereco = document.getElementById('btn-novo-endereco');
    const formNovoEnderecoDiv = document.getElementById('form-novo-endereco');
    const btnCancelarEndereco = document.getElementById('btn-cancelar-endereco');
    const formSalvarEndereco = document.getElementById('form-salvar-endereco');

    if (btnNovoEndereco) {
        btnNovoEndereco.addEventListener('click', function() {
            formNovoEnderecoDiv.style.display = 'block';
            this.style.display = 'none'; // Esconde o botão "ADICIONAR MAIS UM ENDEREÇO"
        });
    }

    if (btnCancelarEndereco) {
        btnCancelarEndereco.addEventListener('click', function() {
            formNovoEnderecoDiv.style.display = 'none';
            btnNovoEndereco.style.display = 'block'; // Mostra o botão novamente
            formSalvarEndereco.reset(); // Limpa o formulário
        });
    }

    if (formSalvarEndereco) {
        formSalvarEndereco.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Endereço salvo com sucesso! (Funcionalidade de salvamento real a ser implementada no backend)');
            formNovoEnderecoDiv.style.display = 'none';
            btnNovoEndereco.style.display = 'block';
            formSalvarEndereco.reset(); // Limpa o formulário
            // Aqui você precisaria fazer uma requisição AJAX para salvar o endereço no banco
            // e depois recarregar a lista de endereços ou a página.
        });
    }
});