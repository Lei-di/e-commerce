<?php
// Script de teste para verificar se as categorias e produtos estão no banco
require_once 'config/configBanco.php';
require_once 'models/Produto.php';

echo "<h2>TESTE DE CATEGORIAS E PRODUTOS</h2>";

// 1. Verificar conexão com banco
echo "<h3>1. Conexão com Banco:</h3>";
try {
    $teste = $conn->query("SELECT 1");
    echo "✅ Conexão OK<br>";
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    exit;
}

// 2. Verificar se as tabelas existem
echo "<h3>2. Verificar Tabelas:</h3>";
$tabelas = ['categorias', 'produtos', 'estoque'];
foreach ($tabelas as $tabela) {
    try {
        $result = $conn->query("SELECT COUNT(*) as count FROM $tabela");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "✅ Tabela '$tabela': $count registros<br>";
    } catch (Exception $e) {
        echo "❌ Tabela '$tabela': " . $e->getMessage() . "<br>";
    }
}

// 3. Listar todas as categorias
echo "<h3>3. Categorias Cadastradas:</h3>";
try {
    $categorias = Produto::getAllCategorias();
    if (empty($categorias)) {
        echo "❌ Nenhuma categoria encontrada<br>";
    } else {
        echo "<ul>";
        foreach ($categorias as $cat) {
            echo "<li>ID: {$cat['id_categoria']} - Nome: {$cat['nome']}</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar categorias: " . $e->getMessage() . "<br>";
}

// 4. Testar filtros por categoria
echo "<h3>4. Testar Filtros por Categoria:</h3>";
$categorias_teste = ['feminino', 'masculino', 'acessorios', 'novidades'];

foreach ($categorias_teste as $cat) {
    try {
        echo "<h4>Categoria: $cat</h4>";
        $produtos = Produto::getByCategoria($cat);
        echo "Produtos encontrados: " . count($produtos) . "<br>";
        
        if (!empty($produtos)) {
            echo "<ul>";
            foreach (array_slice($produtos, 0, 3) as $produto) { // Mostrar apenas 3 primeiros
                echo "<li>{$produto['nome']} - R$ {$produto['preco']} ({$produto['categoria']})</li>";
            }
            echo "</ul>";
        }
        echo "<br>";
    } catch (Exception $e) {
        echo "❌ Erro ao buscar '$cat': " . $e->getMessage() . "<br><br>";
    }
}

// 5. Verificar estrutura das tabelas
echo "<h3>5. Estrutura das Tabelas:</h3>";
try {
    echo "<h4>Categorias:</h4>";
    $result = $conn->query("DESCRIBE categorias");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']}<br>";
    }
    
    echo "<h4>Produtos:</h4>";
    $result = $conn->query("DESCRIBE produtos");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']}<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar estrutura: " . $e->getMessage() . "<br>";
}

echo "<h3>✅ Teste Concluído!</h3>";
echo "<p><a href='/'>Voltar para a página principal</a></p>";
?>