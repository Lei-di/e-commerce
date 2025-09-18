<?php
class HomeController {
    public function index() {
        // Carrega a view da página principal
        require_once __DIR__ . '/../../views/pagina_principal.php';
    }
}