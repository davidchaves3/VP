<?php
include "db.php"; // Conexão com o banco de dados

function registrarLog($usuario_id, $nome_usuario, $acao, $processo) {
    global $pdo;

    // Obtém o nome correto do processo (segundo nível acima do arquivo)
    $partes = explode("/", $processo);
    $total_partes = count($partes);
    
    if ($total_partes >= 3) {
        $processo_nome = $partes[$total_partes - 2]; 
    } else {
        $processo_nome = "Processo Desconhecido"; 
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM logs WHERE usuario_id = :usuario_id AND acao = :acao AND processos = :processo AND data >= NOW() - INTERVAL 5 SECOND");
    $stmt->execute([
        'usuario_id' => $usuario_id,
        'acao' => $acao,
        'processo' => $processo_nome
    ]);
    $log_existente = $stmt->fetchColumn();

    if ($log_existente == 0) {
        $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, nome_usuario, acao, data, processos) VALUES (:usuario_id, :nome_usuario, :acao, NOW(), :processo)");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'nome_usuario' => $nome_usuario,
            'acao' => $acao,
            'processo' => $processo_nome
        ]);
    }
}
?>

