<?php
// Controllers que carregam as Views 
require_once __DIR__ . '/../controllers/web/HomeController.php';
require_once __DIR__ . '/../controllers/web/PerfilController.php';

// Controllers que respondem à API (JSON)
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/CarrinhoController.php';
require_once __DIR__ . '/../controllers/ProdutoController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
//Adicionado o novo controller de Pedido
require_once __DIR__ . '/../controllers/PedidoController.php';

class Router {
    public function handleRequest($path) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch (true) {
            // Rotas para carregar Views 
            case $path === '/':
                (new HomeController())->index();
                break;

            case $path === '/perfil':
                (new PerfilController())->index();
                break;

            case $path === '/api/login':
                (new LoginController())->login();
                break;

            case $path === '/api/carrinho/adicionar':
                (new CarrinhoController())->adicionar();
                break;
            
            case $method === 'PUT' && $path === '/api/carrinho/atualizar':
                (new CarrinhoController())->atualizar();
                break;

            case $path === '/api/carrinho/remover':
                (new CarrinhoController())->remover();
                break;

            case $path === '/api/carrinho/ver':
                (new CarrinhoController())->ver();
                break;

            //Adicionada a nova rota para listar pedidos do usuário
            case $method === 'GET' && $path === '/api/meus-pedidos':
                (new PedidoController())->listarPedidosDoUsuario();
                break;

            case $path === '/api/pedido/finalizar':
                (new CarrinhoController())->finalizar();
                break;

            case $path === '/api/produtos':
                (new ProdutoController())->listarTodos();
                break;

            case $method === 'POST' && $path === '/api/produtos/criar':
                (new ProdutoController())->criarProduto();
                break;

            case $method === 'PUT' && preg_match('/^\/api\/produtos\/atualizar\/(\d+)$/', $path, $matches):
                (new ProdutoController())->atualizarProduto($matches[1]);
                break;
            
            case preg_match('/^\/api\/produtos\/(\d+)$/', $path, $matches):
                (new ProdutoController())->buscarPorId($matches[1]);
                break;
            
            case $method === 'DELETE' && preg_match('/^\/api\/produtos\/deletar\/(\d+)$/', $path, $matches):
                (new ProdutoController())->deletarProduto($matches[1]);
                break;

            case $method === 'GET' && preg_match('/^\/api\/produtos\/buscar\/(.+)$/', $path, $matches):
                $termo = urldecode($matches[1]);
                (new ProdutoController())->buscarProdutos($termo);
                break;

            case $method === 'GET' && preg_match('/^\/api\/produtos\/categoria\/(.+)$/', $path, $matches):
                $categoria = urldecode($matches[1]);
                (new ProdutoController())->buscarPorCategoria($categoria);
                break;

            case $method === 'GET' && preg_match('/^\/api\/produtos\/preco\/(\d+)\/(\d+)$/', $path, $matches):
                $min = (int)$matches[1];
                $max = (int)$matches[2];
                (new ProdutoController())->buscarPorPreco($min, $max);
                break;

            case $path === '/api/usuario/cadastrar':
                (new UsuarioController())->registrar();
                break;

            default:
                http_response_code(404);
                echo "Página não encontrada!";
                break;
        }
    }
}