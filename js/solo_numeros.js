
const telefonoInput = document.getElementById('telefono_paciente');

telefonoInput.addEventListener('keypress', function(event) {
    const keyCode = event.keyCode;
    if (keyCode < 48 || keyCode > 57) {
        event.preventDefault();
    }
});