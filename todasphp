<?php
require_once __DIR__ . '/../configBanco.php'; // ajuste o caminho conforme a estrutura

// Exemplo de uso:
$stmt = $conn->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($produtos as $p) {
    echo $p['nome'] . " - R$ " . $p['preco'] . "<br>";
}
?>
