<?php
class CheckoutController {
    public function index() {
        // Lógica para verificar se o usuário está logado pode ser adicionada aqui
        
        // Simplesmente carrega a view de checkout
        // CORREÇÃO AQUI: Trocado dirname(_FILE_) por __DIR__
        require_once __DIR__ . '/../../views/checkout.php';
    }
}
?>