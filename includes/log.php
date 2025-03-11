<?php
include __DIR__ . "/../config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function registrarLog($usuario_id, $nome_usuario, $acao, $processos = '', $caminho = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, nome_usuario, acao, processos, caminho) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $nome_usuario, $acao, $processos, $caminho]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id   = $_SESSION['usuario_id'];
    $nome_usuario = $_SESSION['nome'];
    $acao         = $_POST['acao'] ?? 'Ação não especificada';
    $caminho      = $_POST['caminho'] ?? '';
    $processos    = $_POST['processo'] ?? 'Desconhecido';

    registrarLog($usuario_id, $nome_usuario, $acao, $processos, $caminho);
    echo "Registrado com sucesso";
}
