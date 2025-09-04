<?php

class Carrinho {

    // Construtor para iniciar a sessão se ela ainda não foi iniciada
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Método para adicionar um item ao carrinho
    public function adicionarItem($produtoId, $quantidade) {
        // Se o carrinho não existir na sessão, crie-o como um array vazio
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Exemplo de como adicionar/atualizar o item
        // No mundo real, você verificaria se o produto existe
        $_SESSION['carrinho'][$produtoId] = $quantidade;

        return true; // Retorna sucesso
    }

    // Método para remover um item do carrinho
    public function removerItem($produtoId) {
        if (isset($_SESSION['carrinho'][$produtoId])) {
            unset($_SESSION['carrinho'][$produtoId]);
            return true;
        }
        return false; // Retorna falha se o item não existir
    }

    // Método para obter todos os itens do carrinho
    public function getItens() {
        return $_SESSION['carrinho'] ?? [];
    }

    // Método para esvaziar o carrinho após a finalização da compra
    public function esvaziarCarrinho() {
        unset($_SESSION['carrinho']);
    }
}