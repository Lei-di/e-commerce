<?php
class CheckoutController {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // --- ADICIONADO: Verificação de Segurança ---
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login?redirect=checkout'); // Manda para o login
            exit; 
        }
        // --- FIM DA VERIFICAÇÃO ---
        
        // Se logado, carrega a view de checkout
        require_once __DIR__ . '/../../views/checkout.php';
    }
}
?>