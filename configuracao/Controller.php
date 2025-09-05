<?php

class Controller {
    /**
     * Retorna uma resposta JSON padronizada
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // Garante que a resposta finalize aqui
    }

    /**
     * Retorna uma mensagem de erro padronizada
     */
    protected function jsonError($message, $statusCode = 400) {
        $this->jsonResponse(["erro" => $message], $statusCode);
    }
}
