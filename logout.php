<?php
session_start();
session_unset();  // Limpa todas as variáveis de sessão
session_destroy(); // Destroi a sessão completamente

// Garante que o navegador não armazene a página anterior
header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

// Redireciona para a página de login
header("Location: login.php");
exit;
