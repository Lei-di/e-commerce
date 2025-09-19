<?php
// Habilitar exibição de erros (opcional durante desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define a URL base do projeto para ser usada nas views -> /e-commerce/public
define('BASE_URL', '/e-commerce/public');

// Inclui o Router da pasta correta
require_once __DIR__ . '/../config/Router.php';

// Pega o path da URL acessada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o basePath se o projeto não estiver na raiz do servidor
$basePath = '/e-commerce/public'; // <- AJUSTE SE NECESSÁRIO
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Instancia o Router e delega a requisição
$router = new Router();
$router->handleRequest($uri);