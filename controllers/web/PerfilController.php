<?php
// controllers/web/PerfilController.php
require_once __DIR__ . '/../../models/Usuario.php'; 

class PerfilController {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $idCliente = $_SESSION['usuario_id'];
        $usuario = Usuario::getById($idCliente);

        if (!$usuario) {
            session_unset();
            session_destroy();
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        require_once __DIR__ . '/../../views/perfil.php';
    }

    /**
     * Lida com a atualização dos dados do perfil do usuário.
     */
    public function atualizar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json'); // Garante que a resposta será JSON

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
            exit;
        }

        $idCliente = $_SESSION['usuario_id'];
        $input = file_get_contents('php://input');
        $dadosAtualizados = json_decode($input, true);

        if (empty($dadosAtualizados)) {
            echo json_encode(['success' => false, 'message' => 'Dados de atualização vazios.']);
            exit;
        }

        try {
            // Chama o método do Model para atualizar o usuário
            $sucesso = Usuario::atualizarPerfil($idCliente, $dadosAtualizados);

            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Falha ao atualizar o perfil.']);
            }
        } catch (Exception $e) {
            error_log("Erro ao atualizar perfil: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro interno ao atualizar o perfil.']);
        }
    }
}