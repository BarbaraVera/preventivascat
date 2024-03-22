//Creado por Barbara Vera
document.addEventListener("DOMContentLoaded", function () {
    // Verificar si la sesión del usuario existe
    fetch("session.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.authenticated) {
                
                if (!data.mutual) {
                    window.location.href = "formulario.html";
                }
            } else {
                
                window.location.href = "login.html";
            }
        })
        .catch((error) => {
            console.error(error);
        });
});
