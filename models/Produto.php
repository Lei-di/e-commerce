<?php
require_once __DIR__ . '/../config/configBanco.php';
class Produto {
    public static function getAll() {
        global $conn;
        
        $sql = "SELECT 
                    p.id_produto,
                    p.nome AS nome,
                    p.imagem,
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // --- ALTERAÇÃO IMPORTANTE ---
            // Em vez de quebrar a página, vamos enviar um erro JSON detalhado
            // que poderemos ver nas ferramentas de desenvolvedor do navegador.
            http_response_code(500); // Internal Server Error
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'erro' => 'Falha na consulta SQL',
                'mensagem_original' => $e->getMessage()
            ]);
            exit; // Termina o script para garantir que nada mais seja enviado.
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
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao buscar produto: " . $e->getMessage());
        }
    }
    
    // As outras funções (deletar, buscarPorNome, criar, atualizar) permanecem iguais
    public static function deletar($id) {
        foreach (self::$produtos as $key => $produto) {
            if ($produto['id'] == $id) {
                unset(self::$produtos[$key]);
                self::$produtos = array_values(self::$produtos);
                return true;
            }
        }
        return false;
    }

    public static function buscarPorNome($termo) {
        $resultados = [];
        foreach (self::$produtos as $produto) {
            if (stristr($produto['nome'], $termo)) {
                $resultados[] = $produto;
            }
        }
        return $resultados;
    }

    public static function criar($dados) {
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

    public static function atualizar($id, $dados) {
        foreach (self::$produtos as $key => $produto) {
            if ($produto['id'] == $id) {
                self::$produtos[$key]['nome'] = $dados['nome'] ?? $produto['nome'];
                self::$produtos[$key]['preco'] = $dados['preco'] ?? $produto['preco'];
                self::$produtos[$key]['estoque'] = $dados['estoque'] ?? $produto['estoque'];
                self::$produtos[$key]['imagem'] = $dados['imagem'] ?? $produto['imagem'];
                return self::$produtos[$key];
            }
        }
        return null;
    }
}
