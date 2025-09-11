<?php
require_once 'configuracao/Controller.php';
require_once 'models/Usuario.php';

class LoginController extends Controller {
    public function index() {
        // Por enquanto, só mostra uma mensagem simples
        $this->jsonResponse(["mensagem" => "Página inicial - login não implementado ainda"]);
    }

    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['senha'])) {
            $this->jsonError("Email e senha são obrigatórios", 400);
        }

        $usuario = Usuario::validarLogin($data['email'], $data['senha']);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $this->jsonResponse([
                "mensagem" => "Login bem-sucedido!",
                "usuario" => [
                    "id" => $usuario['id'],
                    "nome" => $usuario['nome'],
                    "email" => $usuario['email']
                ]
            ]);
        } else {
            $this->jsonError("Usuário ou senha inválidos", 401);
        }
    }
}