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
        // Busca os dados do produto para obter a quantidade em estoque
        $produto = Produto::getById($produtoId);

        // Verifica se o produto existe
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }
        
        // Compara o estoque disponível com a quantidade solicitada
        $estoqueDisponivel = (int)$produto['estoque'];
        if ($quantidade > $estoqueDisponivel) {
            // Se não houver estoque, retorna um erro
            return ['success' => false, 'message' => 'Estoque insuficiente.'];
        }

        // Se o carrinho não existir na sessão, cria o array
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Adiciona ou atualiza o item no carrinho
        $_SESSION['carrinho'][$produtoId] = $quantidade;

        // Retorna sucesso se o item foi adicionado
        return ['success' => true];
    }

    // Método para remover um item do carrinho
    public function removerItem($produtoId) {
        if (isset($_SESSION['carrinho'][$produtoId])) {
            unset($_SESSION['carrinho'][$produtoId]);
            return true;
        }
        return false; // Retorna falha se o item não existir
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


    // Método para esvaziar o carrinho após a finalização da compra
    public function esvaziarCarrinho() {
        unset($_SESSION['carrinho']);
    }
}