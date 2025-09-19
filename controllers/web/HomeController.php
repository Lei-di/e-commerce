<?php
class HomeController {
    public function index() {
        // Página principal
        require __DIR__ . '/../../views/pagina_principal.php';
    }

    public function categorias() {
        // Página de categorias (Feminino/Masculino/Acessórios)
        require __DIR__ . '/../../views/categorias.php';
    }
}
