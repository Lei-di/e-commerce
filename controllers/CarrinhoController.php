<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarrinhoController extends Controller {

    // ... (outros métodos como adicionar, atualizar, etc. ficam iguais) ...

    public function finalizar() {
        // Inicia a sessão para garantir acesso a $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Verifica se o usuário está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            $this->jsonError("Acesso não autorizado. Por favor, faça o login para finalizar a compra.", 401);
            return;
        }

        $carrinho = new Carrinho();
        $itensCarrinho = $carrinho->getItens();

        if (empty($itensCarrinho)) {
            $this->jsonError("O carrinho está vazio.", 400);
            return;
        }

        $clienteId = $_SESSION['usuario_id'];

        // LINHA CORRIGIDA: Chamando o método correto 'criarPedido'
        $resultado = Pedido::criarPedido($clienteId, $itensCarrinho);

        if ($resultado['success']) {
            $carrinho->esvaziarCarrinho();
            $this->jsonResponse([
                'status' => 'success',
                'message' => $resultado['message'],
                'pedidoId' => $resultado['pedidoId']
            ], 201);
        } else {
            $this->jsonError($resultado['message'], 500);
        }
    }

    // ... (outros métodos) ...
}