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
        // garante sessão
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // ADIÇÃO 1: Tenta obter o ID do usuário em sessão com chaves comuns
        $clienteId = $_SESSION['user_id'] ?? $_SESSION['id_usuario'] ?? $_SESSION['cliente_id'] ?? $_SESSION['usuario_id'] ?? null;

        if (!$clienteId) {
            // MUDANÇA 1: Mensagem de erro atualizada
            $this->jsonError("Usuário não autenticado. Faça login para finalizar a compra.", 401);
            return;
        }

        $carrinho = new Carrinho();
        $itensCarrinho = $carrinho->getItens();

        if (empty($itensCarrinho)) {
            // MUDANÇA 2: Mensagem de erro simplificada
            $this->jsonError("Carrinho vazio.", 400);
            return;
        }

        // ADIÇÃO 2: Leitura de dados adicionais (opcional)
        /*$body = json_decode(file_get_contents('php://input'), true) ?? [];
        $opcoes = [];
        if (!empty($body['endereco'])) {
            $opcoes['endereco'] = $body['endereco'];
        }
        if (!empty($body['metodo_pagamento'])) {
            $opcoes['metodo_pagamento'] = $body['metodo_pagamento'];
        }*/

        // ADIÇÃO 3: Estrutura try...catch para tratamento de erros de execução
        try {
            $resultado = Pedido::criarPedido($clienteId, $itensCarrinho);

            // ADIÇÃO 4: Validação de retorno e flexibilidade no status/mensagem
            if (!is_array($resultado)) {
                $this->jsonError("Resposta inesperada do model Pedido.", 500);
                return;
            }

            // ADIÇÃO 5: Verifica 'status' ou 'resultado' para sucesso (mais flexível)
            if (($resultado['status'] ?? false) === 'success' || ($resultado['success'] ?? false)) {

                $carrinho->esvaziarCarrinho();

                // ADIÇÃO 6: Normalização do ID do pedido no retorno
                $pedidoId = $resultado['pedido_id'] ?? $resultado['id_pedido'] ?? $resultado['pedidoId'] ?? $resultado['id'] ?? null;
                $total = $resultado['total'] ?? null;

                $res = [
                    'status' => 'success',
                    'message' => $resultado['message'] ?? 'Pedido finalizado',
                    'pedidoId' => $pedidoId // Mantendo a chave original por clareza
                ];
                if ($total !== null) $res['total'] = $total;

                // MUDANÇA 4: Código de status HTTP para 200 (OK) ou 201 (Created)
                $this->jsonResponse($res, 201); // Mantido 201 do código 1
            } else {
                // ADIÇÃO 7: Melhor tratamento de erro (procura por message ou erro, retorna 400)
                $msg = $resultado['message'] ?? ($resultado['erro'] ?? 'Falha ao finalizar pedido');
                $this->jsonError($msg, 400);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao finalizar pedido: " . $e->getMessage(), 500);
        }
    }
}