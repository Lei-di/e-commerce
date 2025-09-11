<?php
// Habilitar exibição de erros (opcional durante desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o Router
require_once __DIR__ . '/../configuracao/Router.php';

// Pega o path da URL acessada e remove a parte /e-commerce/public
$basePath = '/e-commerce/public'; // ajuste conforme sua pasta
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace($basePath, '', $uri);

// Instancia o Router e delega a requisição
$router = new Router();
$router->handleRequest($uri);
