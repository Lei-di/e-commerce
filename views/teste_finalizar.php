<?php
// Inicia a sessão para simular o login e o carrinho
session_start();

// --- SIMULAÇÃO ---
// 1. Simular o login do utilizador "Admin" (ID 1)
$_SESSION['usuario_id'] = 1;

// 2. Limpar o carrinho antigo e adicionar um item para o teste
unset($_SESSION['carrinho']);
$_SESSION['carrinho'] = [
    1 => 2, // Adiciona 2 unidades do produto com ID 1
    3 => 1  // Adiciona 1 unidade do produto com ID 3
];

echo "<h1>Página de Teste de Finalização de Pedido</h1>";
echo "<p><strong>Status do Teste:</strong></p>";
echo "<ul>";
echo "<li>Login simulado para o utilizador com ID: " . $_SESSION['usuario_id'] . "</li>";
echo "<li>Carrinho preparado com produtos.</li>";
echo "</ul>";
echo "<hr>";
echo "<h2>Clique no botão abaixo para finalizar o pedido:</h2>";

?>

<button id="finalizarBtn">Finalizar Pedido Agora</button>

<h3>Resultado:</h3>
<pre id="resultado"></pre>

<script>
    // Adiciona um evento ao botão
    document.getElementById("finalizarBtn").addEventListener("click", function() {
        const resultadoDiv = document.getElementById("resultado");
        resultadoDiv.textContent = "A processar...";

        // Esta função 'fetch' faz exatamente o que o Postman faz:
        // envia uma requisição POST para o URL de finalização.
        fetch('/e-commerce/public/api/pedido/finalizar', {
            method: 'POST'
        })
        .then(response => response.json()) // Converte a resposta para JSON
        .then(data => {
            // Mostra a resposta do servidor na tela
            resultadoDiv.textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            // Mostra qualquer erro que tenha ocorrido
            resultadoDiv.textContent = "Ocorreu um erro: " + error;
        });
    });
</script>