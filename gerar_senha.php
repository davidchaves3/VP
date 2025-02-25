<?php
$senha = "123"; // Altere para a senha desejada
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Senha criptografada: " . $senha_hash;
?>

