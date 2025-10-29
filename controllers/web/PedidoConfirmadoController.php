<?php
class PedidoConfirmadoController {
    public function index() {
        // Lógica para verificar se o usuário está logado pode ser adicionada aqui
        
        // Simplesmente carrega a view de pedido confirmado
        // CORREÇÃO AQUI: Trocado dirname(_FILE_) por __DIR__
        require_once __DIR__ . '/../../views/pedidoconfirmado.php';
    }
}
?>