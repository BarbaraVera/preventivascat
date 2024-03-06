document.getElementById("agregar").addEventListener("click", function() {
    var rut = document.getElementById("rut_paciente").value;
    var nombre = document.getElementById("nombre_paciente").value;
    var telefono = document.getElementById("telefono_paciente").value;
    var preventiva = document.getElementById("preventiva_paciente").value;

    var fila = "<tr><td>" + rut + "</td><td>" + nombre + "</td><td>" + telefono + "</td><td>" + preventiva + "</td></tr>";

    document.getElementById("tablaBody").innerHTML += fila;

    // Limpiar los campos 
    document.getElementById("rut_paciente").value = "";
    document.getElementById("nombre_paciente").value = "";
    document.getElementById("telefono_paciente").value = "";
    document.getElementById("preventiva_paciente").value = "";

    document.getElementById("enviar").disabled = false;
});