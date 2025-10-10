<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarrinhoController extends Controller {

    public function adicionar() {
        $carrinho = new Carrinho();
        $data = json_decode(file_get_contents('php://input'), true);
        $produtoId = $data['produto_id'] ?? null;
        $quantidade = $data['quantidade'] ?? 1;

        if (!$produtoId) {
            $this->jsonError('Produto não especificado.', 400);
            return;
        }

        $resultado = $carrinho->adicionarItem($produtoId, $quantidade);
        if ($resultado['success']) {
            $this->jsonResponse(['status' => 'success', 'message' => 'Item adicionado ao carrinho.'], 200);
        } else {
            $this->jsonError($resultado['message'], 400);
        }
    }
    
    public function atualizar() {
        $carrinho = new Carrinho();
        $data = json_decode(file_get_contents('php://input'), true);
        $produtoId = $data['produto_id'] ?? null;
        $quantidade = $data['quantidade'] ?? null;

        if (!$produtoId || $quantidade === null) {
            $this->jsonError('ID do produto e quantidade são obrigatórios.', 400);
            return;
        }

        $resultado = $carrinho->atualizarItem($produtoId, (int)$quantidade);

        if ($resultado['success']) {
            $this->jsonResponse(['status' => 'success', 'message' => $resultado['message']], 200);
        } else {
            $this->jsonError($resultado['message'], 400);
        }
    }

    public function remover() {
        $carrinho = new Carrinho();
        $data = json_decode(file_get_contents('php://input'), true);
        $produtoId = $data['produto_id'] ?? null;

        if ($produtoId && $carrinho->removerItem($produtoId)) {
            $this->jsonResponse(['status' => 'success', 'message' => 'Item removido do carrinho.'], 200);
        } else {
            $this->jsonError('Item não encontrado no carrinho.', 404);
        }
    }

    public function ver() {
        $carrinho = new Carrinho();
        $itens = $carrinho->getItens();
        $totalCarrinho = 0;
        $itensComSubtotal = [];

        foreach ($itens as $item) {
            $subtotal = $item['preco'] * $item['quantidade'];
            $totalCarrinho += $subtotal;
            $item['subtotal'] = $subtotal;
            $itensComSubtotal[] = $item;
        }

        $resposta = [
            'itens' => $itensComSubtotal,
            'total' => $totalCarrinho
        ];

        $this->jsonResponse($resposta, 200);
    }

    public function finalizar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

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
}