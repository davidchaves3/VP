<?php
include "db.php"; // Conexão com o banco de dados

function registrarLog($usuario_id, $nome_usuario, $acao, $processo) {
    global $pdo;

    // Ajustando a verificação de duplicação para permitir mais registros ao longo do tempo
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM logs WHERE usuario_id = :usuario_id AND acao = :acao AND processos = :processo AND data >= NOW() - INTERVAL 30 SECOND");
    $stmt->execute([
        'usuario_id' => $usuario_id,
        'acao' => $acao,
        'processo' => $processo
    ]);
    $log_existente = $stmt->fetchColumn();

    if ($log_existente < 3) { 
        $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, nome_usuario, acao, data, processos) VALUES (:usuario_id, :nome_usuario, :acao, NOW(), :processo)");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'nome_usuario' => $nome_usuario,
            'acao' => $acao,
            'processo' => $processo
        ]);
    }
}
?>
