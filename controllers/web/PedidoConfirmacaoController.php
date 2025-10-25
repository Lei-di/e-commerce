<?php
class PedidoConfirmadoController {
    public function index() {
        // Lógica para verificar se o usuário está logado pode ser adicionada aqui
        
        // Simplesmente carrega a view de pedido confirmado
        require_once _DIR_ . '/../../views/pedidoconfirmado.php';
    }
}
?>