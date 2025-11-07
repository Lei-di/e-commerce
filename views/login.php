<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Elegancia Store</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidecart.css">
    <style>
        body { background-color: #f4f4f4; font-family: Arial, sans-serif; }
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-container h1 { font-size: 24px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; /* Garante que o padding não afete a largura */
        }
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            margin-bottom: 15px;
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
            text-decoration: none;
            display: block;
        }
        .error-message {
            color: red;
            background: #fdd;
            border: 1px solid red;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none; /* Começa oculto */
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/cabecalho.php'; ?>

    <main class="login-container">
        <h1>Acessar Minha Conta</h1>
        
        <div id="error-message" class="error-message"></div>

        <form id="login-form">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" required>
            </div>
            
            <button type="submit" id="btn-login" class="btn btn-primary">Entrar</button>
            <a href="<?= BASE_URL ?>/registrar" class="btn btn-secondary">Não tenho conta</a>
        </form>
    </main>

    <div id="sidecart-overlay" class="sidecart-overlay"></div>
    <div id="sidecart" class="sidecart"></div>

    <script>
        const baseURL = "<?= BASE_URL ?>";

        document.getElementById("login-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const email = document.getElementById("email").value;
            const senha = document.getElementById("senha").value;
            const btnLogin = document.getElementById("btn-login");
            const errorMsg = document.getElementById("error-message");

            btnLogin.disabled = true;
            btnLogin.textContent = "Entrando...";
            errorMsg.style.display = 'none';

            try {
                const response = await fetch(`${baseURL}/api/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, senha })
                });

                const result = await response.json();

                if (response.ok) {
                    // Sucesso! Redireciona para o perfil ou página principal
                    window.location.href = `${baseURL}/perfil`;
                } else {
                    // Erro
                    throw new Error(result.erro || "Falha no login");
                }

            } catch (error) {
                errorMsg.textContent = error.message;
                errorMsg.style.display = 'block';
                btnLogin.disabled = false;
                btnLogin.textContent = "Entrar";
            }
        });
    </script>
    <script src="<?= BASE_URL ?>/js/sidecart.js"></script>
</body>
</html>