<?php

class CarrinhoPageController {
    public function index() {
        // Define o título da página
        $titulo = "Carrinho de Compras";
        
        // Carrega a view do carrinho
        require_once __DIR__ . '/../../views/carrinho.php';
    }
}

