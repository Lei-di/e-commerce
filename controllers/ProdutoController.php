<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Produto.php';

class ProdutoController extends Controller {

    // --- NOVA FUNÇÃO PARA FILTROS COMBINADOS ---
    public function filtrar() {
        try {
            $filtros = [];
            
            // Coleta os filtros da URL (GET parameters)
            if (!empty($_GET['categoria'])) {
                $filtros['categoria'] = $_GET['categoria'];
            }
            if (!empty($_GET['min'])) {
                $filtros['min'] = $_GET['min'];
            }
            if (!empty($_GET['max'])) {
                $filtros['max'] = $_GET['max'];
            }

            // Chama o novo método do model
            $produtos = Produto::getByFiltros($filtros);
            $this->jsonResponse($produtos);

        } catch (Exception $e) {
            $this->jsonError("Erro ao filtrar produtos: " . $e->getMessage(), 500);
        }
    }
    // --- FIM DA NOVA FUNÇÃO ---


    public function listarTodos() {
        try {
            $produtos = Produto::getAll();
            $this->jsonResponse($produtos);
        } catch (Exception $e) {
            $this->jsonError("Erro ao listar produtos: " . $e->getMessage(), 500);
        }
    }

    public function buscarPorId($id) {
        try {
            $produto = Produto::getById($id);
            if ($produto) {
                $this->jsonResponse($produto);
            } else {
                $this->jsonError("Produto não encontrado", 404);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao buscar produto: " . $e->getMessage(), 500);
        }
    }

    public function deletarProduto($id) {
        try {
            if (Produto::deletar($id)) {
                $this->jsonResponse(["mensagem" => "Produto deletado com sucesso"]);
            } else {
                $this->jsonError("Produto não encontrado para deletar", 404);
            }
        } catch (Exception $e) {
            $this->jsonError("Erro ao deletar produto: " . $e->getMessage(), 500);
        }
    }

    public function buscarProdutos($termo) {
        try {
            $produtos = Produto::buscarPorNome($termo);
            $this->jsonResponse($produtos);
        } catch (Exception $e) {
            $this->jsonError("Erro ao buscar produtos: " . $e->getMessage(), 500);
        }
    }

    public function criarProduto() {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);

            if (!isset($dados['nome']) || !isset($dados['preco'])) {
                $this->jsonError("Os campos 'nome' e 'preco' são obrigatórios.", 400);
                return;
            }

            $novoProduto = Produto::criar($dados);
            $this->jsonResponse($novoProduto, 201);
        } catch (Exception $e) {
            $this->jsonError("Erro ao criar produto: " . $e->getMessage(), 500);
        }
    }

    public function atualizarProduto($id) {
        try {
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
        } catch (Exception $e) {
            $this->jsonError("Erro ao atualizar produto: " . $e->getMessage(), 500);
        }
    }

    public function buscarPorCategoria($categoria) {
        try {
            $produtos = Produto::getByCategoria($categoria);
            $this->jsonResponse($produtos);
        } catch (Exception $e) {
            $this->jsonError("Erro ao buscar produtos por categoria: " . $e->getMessage(), 500);
        }
    }

    public function buscarPorPreco($min, $max) {
        try {
            $produtos = Produto::getByFaixaDePreco($min, $max);
            $this->jsonResponse($produtos);
        } catch (Exception $e) {
            $this->jsonError("Erro ao buscar produtos por preço: " . $e->getMessage(), 500);
        }
    }
}