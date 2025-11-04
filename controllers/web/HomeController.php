<?php
// Adicione esta linha no topo para poder usar o Model de Produto
require_once __DIR__ . '/../../models/Produto.php';

class HomeController {
    public function index() {
        $categoria = $_GET['categoria'] ?? null;
        $produtos = [];
        $titulo = "Todos";

        // DEBUG: Mostrar qual categoria foi recebida
        // echo "<pre>Categoria recebida: " . var_export($categoria, true) . "</pre>";

        if ($categoria) {
            $produtos = Produto::getByCategoria($categoria);
            $titulo = ucfirst($categoria); // Deixa a primeira letra maiúscula
            
            // DEBUG: Mostrar quantos produtos foram encontrados
            // echo "<pre>Produtos encontrados: " . count($produtos) . "</pre>";
        } else {
            $produtos = Produto::getAll();
        }

        // --- LINHA NOVA ---
        // Busca a lista de categorias para os filtros da sidebar
        $categorias_filtro = Produto::getAllCategorias(); 
        
        // DEBUG: Mostrar categorias disponíveis
        // echo "<pre>Categorias disponíveis: " . var_export($categorias_filtro, true) . "</pre>";

        // Agora, a view `pagina_principal` terá acesso a $produtos, $titulo e $categorias_filtro
        require_once __DIR__ . '/../../views/pagina_principal.php';
    }
}