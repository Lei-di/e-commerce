<?php
class PerfilController {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // --- ADICIONADO: Verificação de Segurança ---
        // Se não houver 'usuario_id' na sessão, redireciona para o login
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit; // Interrompe a execução
        }
        // --- FIM DA VERIFICAÇÃO ---

        // Se chegou aqui, o usuário está logado e pode ver a página
        require_once __DIR__ . '/../../views/perfil.php';
    }
}