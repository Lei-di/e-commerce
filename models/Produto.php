<?php
require_once __DIR__ . '/../config/configBanco.php';

class Produto {

    // Lista todos os produtos com categoria e estoque
    public static function getAll() {
        global $conn;

        $sql = "SELECT 
                    p.id_produto AS id,
                    p.nome AS nome,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque,
                    p.imagem
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                ORDER BY p.nome";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca produto por ID
    public static function getById($id) {
        global $conn;

        $sql = "SELECT 
                    p.id_produto AS id,
                    p.nome AS nome,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque,
                    p.imagem
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                WHERE p.id_produto = :id
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca produtos por categoria
    public static function getByCategoria($categoria) {
        global $conn;

        if (strtolower($categoria) === 'novidades') {
            return self::getAll();
        }

        $sql = "SELECT 
                    p.id_produto AS id,
                    p.nome AS nome,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque,
                    p.imagem
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                WHERE LOWER(c.nome) = LOWER(:categoria)
                ORDER BY p.nome";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca produtos pelo termo no nome
    public static function buscarPorNome($termo) {
        global $conn;

        $sql = "SELECT 
                    p.id_produto AS id,
                    p.nome AS nome,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque,
                    p.imagem
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                WHERE p.nome LIKE :termo
                ORDER BY p.nome";

        $like = "%$termo%";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':termo', $like, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- NOVO MÉTODO PARA FILTROS COMBINADOS ---
    /**
     * Busca produtos com base em múltiplos filtros (categoria, preço min/max)
     * @param array $filtros Array associativo com chaves 'categoria', 'min', 'max'
     * @return array Lista de produtos filtrados
     */
    public static function getByFiltros($filtros = []) {
        global $conn;

        $sqlBase = "SELECT 
                        p.id_produto AS id,
                        p.nome AS nome,
                        c.nome AS categoria,
                        p.preco,
                        e.quantidade AS estoque,
                        p.imagem
                    FROM produtos p
                    JOIN categorias c ON p.id_categoria = c.id_categoria
                    JOIN estoque e ON p.id_produto = e.id_produto";
        
        $sqlWhere = " WHERE 1=1"; // Cláusula base
        $params = []; // Parâmetros para o PDO

        // Adiciona filtro de Categoria
        if (!empty($filtros['categoria']) && strtolower($filtros['categoria']) !== 'novidades') {
            $sqlWhere .= " AND c.nome = :categoria";
            $params[':categoria'] = $filtros['categoria'];
        }

        // Adiciona filtro de Preço Mínimo
        if (!empty($filtros['min'])) {
            $sqlWhere .= " AND p.preco >= :min";
            $params[':min'] = $filtros['min'];
        }

        // Adiciona filtro de Preço Máximo
        if (!empty($filtros['max'])) {
            $sqlWhere .= " AND p.preco <= :max";
            $params[':max'] = $filtros['max'];
        }

        $sqlOrder = " ORDER BY p.nome";

        // Prepara e executa a consulta final
        $stmt = $conn->prepare($sqlBase . $sqlWhere . $sqlOrder);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cria um novo produto
    public static function criar($dados) {
        global $conn;

        $sql = "INSERT INTO produtos (nome, preco, id_categoria, imagem)
                VALUES (:nome, :preco, :id_categoria, :imagem)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':preco', $dados['preco']);
        $stmt->bindParam(':id_categoria', $dados['id_categoria']);
        $stmt->bindParam(':imagem', $dados['imagem']);
        $stmt->execute();

        $id = $conn->lastInsertId();

        // Insere estoque inicial
        $sqlEstoque = "INSERT INTO estoque (id_produto, quantidade) VALUES (:id, :quantidade)";
        $stmtEstoque = $conn->prepare($sqlEstoque);
        $quantidade = $dados['estoque'] ?? 0;
        $stmtEstoque->bindParam(':id', $id);
        $stmtEstoque->bindParam(':quantidade', $quantidade);
        $stmtEstoque->execute();

        return self::getById($id);
    }

    // Atualiza produto existente
    public static function atualizar($id, $dados) {
        global $conn;

        $sql = "UPDATE produtos 
                SET nome = :nome, preco = :preco, id_categoria = :id_categoria, imagem = :imagem
                WHERE id_produto = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':preco', $dados['preco']);
        $stmt->bindParam(':id_categoria', $dados['id_categoria']);
        $stmt->bindParam(':imagem', $dados['imagem']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Atualiza estoque se fornecido
        if (isset($dados['estoque'])) {
            $sqlEstoque = "UPDATE estoque SET quantidade = :quantidade WHERE id_produto = :id";
            $stmtEstoque = $conn->prepare($sqlEstoque);
            $stmtEstoque->bindParam(':quantidade', $dados['estoque']);
            $stmtEstoque->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtEstoque->execute();
        }

        return self::getById($id);
    }

    // Deleta produto
    public static function deletar($id) {
        global $conn;

        // Primeiro deleta o estoque
        $sqlEstoque = "DELETE FROM estoque WHERE id_produto = :id";
        $stmtEstoque = $conn->prepare($sqlEstoque);
        $stmtEstoque->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtEstoque->execute();

        // Depois deleta o produto
        $sql = "DELETE FROM produtos WHERE id_produto = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Busca por faixa de preço (MANTIDO, mas não será usado pelo main.js)
    public static function getByFaixaDePreco($min, $max) {
        global $conn;

        $sql = "SELECT 
                    p.id_produto AS id,
                    p.nome AS nome,
                    c.nome AS categoria,
                    p.preco,
                    e.quantidade AS estoque,
                    p.imagem
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                JOIN estoque e ON p.id_produto = e.id_produto
                WHERE p.preco BETWEEN :min AND :max
                ORDER BY p.preco ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':min', $min, PDO::PARAM_INT);
        $stmt->bindParam(':max', $max, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // --- NOVO MÉTODO ADICIONADO ---
    // Busca todas as categorias para os filtros
    public static function getAllCategorias() {
        global $conn;

        $sql = "SELECT id_categoria, nome FROM categorias ORDER BY nome";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}