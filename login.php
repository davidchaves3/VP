<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($senha, $usuario['senha'])) {
            // Guarda os dados do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['departamento'] = $usuario['departamento'];

            // Verifica se o usuário é administrador
            if (isset($usuario['admin']) && $usuario['admin'] == 1) {
                $_SESSION['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
            }

            header("Location: dashboard.php");
            exit;
        } else {
            $erro = "Email ou senha incorretos!";
        }
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal de Arquivos</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="img/icone.ico" type="image/x-icon">
</head>
<body>
    <img src="img/logo.png" alt="Logo" class="logo">
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($erro)) { echo "<p class='error'>$erro</p>"; } ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Digite seu email" required>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
