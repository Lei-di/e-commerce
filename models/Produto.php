<?php
require_once __DIR__ . '/../config/configBanco.php';
class Produto {
        public static function getAll() {
        global $conn;
         $sql = "SELECT 
                    p.id_produto,
                    p.nome AS produto,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                ORDER BY p.nome";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // retorna array 
        } catch (PDOException $e) {
            die("Erro ao buscar produtos: " . $e->getMessage());
        }
    }

    public static function getById($id) {
        global $conn;

        $sql = "SELECT 
                    p.id_produto,
                    p.nome AS produto,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                WHERE p.id_produto = :id
                LIMIT 1";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // retorna apenas 1 registro
        } catch (PDOException $e) {
            die("Erro ao buscar produto: " . $e->getMessage());
        }
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