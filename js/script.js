document.addEventListener("DOMContentLoaded", function () {
    console.log("Script carregado!");

    // Confirmação para excluir usuário
    const deleteButtons = document.querySelectorAll(".delete-user");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const userId = this.getAttribute("data-id");
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                window.location.href = "usuarios.php?delete=" + userId;
            }
        });
    });

    // Exibir logs em modal (se houver um modal na página)
    const logButtons = document.querySelectorAll(".view-log");
    logButtons.forEach(button => {
        button.addEventListener("click", function () {
            const logData = this.getAttribute("data-log");
            alert("Detalhes do Log: " + logData);
        });
    });
});
