<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class LoginController extends Controller {
    
    // Redireciona para a página de login real (raiz)
    public function index() {
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['senha'])) {
            $this->jsonError("Email e senha são obrigatórios", 400);
        }

        // O Model (Usuario::validarLogin) agora faz a verificação de hash
        $usuario = Usuario::validarLogin($data['email'], $data['senha']);

        if ($usuario) {
            // Salva o ID do cliente na sessão
            $_SESSION['usuario_id'] = $usuario['id_cliente']; 
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            $this->jsonResponse([
                "mensagem" => "Login bem-sucedido!",
                "usuario" => [
                    "id" => $usuario['id_cliente'],
                    "nome" => $usuario['nome'],
                    "email" => $usuario['email']
                ]
            ]);
        } else {
            $this->jsonError("Usuário ou senha inválidos", 401);
        }
    }

    /**
     * NOVO MÉTODO: Logout
     * Destroi a sessão e redireciona para a home.
     */
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset(); // Limpa as variáveis da sessão
        session_destroy(); // Destroi a sessão
        
        // Redireciona para a página principal (login)
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}