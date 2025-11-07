<?php
class LoginPageController {
    public function index() {
        // Inicia a sessão para verificar se já está logado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Se o usuário já está logado, manda ele para o perfil
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/perfil');
            exit;
        }
        
        // Se não, mostra a página de login
        require_once __DIR__ . '/../../views/login.php';
    }
}
?>