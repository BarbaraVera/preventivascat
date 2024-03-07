document.getElementById("agregar").addEventListener("click", function() {
    
    var rut = document.getElementById("rut_paciente").value;
    var nombre = document.getElementById("nombre_paciente").value;
    var telefono = document.getElementById("telefono_paciente").value;
    var preventiva = document.getElementById("preventiva_paciente").value;

    
    if (rut && nombre && telefono && preventiva) {
        
        var fila = "<tr><td>" + rut + "</td><td>" + nombre + "</td><td>" + telefono + "</td><td>" + preventiva + "</td></tr>";

        document.getElementById("tablaBody").innerHTML += fila;

        document.getElementById("rut_paciente").value = "";
        document.getElementById("nombre_paciente").value = "";
        document.getElementById("telefono_paciente").value = "";
        document.getElementById("preventiva_paciente").value = "";

        document.getElementById("enviar").disabled = false;
        document.getElementById("agregar").disabled = true;

        var alertaContainer = document.getElementById("alert-container");
        if (alertaContainer.classList.contains("show")) {
            alertaContainer.classList.remove("show");
        }
    } else {
        var alertaHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Por favor complete todos los campos antes de agregar a la tabla.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.getElementById("alert-container").innerHTML = alertaHTML;
    }
});
