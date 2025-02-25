<?php
function registrarLog($usuario_id, $acao) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, acao) VALUES (:usuario_id, :acao)");
    $stmt->execute(['usuario_id' => $usuario_id, 'acao' => $acao]);
}
?>
