<?php 
session_start();
include "../includes/admin_auth.php";  // Garante que somente administradores possam acessar
include "../includes/db.php";

// Verifica se o ID do usuário a ser editado foi passado via GET
if (!isset($_GET['id'])) {
    die("ID do usuário não especificado!");
}

$id = $_GET['id'];

// Processa o formulário de atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $departamento = $_POST['departamento'];
    $mesas = $_POST['mesas'];
    $admin_status = isset($_POST['admin']) ? $_POST['admin'] : 0;

    if (!empty($_POST['senha'])) {
        $senha = $_POST['senha'];
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, departamento = :departamento, mesas = :mesas, admin = :admin WHERE id = :id");
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha_hash,
            'departamento' => $departamento,
            'mesas' => $mesas,
            'admin' => $admin_status,
            'id' => $id
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, departamento = :departamento, mesas = :mesas, admin = :admin WHERE id = :id");
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'departamento' => $departamento,
            'mesas' => $mesas,
            'admin' => $admin_status,
            'id' => $id
        ]);
    }

    // Log de atividade do administrador
    $admin_id = $_SESSION['usuario_id'];
    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, acao) VALUES (:admin_id, :acao)");
    $stmt->execute([
        'admin_id' => $admin_id,
        'acao' => "Editou o usuário com ID $id"
    ]);

    $mensagem = "Usuário atualizado com sucesso!";
}

// Processa a exclusão do usuário
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $delete_id]);

    header("Location: usuarios.php?deleted=true");
    exit;
}

// Recupera os dados do usuário para exibição no formulário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado!");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário - Administração</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/delete_modal.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
    <h1>Editar Usuário</h1>
    <?php if(isset($mensagem)) { echo "<p style='color: green; text-align:center;'>$mensagem</p>"; } ?>
    
    <form method="POST">
        <label>Nome Completo:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>
        
        <label>Departamento:</label><br>
        <input type="text" name="departamento" value="<?= htmlspecialchars($usuario['departamento']) ?>" required><br><br>
        
        <label>Mesas (separadas por vírgula):</label><br>
        <input type="text" name="mesas" value="<?= htmlspecialchars($usuario['mesas']) ?>"><br><br>
        
        <label>Administrador:</label><br>
        <input type="radio" name="admin" value="1" <?php if($usuario['admin'] == 1) echo "checked"; ?>> Sim
        <input type="radio" name="admin" value="0" <?php if($usuario['admin'] == 0) echo "checked"; ?>> Não
        <br><br>
        
        <label>Nova Senha (deixe em branco para manter a atual):</label><br>
        <input type="password" name="senha"><br><br>
        
        <button type="submit" class="btn">Atualizar Usuário</button>
        <button type="button" class="btn-delete-user" data-id="<?= $id ?>">Excluir Usuário</button>
    </form>

    <!-- Modal de Confirmação para Exclusão -->
    <div id="confirmModal" class="modal-delete">
        <div class="modal-delete-content">
            <span class="close-delete">&times;</span>
            <p>Tem certeza que deseja excluir este usuário?</p>
            <div class="modal-delete-actions">
                <button id="confirmDelete" class="btn-delete">Sim, Excluir</button>
                <button id="cancelDelete" class="btn-delete btn-cancel">Cancelar</button>
            </div>
        </div>
    </div>

    <p style="text-align:center;"><a href="usuarios.php">Voltar para a Lista de Usuários</a></p>
</body>
</html>
