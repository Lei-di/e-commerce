<?php
// Adicione esta linha no topo para poder usar o Model de Produto
require_once __DIR__ . '/../../models/Produto.php';

class HomeController {
    public function index() {
        $categoria = $_GET['categoria'] ?? null;
        $produtos = [];
        $titulo = "Todos";

        if ($categoria) {
            $produtos = Produto::getByCategoria($categoria);
            $titulo = ucfirst($categoria); // Deixa a primeira letra maiúscula
        } else {
            $produtos = Produto::getAll();
        }

        // Agora, a view `pagina_principal` terá acesso às variáveis $produtos e $titulo
        require_once __DIR__ . '/../../views/pagina_principal.php';
    }
}
