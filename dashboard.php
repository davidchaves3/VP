<?php
include "config.php";
include "includes/log.php";

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
if (!isset($_SESSION['nome'])) {
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $usuario_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['nome'] = $user ? $user['nome'] : "Usuário Desconhecido";
}

$nome_usuario = $_SESSION['nome']; 
$departamento = $_SESSION['departamento'];

function listarArquivos($pasta, $processo_nome) {
    global $usuario_id, $nome_usuario;

    if (!is_dir($pasta)) {
        echo "<p>Nenhum documento disponível.</p>";
        return;
    }

    $arquivos = scandir($pasta);
    echo "<ul>";
    foreach ($arquivos as $arquivo) {
        if ($arquivo != "." && $arquivo != "..") {
            $caminho = $pasta . "/" . $arquivo;
            
            if (is_dir($caminho)) {
                echo "<li><details><summary>" . htmlspecialchars($arquivo) . "</summary>";
                listarArquivos($caminho, $processo_nome);
                echo "</details></li>";
            } else {
                // Garante que logs continuem sendo registrados ao longo do tempo
                if (!isset($_SESSION['logs'][$caminho]) || time() - $_SESSION['logs'][$caminho] > 30) {
                    registrarLog($usuario_id, $nome_usuario, "Visualizou o processo", $processo_nome);
                    $_SESSION['logs'][$caminho] = time(); // Marca o último registro
                }

                echo "<li>
                    <a href='#' class='file-link' data-file='" . htmlspecialchars($caminho) . "'>" . htmlspecialchars($arquivo) . "</a>
                    <a href='download.php?file=" . urlencode($caminho) . "'>Download</a>
                </li>";
            }
        }
    }
    echo "</ul>";
}
function listarProcessos($pasta) {
    global $usuario_id, $nome_usuario;

    if (!is_dir($pasta)) {
        echo "<p>Nenhum processo disponível.</p>";
        return;
    }

    $processos = scandir($pasta);
    echo "<ul>";
    foreach ($processos as $processo) {
        if ($processo != "." && $processo != "..") {
            $caminhoProcesso = $pasta . "/" . $processo;
            
            if (is_dir($caminhoProcesso)) {
                // Apenas registra log se ainda não foi registrado nessa sessão
                if (!isset($_SESSION['logs'][$caminhoProcesso])) {
                    registrarLog($usuario_id, $nome_usuario, "Acessou o processo", $processo);
                    $_SESSION['logs'][$caminhoProcesso] = true;
                }

                echo "<li><details><summary>" . htmlspecialchars($processo) . "</summary>";
                listarArquivos($caminhoProcesso, $processo);
                echo "</details></li>";
            }
        }
    }
    echo "</ul>";
}


// Recupera as mesas atribuídas ao usuário
$usuario_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT mesas FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuario_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Processos por Mesa</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" href="img/icone.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/modal.css">
</head>
<body>
    <h1>Bem-vindo ao Portal de Arquivos</h1>
    <!-- Menu de Navegação -->
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <li><a href="admin/usuarios.php">Administração</a></li>
                <li><a href="admin/logs.php">Logs de Atividades</a></li>
            <?php endif; ?>
            <li><a href="login.php">Sair</a></li>
        </ul>
    </nav>
    
    <!-- Seção para Processos por Mesa -->
    <section>
    <h2>Processos Atribuídos</h2>
        <?php
            if ($user && !empty($user['mesas'])) {
            // Assume que o campo 'mesas' armazena uma lista separada por vírgulas
                    $mesasArray = array_map('trim', explode(',', $user['mesas']));
                    foreach ($mesasArray as $mesa) {
                        // Exibe um cabeçalho para cada mesa
                        echo "<h3>Mesa: " . htmlspecialchars($mesa) . "</h3>";
                        // Define o caminho: uploads/{mesa}
                        $pastaMesa = "uploads/" . $mesa;
                        listarProcessos($pastaMesa);
            }
            } else {
                     echo "<p>Nenhuma mesa configurada para o usuário.</p>";
            }
        ?>
    </section>
    <!-- Modal para Visualizar Arquivos -->
<div id="fileModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <iframe id="fileViewer" src="" frameborder="0"></iframe>
    <div class="modal-actions">
      <button id="prevBtn" class="btn-nav">← Documento Anterior</button>
      <a id="downloadBtn" class="btn-download" href="#" download>Download</a>
      <button id="nextBtn" class="btn-nav">Próximo Documento →</button>
    </div>
  </div>
</div>
<script src="js/modal.js"></script>
</body>
</html>
