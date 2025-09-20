<?php
// Controllers que carregam as Views (Páginas)
require_once __DIR__ . '/../controllers/web/HomeController.php';
require_once __DIR__ . '/../controllers/web/PerfilController.php';

// Controllers que respondem à API (JSON)
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/CarrinhoController.php';
require_once __DIR__ . '/../controllers/ProdutoController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';

class Router {
    public function handleRequest($path) {
        // Pega o método da requisição (GET, POST, DELETE, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        switch (true) {
            // --- Rotas para carregar Views (Páginas) ---
            case $path === '/':
                (new HomeController())->index();
                break;

            case $path === '/perfil':
                (new PerfilController())->index();
                break;

            // --- Rotas da API (retornam JSON) ---
            case $path === '/api/login':
                (new LoginController())->login();
                break;

            case $path === '/api/carrinho/adicionar':
                (new CarrinhoController())->adicionar();
                break;

            case $path === '/api/carrinho/remover':
                (new CarrinhoController())->remover();
                break;

            case $path === '/api/carrinho/ver':
                (new CarrinhoController())->ver();
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
            
            // Nova rota para deletar produto
            case $method === 'DELETE' && preg_match('/^\/api\/produtos\/deletar\/(\d+)$/', $path, $matches):
                (new ProdutoController())->deletarProduto($matches[1]);
                break;

            // Nova rota para buscar produtos
            case $method === 'GET' && preg_match('/^\/api\/produtos\/buscar\/(.+)$/', $path, $matches):
                // Decodifica o termo da URL (ex: espaços %20)
                $termo = urldecode($matches[1]);
                (new ProdutoController())->buscarProdutos($termo);
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
