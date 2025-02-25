<?php
// includes/admin_auth.php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Se o usuário não for admin, redireciona para a página de login
    header("Location: ../login.php");
    exit;
}
?>
