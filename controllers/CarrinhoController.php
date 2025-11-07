<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Pedido.php'; // Embora não usado aqui, é bom manter
require_once __DIR__ . '/../models/Produto.php'; // Necessário para o Carrinho::adicionar

class CarrinhoController extends Controller {

    public function adicionar() {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $produtoId = $dados['produto_id'] ?? null;
            $quantidade = $dados['quantidade'] ?? 1;
            $tamanho = $dados['tamanho'] ?? null; // Pega o tamanho

            if (!$produtoId) {
                $this->jsonError("ID do produto é obrigatório.", 400);
                return;
            }

            // Passa o tamanho para o Model
            $resultado = Carrinho::adicionar($produtoId, $quantidade, $tamanho);
            
            if ($resultado) {
                // Retorna o carrinho atualizado
                $carrinhoAtualizado = Carrinho::ver();
                $this->jsonResponse(['mensagem' => 'Produto adicionado!', 'carrinho' => $carrinhoAtualizado]);
            } else {
                $this->jsonError("Não foi possível adicionar o produto (talvez não exista).", 500);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao adicionar ao carrinho: " . $e->getMessage(), 500);
        }
    }

    public function remover() {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $cartItemId = $dados['cart_item_id'] ?? null; // Modificado de produto_id para cart_item_id

            if (!$cartItemId) {
                $this->jsonError("ID do item do carrinho é obrigatório.", 400);
                return;
            }

            
            $removido = Carrinho::remover($cartItemId); // Usa a nova chave
            
            if ($removido) {
                // Retorna o carrinho atualizado
                $carrinhoAtualizado = Carrinho::ver();
                $this->jsonResponse(['mensagem' => 'Produto removido!', 'carrinho' => $carrinhoAtualizado]);
            } else {
                $this->jsonError("Item do carrinho não encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao remover do carrinho: " . $e->getMessage(), 500);
        }
    }

    public function atualizar() {
        try {
            // Esta rota é acessada via PUT
            $dados = json_decode(file_get_contents('php://input'), true);
            $cartItemId = $dados['cart_item_id'] ?? null; // Modificado de produto_id para cart_item_id
            $quantidade = $dados['quantidade'] ?? null;

            if (!$cartItemId || $quantidade === null) {
                $this->jsonError("ID do item do carrinho e quantidade são obrigatórios.", 400);
                return;
            }

            
            $atualizado = Carrinho::atualizar($cartItemId, $quantidade); // Usa a nova chave

            if ($atualizado) {
                // Retorna o carrinho atualizado
                $carrinhoAtualizado = Carrinho::ver();
                $this->jsonResponse(['mensagem' => 'Quantidade atualizada!', 'carrinho' => $carrinhoAtualizado]);
            } else {
                $this->jsonError("Item do carrinho não encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao atualizar o carrinho: " . $e->getMessage(), 500);
        }
    }

    public function ver() {
        try {
            $carrinho = Carrinho::ver();
            $this->jsonResponse(['carrinho' => $carrinho]);
        } catch (Exception $e) {
            $this->jsonError("Erro ao ver o carrinho: " . $e->getMessage(), 500);
        }
    }

    public function finalizar() {
        // garante sessão
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Tenta obter o ID do usuário em sessão com chaves comuns
        $clienteId = $_SESSION['user_id'] ?? $_SESSION['id_usuario'] ?? $_SESSION['cliente_id'] ?? $_SESSION['usuario_id'] ?? null;

        if (!$clienteId) {
            $this->jsonError("Usuário não autenticado. Faça login para finalizar a compra.", 401);
            return;
        }

        // Pega os itens do carrinho com a nova lógica
        $carrinho = Carrinho::ver();
        $itensCarrinho = $carrinho['itens']; // A função 'ver' agora retorna um array com 'itens'

        if (empty($itensCarrinho)) {
            $this->jsonError("Carrinho vazio.", 400);
            return;
        }

        try {
            // A função criarPedido precisará ser ajustada para receber o array de $itensCarrinho
            // que agora inclui 'tamanho'
            $resultado = Pedido::criarPedido($clienteId, $itensCarrinho);

            if (!is_array($resultado)) {
                $this->jsonError("Resposta inesperada do model Pedido.", 500);
                return;
            }

            if (($resultado['status'] ?? false) === 'success' || ($resultado['success'] ?? false)) {

                Carrinho::limpar(); // Usa a nova função de limpar

                $pedidoId = $resultado['pedido_id'] ?? $resultado['id_pedido'] ?? $resultado['pedidoId'] ?? $resultado['id'] ?? null;
                $total = $resultado['total'] ?? null;

                $res = [
                    'status' => 'success',
                    'message' => $resultado['message'] ?? 'Pedido finalizado',
                    'pedidoId' => $pedidoId
                ];
                if ($total !== null) $res['total'] = $total;

                $this->jsonResponse($res, 201);
            } else {
                $msg = $resultado['message'] ?? ($resultado['erro'] ?? 'Falha ao finalizar pedido');
                $this->jsonError($msg, 400);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao finalizar pedido: " . $e->getMessage(), 500);
        }
    }
}