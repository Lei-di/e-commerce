<?php

require_once 'controllers/LoginController.php';
require_once 'controllers/CarrinhoController.php';

class Router {
    public function handleRequest($path) {
        switch ($path) {
            case '/':
                // Rota para a página inicial (pode ser a página de login por enquanto)
                (new LoginController())->index();
                break;
            case '/login':
                // Rota para o processamento do login
                (new LoginController())->login();
                break;
            case '/api/carrinho/adicionar':
                // Rota para adicionar um item ao carrinho
                (new CarrinhoController())->adicionar();
                break;
            case '/api/carrinho/remover':
                // Rota para remover um item do carrinho
                (new CarrinhoController())->remover();
                break;
            case '/api/carrinho/ver':
                // Rota para visualizar o conteúdo do carrinho
                (new CarrinhoController())->ver();
                break;
            case '/api/pedido/finalizar':
                // Rota para finalizar o pedido
                (new CarrinhoController())->finalizar();
                break;
            default:
                // Rota padrão para páginas não encontradas
                http_response_code(404);
                echo "Página não encontrada!";
                break;
        }
    }
}
