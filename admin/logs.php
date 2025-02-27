<?php
include "../includes/db.php";
include "../includes/admin_auth.php";
include "../includes/admin_nav.php";

$stmt = $pdo->query("SELECT logs.*, usuarios.nome FROM logs JOIN usuarios ON logs.usuario_id = usuarios.id ORDER BY logs.data DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Data/Hora</th>
        <th>Usuário</th>
        <th>Ação</th>
        <th>Nome do Processo</th>
    </tr>
    <?php foreach($logs as $log): ?>
    <tr>
        <td><?= htmlspecialchars($log['data']) ?></td>
        <td><?= htmlspecialchars($log['nome_usuario']) ?></td>
        <td><?= htmlspecialchars($log['acao']) ?></td>
        <td><?= htmlspecialchars($log['processos']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
