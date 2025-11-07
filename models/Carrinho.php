<?php
// models/Carrinho.php
require_once __DIR__ . '/Produto.php';

class Carrinho {

    /**
     * Garante que o carrinho exista na sessão.
     */
    private static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    /**
     * Adiciona um produto ao carrinho na sessão.
     * @param int $produtoId ID do produto
     * @param int $quantidade Quantidade
     * @param string|null $tamanho O tamanho selecionado (PP, M, G, etc.)
     * @return bool
     */
    public static function adicionar($produtoId, $quantidade = 1, $tamanho = null) {
        self::init();

        // --- LÓGICA DE CHAVE ÚNICA ---
        // Cria uma chave única para o item no carrinho (ex: "5_M" or "5_default")
        $cartItemId = $produtoId . '_' . ($tamanho ? $tamanho : 'default');

        // Verifica se o produto (com esse tamanho) já está no carrinho
        if (isset($_SESSION['carrinho'][$cartItemId])) {
            // Se sim, apenas atualiza a quantidade
            $_SESSION['carrinho'][$cartItemId]['quantidade'] += $quantidade;
        } else {
            // Se não, busca os dados do produto para adicionar
            $produto = Produto::getById($produtoId);
            if ($produto) {
                $_SESSION['carrinho'][$cartItemId] = [
                    'id' => $produtoId, // Armazena o ID real do produto
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'imagem' => $produto['imagem'],
                    'quantidade' => $quantidade,
                    'tamanho' => $tamanho // Armazena o tamanho
                ];
            } else {
                return false; // Produto não encontrado
            }
        }
        return true;
    }

    /**
     * Remove um produto do carrinho na sessão.
     * @param string $cartItemId ID único do item no carrinho (ex: "5_M")
     * @return bool
     */
    public static function remover($cartItemId) {
        self::init();

        if (isset($_SESSION['carrinho'][$cartItemId])) {
            unset($_SESSION['carrinho'][$cartItemId]);
            return true;
        }
        return false;
    }

    /**
     * Atualiza a quantidade de um produto no carrinho.
     * @param string $cartItemId ID único do item no carrinho (ex: "5_M")
     * @param int $quantidade Nova quantidade
     * @return bool
     */
    public static function atualizar($cartItemId, $quantidade) {
        self::init();

        // Garante que a quantidade seja pelo menos 1
        $quantidade = max(1, (int)$quantidade);

        if (isset($_SESSION['carrinho'][$cartItemId])) {
            // Se a quantidade for 0 ou menos, remove o item
            if ($quantidade <= 0) {
                return self::remover($cartItemId);
            }
            // Senão, atualiza
            $_SESSION['carrinho'][$cartItemId]['quantidade'] = $quantidade;
            return true;
        }
        return false;
    }

    /**
     * Retorna o conteúdo do carrinho com dados atualizados.
     * @return array
     */
    public static function ver() {
        self::init();

        $carrinhoComDados = [];
        $totalItens = 0;
        $totalPreco = 0;

        // Itera sobre o carrinho da sessão
        foreach ($_SESSION['carrinho'] as $cartItemId => $item) {
            
            // Busca dados atualizados do produto (segurança) usando o ID real
            $produto = Produto::getById($item['id']); 

            if ($produto) {
                $totalItens += $item['quantidade'];
                $totalPreco += $produto['preco'] * $item['quantidade'];

                $carrinhoComDados[] = [
                    'id' => $item['id'], // O ID real do produto
                    'cart_item_id' => $cartItemId, // A chave única do carrinho (ex: "5_M")
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'imagem' => $produto['imagem'],
                    'quantidade' => $item['quantidade'],
                    'tamanho' => $item['tamanho'] ?? null // Retorna o tamanho
                ];
            } else {
                // Se o produto não existe mais no DB, remove do carrinho
                self::remover($cartItemId);
            }
        }

        return [
            'itens' => $carrinhoComDados,
            'totalItens' => $totalItens,
            'totalPreco' => $totalPreco
        ];
    }

    /**
     * Limpa o carrinho (usado após finalizar o pedido).
     */
    public static function limpar() {
        self::init();
        $_SESSION['carrinho'] = [];
    }
}