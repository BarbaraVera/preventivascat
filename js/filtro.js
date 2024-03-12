// Llamar a la función mostrarEntradas
document.addEventListener("DOMContentLoaded", function() {
    // Llamar a la función mostrarEntradas al cargar la página
    mostrarEntradas();
});

function mostrarEntradas() {
    var opcionSeleccionada = document.getElementById("tipo").value;

    ocultarEntradas();

    // Mostrar el campo de entrada según la opción seleccionada
    if (opcionSeleccionada === "codigo1") {
        document.getElementById("paquete").closest('div').style.display = 'block';
    } else if (opcionSeleccionada === "codigo2") {
        document.getElementById("paquete").closest('div').style.display = 'none';
    } else if (opcionSeleccionada === "codigo3") {
        document.getElementById("paquete").closest('div').style.display = 'none';
    } else {
        // Mostrar el campo de entrada por defecto (busquedaCodigo)
        document.getElementById("selectPaquete").style.display = 'block';
    }
}

// Función para ocultar todas las entradas
function ocultarEntradas() {
    // Ocultar todos los campos de entrada
    document.getElementById("paquete").closest('div').style.display = 'none';
    // Ocultar el campo de entrada por defecto
    document.getElementById("selectPaquete").style.display = 'none';
}
