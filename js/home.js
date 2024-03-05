//Creado por Barbara Vera
document.addEventListener("DOMContentLoaded", function () {
    var nombreUsuarioSpan = document.getElementById("nombreUsuario");

    fetch("session.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.authenticated) {
                nombreUsuarioSpan.textContent = data.nombre;
            } else {
                window.location.href = "login.html";
            }
        })
        .catch((error) => {
            console.error(error);
        });


});
