<?php

require_once 'controllers/LoginController.php';
require_once 'controllers/CarrinhoController.php';
require_once 'controllers/ProdutoController.php';

class Router {
    public function handleRequest($path) {
        switch (true) {
            case $path === '/':
                // Rota para a página inicial (pode ser a página de login por enquanto)
                (new LoginController())->index();
                break;

            case $path === '/login':
                // Rota para o processamento do login
                (new LoginController())->login();
                break;

            case $path === '/api/carrinho/adicionar':
                // Rota para adicionar um item ao carrinho
                (new CarrinhoController())->adicionar();
                break;

            case $path === '/api/carrinho/remover':
                // Rota para remover um item do carrinho
                (new CarrinhoController())->remover();
                break;

            case $path === '/api/carrinho/ver':
                // Rota para visualizar o conteúdo do carrinho
                (new CarrinhoController())->ver();
                break;

            case $path === '/api/pedido/finalizar':
                // Rota para finalizar o pedido
                (new CarrinhoController())->finalizar();
                break;

            // 🔹 Nova rota: listar todos os produtos
            case $path === '/api/produtos':
                (new ProdutoController())->listarTodos();
                break;

            // 🔹 Nova rota: buscar produto por ID (/api/produtos/{id})
            case preg_match('/^\/api\/produtos\/(\d+)$/', $path, $matches):
                (new ProdutoController())->buscarPorId($matches[1]);
                break;

            default:
                // Rota padrão para páginas não encontradas
                http_response_code(404);
                echo "Página não encontrada!";
                break;
        }
    }
}
