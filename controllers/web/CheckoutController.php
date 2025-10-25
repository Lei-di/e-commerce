<?php
class CheckoutController {
    public function index() {
        // Lógica para verificar se o usuário está logado pode ser adicionada aqui
        
        // Simplesmente carrega a view de checkout
        require_once _DIR_ . '/../../views/checkout.php';
    }
}
?>