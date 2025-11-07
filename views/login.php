<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Elegancia Store</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css"> 
</head>
<body class="login-page">

    <div class="login-container">
        <img src="<?= BASE_URL ?>/assets/imagens/logo.png" alt="Logo Elegancia" class="login-logo">

        <form id="login-form" class="login-form">
            <h2>Login</h2>
            
            <div id="login-error-message" class="login-error" style="display: none;"></div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit" id="btn-login" class="login-button">Entrar</button>
            
            <p class="register-link">
                Não tem uma conta? <a href="<?= BASE_URL ?>/registrar">Crie uma</a>
            </p>
        </form>
    </div>

    <script>
        const baseURL = "<?= BASE_URL ?>"; // Garante que baseURL está definida

        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const errorMessageDiv = document.getElementById('login-error-message');
            const btnLogin = document.getElementById('btn-login');

            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault(); 
                
                // Pega os dados direto dos campos
                const email = document.getElementById("email").value;
                const senha = document.getElementById("senha").value;

                errorMessageDiv.style.display = 'none';
                btnLogin.disabled = true;
                btnLogin.textContent = "Entrando...";

                try {
                    const response = await fetch(`${baseURL}/api/login`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: email, senha: senha }) 
                    });

                    const result = await response.json();

                    // --- ESTA É A CORREÇÃO ---
                    // Verificamos 'response.ok' (status 200) para sucesso
                    // e 'result.erro' para falha, como sua API envia.
                    if (response.ok) {
                        // SUCESSO!
                        window.location.href = `${baseURL}/home`;
                    } else {
                        // ERRO!
                        throw new Error(result.erro || "Falha no login");
                    }
                    // --- FIM DA CORREÇÃO ---

                } catch (error) {
                    errorMessageDiv.textContent = error.message;
                    errorMessageDiv.style.display = 'block';
                    btnLogin.disabled = false;
                    btnLogin.textContent = "Entrar";
                }
            });
        });
    </script>
    </body>
</html>