<?php

class Pedido {

    // Método para simular a criação de um pedido a partir do carrinho
    public function finalizarPedido($carrinho) {
        if (empty($carrinho)) {
            return ['status' => 'error', 'message' => 'O carrinho está vazio.'];
        }

        // Simular a criação do pedido (em um cenário real, salvaria no BD)
        $pedidoId = rand(1000, 9999); // ID de pedido aleatório

        // Retorna um objeto de pedido simulado
        return [
            'status' => 'success',
            'message' => 'Pedido finalizado com sucesso!',
            'pedidoId' => $pedidoId,
            'itens' => $carrinho
        ];
    }
}
