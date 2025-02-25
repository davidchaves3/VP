<?php
$host = "localhost";
$dbname = "portal_db";
$user = "root"; // Usuário padrão do XAMPP
$password = ""; // Senha padrão (vazia)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    #echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
