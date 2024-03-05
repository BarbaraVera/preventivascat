//Creado por Barbara Vera
document.addEventListener("DOMContentLoaded", function () {
    // Verificar si la sesión del usuario existe
    fetch("session.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.authenticated) {
                // El usuario está autenticado, puede continuar mostrando la página
            } else {
                // El usuario no está autenticado, redirigir a la página de inicio de sesión
                window.location.href = "home.html";
            }
        })
        .catch((error) => {
            console.error(error);
        });
});
