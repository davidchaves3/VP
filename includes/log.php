<?php
include __DIR__ . "/../config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    file_put_contents("log_teste.txt", print_r($_POST, true), FILE_APPEND);
    // Verifica se os campos necessários estão sendo enviados
    if (isset($_SESSION['usuario_id'], $_SESSION['nome_usuario'], $_POST['acao'], $_POST['caminho'])) {
        $usuario_id = $_SESSION['usuario_id'];
        $nome_usuario = $_SESSION['nome_usuario'];
        $acao = $_POST['acao'];
        $caminho = $_POST['caminho'];
        
        // Extrai o nome do processo corretamente
        $partesCaminho = explode('/', $caminho);
        $processos = isset($partesCaminho[3]) ? $partesCaminho[3] : 'N/D';

        // Prepara a query corretamente com os campos da sua tabela logs
        $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, nome_usuario, acao, processos, caminho) VALUES (:usuario_id, :nome_usuario, :acao, :processos, :caminho)");

        // Executa a query com segurança usando prepared statements
        $stmt->execute([
            ':usuario_id'   => $usuario_id,
            ':nome_usuario' => $nome_usuario,
            ':acao'         => $acao,
            ':processos'    => $processos,
            ':caminho'      => $caminho
        ]);
    }
}
?>
