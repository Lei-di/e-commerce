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

    public function criarProduto() {
        // Pega os dados do corpo da requisição (JSON)
        $dados = json_decode(file_get_contents('php://input'), true);

        // Validação simples dos dados recebidos
        if (!isset($dados['nome']) || !isset($dados['preco'])) {
            $this->jsonError("Os campos 'nome' e 'preco' são obrigatórios.", 400);
            return;
        }

        $novoProduto = Produto::criar($dados);
        $this->jsonResponse($novoProduto, 201); // 201 Created
    }

    public function atualizarProduto($id) {
        // Pega os dados do corpo da requisição (JSON)
        $dados = json_decode(file_get_contents('php://input'), true);

        if (empty($dados)) {
            $this->jsonError("Nenhum dado fornecido para atualização.", 400);
            return;
        }

        $produtoAtualizado = Produto::atualizar($id, $dados);

        if ($produtoAtualizado) {
            $this->jsonResponse($produtoAtualizado);
        } else {
            $this->jsonError("Produto não encontrado para atualizar.", 404);
        }
    }
}
