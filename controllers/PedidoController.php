<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidoController extends Controller {

    /**
     * Lista todos os pedidos do usuário logado.
     */
    public function listarPedidosDoUsuario() {
        // Inicia a sessão para garantir que temos acesso a $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Verifica se o usuário está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            $this->jsonError("Acesso não autorizado. Por favor, faça o login.", 401);
            return;
        }

        try {
            // 2. Obtém o ID do usuário da sessão
            $clienteId = $_SESSION['usuario_id'];

            // 3. Busca os pedidos no Model
            $pedidos = Pedido::buscarPorCliente($clienteId);

            // 4. Retorna a lista de pedidos como JSON
            $this->jsonResponse($pedidos);

        } catch (Exception $e) {
            $this->jsonError("Erro ao buscar histórico de pedidos: " . $e->getMessage(), 500);
        }
    }
}