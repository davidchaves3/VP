<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

// Recupera o departamento do usuário (por exemplo, 'ti')
$departamento = $_SESSION['departamento'];

/**
 * Função para listar arquivos (documentos) de forma recursiva.
 * Para cada arquivo, cria um link para visualização.
 */
function listarArquivos($pasta) {
    if (!is_dir($pasta)) {
        echo "<p>Nenhum documento disponível em <em>" . htmlspecialchars($pasta) . "</em>.</p>";
        return;
    }
    
    $itens = scandir($pasta);
    echo "<ul>";
    foreach ($itens as $item) {
        if ($item != "." && $item != "..") {
            $caminho = $pasta . "/" . $item;
            if (is_dir($caminho)) {
                echo "<li><details><summary>" . htmlspecialchars($item) . "</summary>";
                listarArquivos($caminho);
                echo "</details></li>";
            } else {
                // Adicionando a classe .file-link e o atributo data-file
                echo "<li><a href='#' class='file-link' data-file='" . htmlspecialchars($caminho) . "'>" . htmlspecialchars($item) . "</a></li>";
            }
        }
    }
    echo "</ul>";
}
/**
 * Função para listar os processos (que são pastas) de uma determinada mesa.
 * Cada processo é exibido como um elemento <details> que, ao ser clicado,
 * expande para mostrar os documentos contidos nele.
 */
function listarProcessos($pasta) {
    if (!is_dir($pasta)) {
        echo "<p>Nenhum processo disponível em <em>" . htmlspecialchars($pasta) . "</em>.</p>";
        return;
    }
    
    $processos = scandir($pasta);
    echo "<ul>";
    foreach ($processos as $processo) {
        if ($processo != "." && $processo != "..") {
            $caminhoProcesso = $pasta . "/" . $processo;
            if (is_dir($caminhoProcesso)) {
                echo "<li><details><summary>" . htmlspecialchars($processo) . "</summary>";
                listarArquivos($caminhoProcesso);
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

    <!-- Área do PDF -->
    <iframe id="fileViewer" src="" frameborder="0"></iframe>

    <!-- Área de Ações no Rodapé -->
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
