<?php
// Controllers que carregam as Views 
require_once __DIR__ . '/../controllers/web/HomeController.php';
require_once __DIR__ . '/../controllers/web/PerfilController.php';
require_once __DIR__ . '/../controllers/web/CheckoutController.php';
require_once __DIR__ . '/../controllers/web/PedidoConfirmadoController.php';
require_once __DIR__ . '/../controllers/web/LoginPageController.php'; 
require_once __DIR__ . '/../controllers/web/RegistroController.php'; 

// Controllers que respondem à API (JSON)
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/CarrinhoController.php';
require_once __DIR__ . '/../controllers/ProdutoController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/PedidoController.php';

class Router {
    public function handleRequest($path) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch (true) {
            // --- ROTAS MODIFICADAS ---
            // A raiz (/) agora aponta para o Login
            case $path === '/':
                (new LoginPageController())->index();
                break;

            // A página principal (produtos) agora é /home
            case $path === '/home':
                (new HomeController())->index();
                break;
            // --- FIM DAS MODIFICAÇÕES ---

            case $path === '/perfil':
                (new PerfilController())->index();
                break;

            // ROTA ADICIONADA PARA ATUALIZAR O PERFIL
            case $method === 'POST' && $path === '/perfil/atualizar':
                (new PerfilController())->atualizar();
                break;

            case $path === '/checkout':
                (new CheckoutController())->index();
                break;

            case $path === '/pedido_confirmado':
                (new PedidoConfirmadoController())->index();
                break;
            
            case $path === '/login': 
                // Redireciona /login para / (evitar duplicidade)
                header('Location: ' . BASE_URL . '/');
                exit;
                break;

            case $path === '/registrar': 
                (new RegistroController())->index();
                break;

            case $path === '/logout': 
                (new LoginController())->logout();
                break;

            // Rotas de API (sempre começam com /api/)
            case $method === 'POST' && $path === '/api/login':
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

            case $method === 'GET' && $path === '/api/meus-pedidos':
                (new PedidoController())->listarPedidosDoUsuario();
                break;

            case $path === '/api/pedido/finalizar':
                (new CarrinhoController())->finalizar();
                break;

            case $method === 'GET' && $path === '/api/produtos/filtrar':
                (new ProdutoController())->filtrar();
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

            case $method === 'POST' && $path === '/api/usuario/cadastrar':
                (new UsuarioController())->registrar();
                break;

            default:
                http_response_code(404);
                echo "Página não encontrada!";
                break;
        }
    }
}