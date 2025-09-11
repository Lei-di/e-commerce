<?php
require_once 'models/Usuario.php';
require_once 'configuracao/Controller.php';

class UsuarioController extends Controller {
    public function registrar() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['nome'], $data['email'], $data['senha'])) {
            $this->jsonError("Campos obrigatórios faltando", 400);
        }

        $resultado = Usuario::registrar($data['nome'], $data['email'], $data['senha']);

        if ($resultado) {
            $this->jsonResponse([
                "mensagem" => "Usuário registrado com sucesso",
                "usuario" => $resultado
            ], 201);
        } else {
            $this->jsonError("Usuário já existe", 409);
        }
    }
}
