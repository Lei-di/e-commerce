<?php
require_once '../configuracao/Controller.php';
require_once '../models/Carrinho.php';
require_once '../models/Pedido.php';

class CarrinhoController extends Controller {

    public function adicionar() {
        $carrinho = new Carrinho();
        // Obter os dados do produto do corpo da requisição POST
        $data = json_decode(file_get_contents('php://input'), true);
        $produtoId = $data['produto_id'] ?? null;
        $quantidade = $data['quantidade'] ?? 1;

        if ($produtoId) {
            $carrinho->adicionarItem($produtoId, $quantidade);
            $this->response(['status' => 'success', 'message' => 'Item adicionado ao carrinho.'], 200);
        } else {
            $this->response(['status' => 'error', 'message' => 'Produto não especificado.'], 400);
        }
    }

    public function remover() {
        $carrinho = new Carrinho();
        $data = json_decode(file_get_contents('php://input'), true);
        $produtoId = $data['produto_id'] ?? null;

        if ($produtoId && $carrinho->removerItem($produtoId)) {
            $this->response(['status' => 'success', 'message' => 'Item removido do carrinho.'], 200);
        } else {
            $this->response(['status' => 'error', 'message' => 'Item não encontrado no carrinho.'], 404);
        }
    }

    public function ver() {
        $carrinho = new Carrinho();
        $itens = $carrinho->getItens();
        $this->response($itens, 200);
    }

    public function finalizar() {
        $carrinho = new Carrinho();
        $pedido = new Pedido();
        $itensCarrinho = $carrinho->getItens();

        $resultado = $pedido->finalizarPedido($itensCarrinho);

        if ($resultado['status'] == 'success') {
            $carrinho->esvaziarCarrinho(); // Esvaziar o carrinho após a compra
        }

        $this->response($resultado, 200);
    }
}