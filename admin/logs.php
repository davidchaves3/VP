<?php
include "../includes/db.php";
include "../includes/admin_auth.php";
include "../includes/admin_nav.php";

$stmt = $pdo->query("SELECT logs.*, usuarios.nome FROM logs JOIN usuarios ON logs.usuario_id = usuarios.id ORDER BY logs.data DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Registro de Logs</h2>
<ul>
    <?php foreach ($logs as $log): ?>
        <li><?= $log['data'] ?> - <?= $log['nome'] ?>: <?= $log['acao'] ?></li>
    <?php endforeach; ?>
</ul>
