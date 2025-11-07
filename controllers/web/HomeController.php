<?php
// 1. Inclui o Model de Produto
require_once __DIR__ . '/../../models/Produto.php';

class HomeController {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Verificação de Segurança
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/'); 
            exit; 
        }
        
        // --- 3. LÓGICA DE BUSCA DE DADOS (CORRIGIDA) ---
        
        // Monta o array de filtros esperado pelo Model
        $filtros = [];
        if (!empty($_GET['categoria'])) {
            $filtros['categoria'] = $_GET['categoria'];
        }
        if (!empty($_GET['min'])) {
            $filtros['min'] = $_GET['min'];
        }
        if (!empty($_GET['max'])) {
            $filtros['max'] = $_GET['max'];
        }
        
        // Pega o termo de busca (que não faz parte do getByFiltros)
        $busca = $_GET['busca'] ?? null;

        // Chama os métodos CORRETOS do seu Model
        if ($busca) {
            // Usa a função de busca por nome
            $produtos = Produto::buscarPorNome($busca); 
        } else {
            // Usa a função de filtros combinados
            $produtos = Produto::getByFiltros($filtros); 
        }
        
        // Busca as categorias para o menu de filtro
        $categorias_filtro = Produto::getAllCategorias(); 

        // Define o título da página
        if ($busca) {
            $titulo = "Busca por: \"" . htmlspecialchars($busca) . "\"";
        } elseif (!empty($filtros['categoria'])) {
            $titulo = ucfirst($filtros['categoria']); // Ex: "Feminino"
        } else {
            $titulo = "Todos os Produtos"; // Título padrão
        }
        // --- FIM DA LÓGICA CORRIGIDA ---

        // 4. Carrega a View.
        // Agora, $produtos, $categorias_filtro, e $titulo
        // serão passados corretamente para sua 'pagina_principal.php'.
        require_once __DIR__ . '/../../views/pagina_principal.php';
    }
}
?>