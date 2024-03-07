document.getElementById("idForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var tabla = document.getElementById("tablaBody");
    var filas = tabla.getElementsByTagName("tr");
    var datos = [];

    for (var i = 0; i < filas.length; i++) {
        var fila = filas[i];
        var celdas = fila.getElementsByTagName("td");
        var filaDatos = {};

        filaDatos.rut = celdas[0].textContent;
        filaDatos.nombre = celdas[1].textContent;
        filaDatos.telefono = celdas[2].textContent;
        filaDatos.preventiva = celdas[3].textContent;

        datos.push(filaDatos);
    }

    fetch('http://localhost/preventivascat/formulario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos),
    })
    .then(response => {
        console.log("este es el response",response);
        if (!response.ok) {
            throw new Error('Hubo un problema con la solicitud: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log("la data",data);
        if (data.exito) {
            console.log(data.mensaje);
            mostrarMensajeExito(data.mensaje);
            document.getElementById("rut_paciente").value = "";
            document.getElementById("nombre_paciente").value = "";
            document.getElementById("telefono_paciente").value = "";
            document.getElementById("preventiva_paciente").value = "";
            tabla.innerHTML = ""; 
            
        } else {
            console.error('Error al insertar registro:', data.errores);
            mostrarMensajeError('Hubo un problema al insertar registros.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        mostrarMensajeError('Hubo un problema al procesar la solicitud.');
    });
    console.log("termino");
});

function mostrarMensajeExito(mensaje) {
    var alertContainer = document.getElementById("alert-container");
    var alertHTML = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    alertContainer.innerHTML = alertHTML;
    document.getElementById("enviar").disabled = true;
}

function mostrarMensajeError(mensaje) {
    var alertContainer = document.getElementById("alert-container");
    var alertHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    alertContainer.innerHTML = alertHTML;
}