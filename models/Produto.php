<?php
//require_once 'configuracao/Model.php';

class Produto {
    private static $produtos = [
        [
            "id" => 1,
            "nome" => "Vestido Longo Elegance",
            "preco" => 199.90,
            "estoque" => 12,
            "imagem" => "vestido.jpg"
        ],
        [
            "id" => 2,
            "nome" => "Camisa Social Masculina",
            "preco" => 129.90,
            "estoque" => 20,
            "imagem" => "camisa_social.jpg"
        ],
        [
            "id" => 3,
            "nome" => "Calça Jeans Feminina",
            "preco" => 149.90,
            "estoque" => 15,
            "imagem" => "calca_jeans.jpg"
        ]
    ];

    public static function getAll() {
        return self::$produtos;
    }

    public static function getById($id) {
        foreach (self::$produtos as $produto) {
            if ($produto["id"] == $id) {
                return $produto;
            }
        }
        return null;
    }
}
