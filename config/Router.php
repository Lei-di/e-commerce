<?php
require_once '../app/controllers/web/HomeController.php';
require_once '../app/controllers/web/PerfilController.php';

    class Router {
        public function handleRequest($path) {
            switch ($path) {
                // Rotas para carregar Views (Páginas)
                case '/':
                    (new HomeController())->index();
                    break;

                case '/perfil':
                    (new PerfilController())->index();
                    break;

                // Rotas da API (já existentes)
                case '/api/login':
                    (new LoginController())->login();
                    break;
                
                case '/api/produtos':
                    (new ProdutoController())->listarTodos();
                    break;

                // ... outras rotas da API
                
                default:
                    http_response_code(404);
                    echo "Página não encontrada!";
                    break;
            }
        }
    }
