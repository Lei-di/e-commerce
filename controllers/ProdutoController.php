<?php
require_once __DIR__ . '/Controller.php'; // Aponta para o controller base na mesma pasta
require_once __DIR__ . '/../models/Produto.php'; // Sobe um nível e entra em models

class ProdutoController extends Controller{
    public function listarTodos() {
        $this->jsonResponse(Produto::getAll());
    }

    public function buscarPorId($id) {
        $produto = Produto::getById($id);

        if ($produto) {
            $this->jsonResponse($produto);
        } else {
            $this->jsonError("Produto não encontrado", 404);
        }
    }

    public function deletarProduto($id) {
        if (Produto::deletar($id)) {
            $this->jsonResponse(["mensagem" => "Produto deletado com sucesso"]);
        } else {
            $this->jsonError("Produto não encontrado para deletar", 404);
        }
    }

    public function buscarProdutos($termo) {
        $produtos = Produto::buscarPorNome($termo);
        $this->jsonResponse($produtos);
    }
}