<?php
// É necessário incluir a configuração do banco para usar a variável $conn
require_once __DIR__ . '/../config/configBanco.php';

class Pedido {

    /**
     * Cria um novo pedido no banco de dados a partir dos itens do carrinho.
     *
     * @param int $idCliente O ID do cliente que está fazendo o pedido.
     * @param array $itensCarrinho A lista de itens do carrinho.
     * @return array Retorna o resultado da operação.
     */
    public static function criarPedido($idCliente, $itensCarrinho) {
        global $conn;

        if (empty($itensCarrinho)) {
            return ['success' => false, 'message' => 'O carrinho está vazio.'];
        }

        // 1. Iniciar uma transação
        $conn->beginTransaction();

        try {
            // 2. Calcular o total do pedido
            $totalPedido = 0;
            foreach ($itensCarrinho as $item) {
                $totalPedido += $item['preco'] * $item['quantidade'];
            }

            // 3. Inserir na tabela 'pedidos'
            $sqlPedido = "INSERT INTO pedidos (id_cliente, total, status) VALUES (:idCliente, :total, 'Processando')";
            $stmtPedido = $conn->prepare($sqlPedido);
            $stmtPedido->execute([
                ':idCliente' => $idCliente,
                ':total' => $totalPedido
            ]);

            // 4. Obter o ID do pedido recém-criado
            $pedidoId = $conn->lastInsertId();

            // 5. Inserir cada item do carrinho na tabela 'itens_pedido' e abater do estoque
            $sqlItem = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)";
            $stmtItem = $conn->prepare($sqlItem);

            $sqlEstoque = "UPDATE estoque SET quantidade = quantidade - :quantidade WHERE id_produto = :id_produto";
            $stmtEstoque = $conn->prepare($sqlEstoque);

            foreach ($itensCarrinho as $item) {
                // Inserir o item no pedido
                $stmtItem->execute([
                    ':id_pedido' => $pedidoId,
                    ':id_produto' => $item['id'],
                    ':quantidade' => $item['quantidade'],
                    ':preco_unitario' => $item['preco']
                ]);

                // (Bônus) Abater do estoque
                $stmtEstoque->execute([
                    ':quantidade' => $item['quantidade'],
                    ':id_produto' => $item['id']
                ]);
            }

            // 6. Se tudo deu certo, comitar a transação
            $conn->commit();

            return [
                'success' => true,
                'message' => 'Pedido finalizado com sucesso!',
                'pedidoId' => $pedidoId
            ];

        } catch (Exception $e) {
            // 7. Se algo deu errado, reverter a transação
            $conn->rollBack();
            // Para depuração, você pode logar o erro: error_log($e->getMessage());
            return ['success' => false, 'message' => 'Erro ao finalizar o pedido: ' . $e->getMessage()];
        }
    }

    /**
     * Busca todos os pedidos de um cliente específico no banco de dados.
     * @param int $idCliente O ID do cliente.
     * @return array A lista de pedidos com seus itens.
     */
    public static function buscarPorCliente($idCliente) {
        global $conn;

        $sqlPedidos = "SELECT id_pedido, data_pedido, status, total
                       FROM pedidos
                       WHERE id_cliente = :idCliente
                       ORDER BY data_pedido DESC";

        $stmtPedidos = $conn->prepare($sqlPedidos);
        $stmtPedidos->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
        $stmtPedidos->execute();
        $pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

        $sqlItens = "SELECT
                        ip.quantidade,
                        ip.preco_unitario,
                        p.nome AS produto_nome,
                        p.imagem AS produto_imagem
                     FROM itens_pedido ip
                     JOIN produtos p ON ip.id_produto = p.id_produto
                     WHERE ip.id_pedido = :idPedido";
        
        $stmtItens = $conn->prepare($sqlItens);

        foreach ($pedidos as $key => $pedido) {
            $stmtItens->bindParam(':idPedido', $pedido['id_pedido'], PDO::PARAM_INT);
            $stmtItens->execute();
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
            $pedidos[$key]['itens'] = $itens;
        }

        return $pedidos;
    }
}