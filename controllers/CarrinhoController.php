<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarrinhoController extends Controller {

    public function adicionar() {
        $carrinho = new Carrinho();
        // Obter os dados do produto do corpo da requisição POST
        $data = json_decode(file_get_contents('php://input'), true);
        $produtoId = $data['produto_id'] ?? null;
        $quantidade = $data['quantidade'] ?? 1;

        if (!$produtoId) {
            $this->jsonError('Produto não especificado.', 400);
            return;
        }

        // Chama o método modificado que retorna um array com status e mensagem
        $resultado = $carrinho->adicionarItem($produtoId, $quantidade);

        // Se o resultado for sucesso, envia a resposta padrão
        if ($resultado['success']) {
            $this->jsonResponse(['status' => 'success', 'message' => 'Item adicionado ao carrinho.'], 200);
        } else {
            // Se falhar, usa a mensagem de erro retornada pelo model e o código de erro 400
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
        $carrinho = new Carrinho();
        $pedido = new Pedido();
        $itensCarrinho = $carrinho->getItens();

        $resultado = $pedido->finalizarPedido($itensCarrinho);

        if ($resultado['status'] == 'success') {
            $carrinho->esvaziarCarrinho(); // Esvaziar o carrinho após a compra
        }

        $this->jsonResponse($resultado, 200);
    }
}