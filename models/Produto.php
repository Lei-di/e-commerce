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

    /**
     * Deleta um produto pelo ID.
     * @param int $id
     * @return bool Retorna true se o produto foi deletado, false caso contrário.
     */
    public static function deletar($id) {
        foreach (self::$produtos as $key => $produto) {
            if ($produto['id'] == $id) {
                unset(self::$produtos[$key]);
                // Reindexar o array para manter a consistência
                self::$produtos = array_values(self::$produtos);
                return true;
            }
        }
        return false;
    }

    /**
     * Busca produtos por um termo no nome.
     * @param string $termo
     * @return array Retorna um array de produtos que correspondem ao termo.
     */
    public static function buscarPorNome($termo) {
        $resultados = [];
        foreach (self::$produtos as $produto) {
            // stristr é case-insensitive (não diferencia maiúsculas de minúsculas)
            if (stristr($produto['nome'], $termo)) {
                $resultados[] = $produto;
            }
        }
        return $resultados;
    }

    /**
     * Cria um novo produto.
     * @param array $dados Os dados do novo produto (nome, preco, etc.).
     * @return array O novo produto criado.
     */
    public static function criar($dados) {
        // Gera um novo ID para o produto
        $novoId = count(self::$produtos) > 0 ? max(array_column(self::$produtos, 'id')) + 1 : 1;

        $novoProduto = [
            "id" => $novoId,
            "nome" => $dados['nome'] ?? 'Nome do Produto',
            "preco" => $dados['preco'] ?? 0.0,
            "estoque" => $dados['estoque'] ?? 0,
            "imagem" => $dados['imagem'] ?? 'default.jpg'
        ];

        self::$produtos[] = $novoProduto;
        return $novoProduto;
    }

    /**
     * Atualiza um produto existente pelo ID.
     * @param int $id O ID do produto a ser atualizado.
     * @param array $dados Os novos dados do produto.
     * @return array|null O produto atualizado ou null se não for encontrado.
     */
    public static function atualizar($id, $dados) {
        foreach (self::$produtos as $key => $produto) {
            if ($produto['id'] == $id) {
                // Atualiza apenas os campos fornecidos
                self::$produtos[$key]['nome'] = $dados['nome'] ?? $produto['nome'];
                self::$produtos[$key]['preco'] = $dados['preco'] ?? $produto['preco'];
                self::$produtos[$key]['estoque'] = $dados['estoque'] ?? $produto['estoque'];
                self::$produtos[$key]['imagem'] = $dados['imagem'] ?? $produto['imagem'];
                
                return self::$produtos[$key];
            }
        }
        return null; // Retorna null se o produto não for encontrado
    }
}