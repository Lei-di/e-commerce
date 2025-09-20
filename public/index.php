<?php
// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- INÍCIO DO CÓDIGO DE DEPURAÇÃO ---

// 1. Pega o path da URL acessada
$uri_completa = $_SERVER['REQUEST_URI'];
$uri_processada = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. Define o caminho base do projeto
$basePath = '/e-commerce/e-commerce-main/public';

// 3. Mostra os valores antes do processamento
echo "<h1>Diagnóstico de Roteamento</h1>";
echo "<p><strong>URL Completa Recebida pelo Servidor (REQUEST_URI):</strong></p>";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>" . htmlspecialchars($uri_completa) . "</pre>";

echo "<p><strong>Caminho Extraído (parse_url):</strong></p>";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>" . htmlspecialchars($uri_processada) . "</pre>";

echo "<p><strong>Caminho Base Definido no Código (basePath):</strong></p>";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>" . htmlspecialchars($basePath) . "</pre>";
echo "<hr>";

// 4. Lógica de processamento
$uri_final = $uri_processada;
if (strpos($uri_final, $basePath) === 0) {
    $uri_final = substr($uri_final, strlen($basePath));
}
if (empty($uri_final)) {
    $uri_final = '/';
}

// 5. Mostra o valor final que seria enviado ao router
echo "<h1>Resultado Final</h1>";
echo "<p><strong>Caminho final que seria enviado ao Router:</strong></p>";
echo "<pre style='background-color:#e6ffed; padding:10px; border:1px solid #58a65c;'>";
var_dump($uri_final);
echo "</pre>";

// 6. Para a execução para podermos ver o resultado
exit;

// --- FIM DO CÓDIGO DE DEPURAÇÃO ---
