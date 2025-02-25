<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Portal de Arquivos</title>
    <link rel="icon" href="img/icone.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Bem-vindo ao Portal de Arquivos</h2>
    <p><a href="login.php">Fazer Login</a></p>
</body>
</html>
