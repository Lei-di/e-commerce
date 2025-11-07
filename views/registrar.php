<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Elegancia Store</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global/cabecalho.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidecart.css">
    <style>
        body { background-color: #f4f4f4; font-family: Arial, sans-serif; }
        .register-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .register-container h1 { font-size: 24px; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .section-title { font-size: 18px; font-weight: 600; margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .error-message { color: red; background: #fdd; border: 1px solid red; padding: 10px; border-radius: 8px; margin-bottom: 15px; display: none; }
        .success-message { color: green; background: #dfd; border: 1px solid green; padding: 10px; border-radius: 8px; margin-bottom: 15px; display: none; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/cabecalho.php'; ?>

    <main class="register-container">
        <h1>Criar Nova Conta</h1>

        <div id="error-message" class="error-message"></div>
        <div id="success-message" class="success-message"></div>

        <form id="register-form">
            <div class="section-title">Dados Pessoais</div>
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="celular">Nº de Celular (Telefone) *</label>
                    <input type="tel" id="celular" placeholder="(11) 99999-9999" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF *</label>
                    <input type="text" id="cpf" placeholder="000.000.000-00" required>
                </div>
            </div>

            <div class="section-title">Dados de Acesso</div>
            <div class="form-group">
                <label for="email">E-mail (usado para login) *</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Criar Senha *</label>
                <input type="password" id="senha" required>
            </div>

            <div class="section-title">Endereço Principal</div>
            <div class="form-group">
                <label for="cep">CEP *</label>
                <input type="text" id="cep" placeholder="00000-000" required>
            </div>
            
            <div class="form-group">
                <label for="logradouro">Logradouro (Rua / Avenida) *</label>
                <input type="text" id="logradouro" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="numero">Número *</label>
                    <input type="text" id="numero" required>
                </div>
                 <div class="form-group">
                    <label for="complemento">Complemento (Apto, Bloco)</label>
                    <input type="text" id="complemento">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="bairro">Bairro *</label>
                    <input type="text" id="bairro" required>
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade *</label>
                    <input type="text" id="cidade" required>
                </div>
            </div>

            <div class="form-group">
                <label for="estado">Estado (UF) *</label>
                <input type="text" id="estado" maxlength="2" placeholder="SP" required>
            </div>

            <button type="submit" id="btn-register" class="btn btn-primary">Cadastrar</button>
        </form>
    </main>
    
    <div id="sidecart-overlay" class="sidecart-overlay"></div>
    <div id="sidecart" class="sidecart"></div>

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

            // Coleta de dados (CORRIGIDA)
            const dadosCadastro = {
                nome: document.getElementById("nome").value,
                celular: document.getElementById("celular").value,
                cpf: document.getElementById("cpf").value,
                email: document.getElementById("email").value,
                senha: document.getElementById("senha").value,
                endereco: {
                    // removido 'apelido'
                    cep: document.getElementById("cep").value,
                    logradouro: document.getElementById("logradouro").value, // alterado de 'rua'
                    numero: document.getElementById("numero").value,
                    complemento: document.getElementById("complemento").value, // adicionado
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
                        window.location.href = `${baseURL}/login`;
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
    <script src="<?= BASE_URL ?>/js/sidecart.js"></script>
</body>
</html>