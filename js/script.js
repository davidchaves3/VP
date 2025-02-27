document.addEventListener("DOMContentLoaded", function () {
    console.log("Script.js carregado!");

    // Seleciona o modal e os botões
    const deleteButton = document.querySelector(".btn-delete-user");
    const modal = document.getElementById("confirmModal");
    const confirmBtn = document.getElementById("confirmDelete");
    const cancelBtn = document.getElementById("cancelDelete");
    const closeBtn = document.querySelector(".close-delete");

    let userIdToDelete = null;

    // Garante que o modal NÃO apareça ao recarregar a página
    modal.style.display = "none";

    // Apenas adiciona evento ao botão "Excluir Usuário"
    if (deleteButton) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault(); // Impede comportamento inesperado
            userIdToDelete = this.getAttribute("data-id");
            modal.style.display = "flex"; // Exibe o modal apenas quando necessário
        });
    }

    confirmBtn.onclick = function () {
        if (userIdToDelete) {
            window.location.href = "editar_usuario.php?delete=" + userIdToDelete;
        }
    };

    cancelBtn.onclick = function () {
        modal.style.display = "none";
    };

    closeBtn.onclick = function () {
        modal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
