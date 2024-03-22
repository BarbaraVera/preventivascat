//Creado por Barbara Vera
document.addEventListener("DOMContentLoaded", function () {
    var nombreUsuarioSpan = document.getElementById("nombreUsuario");

    fetch("session.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.authenticated) {
                nombreUsuarioSpan.textContent = data.nombre;
                
                if (data.mutual) {
                    document.getElementById("solicitudButton").onclick = function() {
                        window.location.href = "formulario_mutual.html";
                    };
                } else {
                    document.getElementById("solicitudButton").onclick = function() {
                        window.location.href = "formulario.html";
                    };
                }
                
            } 
        })
        .catch((error) => {
            console.error(error);
        });
});
