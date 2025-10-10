<?php

class Pedido {

    // Método para simular a criação de um pedido a partir do carrinho
    public function finalizarPedido($carrinho) {
        if (empty($carrinho)) {
            return ['status' => 'error', 'message' => 'O carrinho está vazio.'];
        }

        // Simular a criação do pedido 
        $pedidoId = rand(1000, 9999); // ID de pedido aleatório

        // Retorna um objeto de pedido simulado
        return [
            'status' => 'success',
            'message' => 'Pedido finalizado com sucesso!',
            'pedidoId' => $pedidoId,
            'itens' => $carrinho
        ];
    }

    /**
     * Busca todos os pedidos de um cliente específico no banco de dados,
     * @param int $idCliente O ID do cliente.
     * @return array A lista de pedidos com seus itens.
     */
    public static function buscarPorCliente($idCliente) {
        global $conn;

        // Query principal para buscar os pedidos do cliente
        $sqlPedidos = "SELECT id_pedido, data_pedido, status, total
                       FROM pedidos
                       WHERE id_cliente = :idCliente
                       ORDER BY data_pedido DESC";

        $stmtPedidos = $conn->prepare($sqlPedidos);
        $stmtPedidos->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
        $stmtPedidos->execute();
        $pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

        // Query para buscar os itens de cada pedido
        $sqlItens = "SELECT
                        ip.quantidade,
                        ip.preco_unitario,
                        p.nome AS produto_nome,
                        p.imagem AS produto_imagem
                     FROM itens_pedido ip
                     JOIN produtos p ON ip.id_produto = p.id_produto
                     WHERE ip.id_pedido = :idPedido";
        
        $stmtItens = $conn->prepare($sqlItens);

        // Adiciona os itens a cada pedido correspondente
        foreach ($pedidos as $key => $pedido) {
            $stmtItens->bindParam(':idPedido', $pedido['id_pedido'], PDO::PARAM_INT);
            $stmtItens->execute();
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
            $pedidos[$key]['itens'] = $itens;
        }

        return $pedidos;
    }
}