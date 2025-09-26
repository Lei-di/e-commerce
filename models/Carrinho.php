<?php
// É necessário incluir o arquivo do model Produto para acessar o estoque
require_once __DIR__ . '/Produto.php';

class Carrinho {

    // Construtor para iniciar a sessão se ela ainda não foi iniciada
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Método para adicionar um item ao carrinho com validação de estoque
    public function adicionarItem($produtoId, $quantidade) {
        $produto = Produto::getById($produtoId);
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }
        
        $estoqueDisponivel = (int)$produto['estoque'];
        if ($quantidade > $estoqueDisponivel) {
            return ['success' => false, 'message' => 'Estoque insuficiente.'];
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $_SESSION['carrinho'][$produtoId] = $quantidade;
        return ['success' => true];
    }

    /**
     * NOVO MÉTODO
     * Atualiza a quantidade de um item no carrinho com validação de estoque.
     */
    public function atualizarItem($produtoId, $novaQuantidade) {
        // Se a quantidade for 0, remove o item
        if ($novaQuantidade <= 0) {
            $this->removerItem($produtoId);
            return ['success' => true, 'message' => 'Item removido do carrinho.'];
        }

        // Busca os dados do produto para obter o estoque
        $produto = Produto::getById($produtoId);
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        // Compara o estoque com a nova quantidade solicitada
        $estoqueDisponivel = (int)$produto['estoque'];
        if ($novaQuantidade > $estoqueDisponivel) {
            return ['success' => false, 'message' => 'Estoque insuficiente para a quantidade solicitada.'];
        }

        // Verifica se o item existe no carrinho para então atualizar
        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId] = $novaQuantidade;
            return ['success' => true, 'message' => 'Quantidade atualizada com sucesso.'];
        }

        return ['success' => false, 'message' => 'Item não encontrado no carrinho para atualizar.'];
    }

    // Método para remover um item do carrinho
    public function removerItem($produtoId) {
        if (isset($_SESSION['carrinho'][$produtoId])) {
            unset($_SESSION['carrinho'][$produtoId]);
            return true;
        }
        return false;
    }

    // Método para obter todos os itens do carrinho com detalhes
    public function getItens() {
        $itensCarrinho = $_SESSION['carrinho'] ?? [];
        $itensDetalhados = [];

        if (empty($itensCarrinho)) {
            return [];
        }

        foreach ($itensCarrinho as $produtoId => $quantidade) {
            $produto = Produto::getById($produtoId);
            if ($produto) {
                $itensDetalhados[] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'imagem' => $produto['imagem'],
                    'quantidade' => $quantidade
                ];
            }
        }
        return $itensDetalhados;
    }

    // Método para esvaziar o carrinho
    public function esvaziarCarrinho() {
        unset($_SESSION['carrinho']);
    }
}