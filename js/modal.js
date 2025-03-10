document.addEventListener("DOMContentLoaded", function() {
    console.log("Modal.js carregado!");

    var modal = document.getElementById("fileModal");
    var fileViewer = document.getElementById("fileViewer");
    var downloadBtn = document.getElementById("downloadBtn");
    var closeBtn = document.querySelector(".modal .close");

    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");

    var fileLinks = document.querySelectorAll(".file-link");
    var fileArray = [];
    var currentIndex = 0;

    // Certifica que o modal está oculto ao carregar a página
    modal.style.display = "none";

    // Preenche a lista de arquivos disponíveis
    fileLinks.forEach(function(link) {
        fileArray.push(link.getAttribute("data-file"));
    });

    // Adiciona evento de clique nos documentos
    fileLinks.forEach(function(link, index) {
        link.addEventListener("click", function(e) {
            e.preventDefault();

            var filePath = this.getAttribute("data-file");
            currentIndex = index;

            console.log("Abrindo modal para o arquivo:", filePath);

            if (filePath) {
                fileViewer.src = filePath + "#toolbar=0&navpanes=0&scrollbar=0";
                downloadBtn.href = filePath;
                modal.style.display = "flex"; 
            }
            fetch('includes/log.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `acao=Visualizou o arquivo&caminho=${encodeURIComponent(filePath)}`
            });
        });
    });

    // Função para navegar entre arquivos
    function navigateFile(direction) {
        if (direction === "next" && currentIndex < fileArray.length - 1) {
            currentIndex++;
        } else if (direction === "prev" && currentIndex > 0) {
            currentIndex--;
        } else {
            return;
        }

        var newFilePath = fileArray[currentIndex];
        console.log("Navegando para:", newFilePath);

        fileViewer.src = newFilePath + "#toolbar=0&navpanes=0&scrollbar=0";
        downloadBtn.href = newFilePath;
    }

    prevBtn.addEventListener("click", function() {
        navigateFile("prev");
    });

    nextBtn.addEventListener("click", function() {
        navigateFile("next");
    });

    // Fecha o modal ao clicar no botão de fechar (X)
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
        fileViewer.src = "";
    });

    // Fecha o modal ao clicar fora dele
    window.addEventListener("click", function(e) {
        if (e.target == modal) {
            modal.style.display = "none";
            fileViewer.src = "";
        }
    });
});
