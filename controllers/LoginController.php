<?php
require_once 'configuracao/Controller.php';

class LoginController extends Controller {
    public function index() {
        // Por enquanto, só mostra uma mensagem simples
        $this->jsonResponse(["mensagem" => "Página inicial - login não implementado ainda"]);
    }

    public function login() {
        // Só uma resposta fake
        $this->jsonResponse(["mensagem" => "Função de login ainda não implementada"]);
    }
}
