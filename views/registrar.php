<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Elegancia Store</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css">

    <style>
        /* Ajusta o card de registro para ser um pouco mais largo */
        .login-container.register-container {
            max-width: 600px; 
            margin: 40px auto; 
        }

        /* =====================================================
        AQUI ESTÁ O ESTILO DA "CAIXA CINZA" QUE VOCÊ QUERIA
        =====================================================
        */
        .form-group {
            background-color: #f0f0f0; /* O fundo cinza claro da caixa */
            border: 1px solid #e0e0e0; /* Uma borda sutil */
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            text-align: left; /* Garante que o label fique à esquerda */
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;        /* Cor mais escura para o rótulo */
            font-size: 0.9rem;  /* Rótulo um pouco menor */
        }
        .form-group input {
            width: 100%;
            padding: 5px 0 0 0; /* Espaço entre o rótulo e o texto digitado */
            border: none;       /* Remove a borda do input */
            background: none;   /* Remove o fundo do input */
            box-sizing: border-box;
            font-size: 1.1rem;  /* Texto digitado maior */
            font-weight: bold;  /* Texto digitado em negrito, como nos prints */
            outline: none;      /* Remove a borda azul ao clicar */
            color: #000;
        }
        /* Remove o preenchimento automático amarelo do Chrome */
        .form-group input:-webkit-autofill,
        .form-group input:-webkit-autofill:hover, 
        .form-group input:-webkit-autofill:focus, 
        .form-group input:-webkit-autofill:active  {
            -webkit-box-shadow: 0 0 0 30px #f0f0f0 inset !important;
            -webkit-text-fill-color: #000 !important;
            font-weight: bold !important;
        }
        
        /* Botão (herda .login-button do index.css) */
        
        /* Mensagens de erro/sucesso (herda do index.css) */
        .success-message {
            background-color: #f0fff0;
            color: #008000;
            border: 1px solid #d2ffd2;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body class="login-page">

    <main class="login-container register-container">
        
        <a href="<?= BASE_URL ?>/">
            <img src="<?= BASE_URL ?>/assets/imagens/logo.png" alt="Logo Elegancia" class="login-logo">
        </a>

        <form id="register-form" class="login-form">
            <h2>Criar Nova Conta</h2>

            <div id="error-message" class="login-error" style="display: none;"></div>
            <div id="success-message" class="success-message" style="display: none;"></div>

            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" required>
            </div>
            <div class="form-group">
                <label for="celular">Nº de Celular (Telefone) *</label>
                <input type="tel" id="celular" placeholder="(11) 99999-9999" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF *</label>
                <input type="text" id="cpf" placeholder="000.000.000-00" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail (usado para login) *</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Criar Senha *</label>
                <input type="password" id="senha" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP *</label>
                <input type="text" id="cep" placeholder="00000-000" required>
            </div>
            <div class="form-group">
                <label for="logradouro">Logradouro (Rua / Avenida) *</label>
                <input type="text" id="logradouro" required>
            </div>
            <div class="form-group">
                <label for="numero">Número *</label>
                <input type="text" id="numero" required>
            </div>
            <div class="form-group">
                <label for="complemento">Complemento (Apto, Bloco)</label>
                <input type="text" id="complemento">
            </div>
            <div class="form-group">
                <label for="bairro">Bairro *</label>
                <input type="text" id="bairro" required>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade *</label>
                <input type="text" id="cidade" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado (UF) *</label>
                <input type="text" id="estado" maxlength="2" placeholder="SP" required>
            </div>

            <button type="submit" id="btn-register" class="login-button">Cadastrar</button>

            <p class="register-link">
                Já tem uma conta? <a href="<?= BASE_URL ?>/">Faça login</a>
            </p>
        </form>
    </main>
    
    <script>
        const baseURL = "<?= BASE_URL ?>";

        document.getElementById("register-form").addEventListener("submit", async (e) => {
            e.preventDefault();

            const btnRegister = document.getElementById("btn-register");
            const errorMsg = document.getElementById("error-message");
            const successMsg = document.getElementById("success-message");

            btnRegister.disabled = true;
            btnRegister.textContent = "Cadastrando...";
            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';

            // Coleta de dados
            const dadosCadastro = {
                nome: document.getElementById("nome").value,
                celular: document.getElementById("celular").value,
                cpf: document.getElementById("cpf").value,
                email: document.getElementById("email").value,
                senha: document.getElementById("senha").value,
                endereco: {
                    cep: document.getElementById("cep").value,
                    logradouro: document.getElementById("logradouro").value,
                    numero: document.getElementById("numero").value,
                    complemento: document.getElementById("complemento").value,
                    bairro: document.getElementById("bairro").value,
                    cidade: document.getElementById("cidade").value,
                    estado: document.getElementById("estado").value.toUpperCase()
                }
            };

            try {
                const response = await fetch(`${baseURL}/api/usuario/cadastrar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dadosCadastro)
                });

                const result = await response.json();

                if (response.ok) {
                    successMsg.innerHTML = "Cadastro realizado com sucesso! Você será redirecionado para o login em 3 segundos...";
                    successMsg.style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = `${baseURL}/`;
                    }, 3000);

                } else {
                    throw new Error(result.erro || "Falha ao cadastrar");
                }

            } catch (error) {
                errorMsg.textContent = error.message;
                errorMsg.style.display = 'block';
                btnRegister.disabled = false;
                btnRegister.textContent = "Cadastrar";
            }
        });
    </script>
</body>
</html>