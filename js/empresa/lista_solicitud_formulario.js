
fetch('http://localhost/preventivascat/lista_solicitud_formulario.php')
.then(response => response.json())
.then(data => {
    
    let tablaHTML = '<table class="table table-striped" style="margin-top:0.5%;">' +
                        '<tr>' +
                            '<th>RUT</th>' +
                            '<th>Nombre del Solicitante</th>' +
                            '<th>Teléfono</th>' +
                            '<th>Estado</th>' +
                            '<th>Fecha de Ingreso</th>' +
                        '</tr>';
    
    data.forEach(solicitud => {
        tablaHTML += '<tr>' +
                        '<td>' + solicitud.rut + '</td>' +
                        '<td>' + solicitud.nombre_solicitante + '</td>' +
                        '<td>' + solicitud.telefono + '</td>' +
                        '<td>' + solicitud.estado + '</td>' +
                        '<td>' + solicitud.fecha_ingreso + '</td>' +
                    '</tr>';
    });

    tablaHTML += '</table>';

    document.getElementById('tabla-container').innerHTML = tablaHTML;
})
.catch(error => {
    console.error('Error al obtener los datos:', error);
});
