<?php
// Habilitar exibição de erros (opcional durante desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define a URL base do projeto para ser usada nas views
define('BASE_URL', '/e-commerce/e-commerce-main/public');

// Inclui o Router da pasta correta
require_once __DIR__ . '/../config/Router.php';

// Pega o path da URL acessada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o basePath se o projeto não estiver na raiz do servidor
$basePath = '/e-commerce/e-commerce-main/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// --- ALTERAÇÃO IMPORTANTE ---
// Garante que a URI nunca seja vazia. Se for, define como a rota raiz '/'.
// Isso corrige o erro "Página não encontrada" na página inicial.
if (empty($uri)) {
    $uri = '/';
}

// Instancia o Router e delega a requisição
$router = new Router();
$router->handleRequest($uri);
