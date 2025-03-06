<?php
include "db.php"; // Conexão com o banco de dados

function registrarLog($usuario_id, $nome_usuario, $acao, $caminho) {
    global $pdo;

    // Extrai corretamente o nome da mesa, situação do processo e nome do processo
    $partes = explode("/", str_replace("\\", "/", $caminho)); 
    $total_partes = count($partes);

    if ($total_partes >= 4) {
        $nome_mesa = $partes[1]; // Nome da mesa
        $situacao_processo = $partes[2]; // Aberto ou Arquivado
        $nome_processo = $partes[3]; // Nome do processo real
    } else {
        $nome_mesa = "Desconhecido";
        $situacao_processo = "Desconhecido";
        $nome_processo = "Desconhecido";
    }

    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, nome_usuario, acao, data, processos, caminho) VALUES (:usuario_id, :nome_usuario, :acao, NOW(), :processo, :caminho)");
    $stmt->execute([
        'usuario_id' => $usuario_id,
        'nome_usuario' => $nome_usuario,
        'acao' => $acao,
        'processo' => $nome_processo,
        'caminho' => $caminho
    ]);
  }
?>
