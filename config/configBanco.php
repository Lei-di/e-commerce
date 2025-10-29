<?php
// configBanco.php
// Ajuste estas variáveis conforme seu ambiente local (XAMPP/WAMP):
$host = "localhost";
$usuario = "root";            // root -> usuário padrão do phpMyAdmin
$senha = "";                  // senha padrão (no XAMPP normalmente é vazia)
$banco = "ecommerce_elegance"; // ecommerce_elegance -> nome do banco de dados criado

try {
    // Cria conexão PDO com charset utf8mb4
    $conn = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão realizada com sucesso!";
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>
