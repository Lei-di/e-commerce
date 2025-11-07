<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController extends Controller {
    
    public function registrar() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validação simples dos campos (pode ser mais robusta)
        if (!isset($data['nome'], $data['email'], $data['senha'], $data['celular'], $data['cpf'], $data['endereco'])) {
            $this->jsonError("Todos os campos são obrigatórios", 400);
            return;
        }

        try {
            // Chama o novo método do Model
            $resultado = Usuario::registrar($data);
            
            $this->jsonResponse([
                "mensagem" => "Usuário registrado com sucesso",
                "usuario" => $resultado
            ], 201);

        } catch (Exception $e) {
            // Captura erros (ex: "e-mail já existe") vindos do Model
            $this->jsonError($e->getMessage(), 409); // 409 Conflict
        }
    }
}