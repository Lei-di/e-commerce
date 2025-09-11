<?php
require_once '../models/Produto.php';
require_once '../configuracao/Controller.php';

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
}
