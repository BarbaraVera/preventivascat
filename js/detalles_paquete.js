var selectPaquete = document.getElementById("paquete");
var botonVerDetalles = document.getElementById("verDetalles");

selectPaquete.addEventListener("change", function() {
    if (selectPaquete.value !== "") {
        botonVerDetalles.removeAttribute("disabled");
    } else {
        botonVerDetalles.setAttribute("disabled", "disabled");
    }
});


document.getElementById("verDetalles").onclick = function() {

    fetch('detalles_paquete.php?codigo=' + selectPaquete.value)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        document.getElementById("detallesPaqueteTable").querySelectorAll('tbody tr').forEach(row => row.remove());

        var tableBody = document.getElementById("detallesPaqueteTable").getElementsByTagName('tbody')[0];

        data.forEach(detalle => {
            var newRow = tableBody.insertRow();
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            cell1.textContent = detalle.cod_prestacion;
            cell2.textContent = detalle.prestacion;
        });

        var detallesPaqueteModal = new bootstrap.Modal(document.getElementById('detallesPaqueteModal'));
        detallesPaqueteModal.show();
    })
    .catch(error => console.error('Error al obtener los detalles del paquete:', error));
};
