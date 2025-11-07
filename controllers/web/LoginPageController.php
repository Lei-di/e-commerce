<?php
class LoginPageController {
    public function index() {
        // Inicia a sessão para verificar se já está logado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Se o usuário JÁ ESTÁ LOGADO, manda ele para a /home (loja)
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/home'); // Modificado de /perfil para /home
            exit;
        }
        
        // Se não está logado, mostra a página de login
        require_once __DIR__ . '/../../views/login.php';
    }
}
?>