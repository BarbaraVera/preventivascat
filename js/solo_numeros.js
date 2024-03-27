function soloNumeros(event){
    const keyCode = event.keyCode;
    if (keyCode < 48 || keyCode > 57) {
        event.preventDefault();
    }
}

function maximoNumeros(valor,maxLength) {
    if (valor.value.length > maxLength) {
        valor.value = valor.value.slice(0, maxLength);
    }
}

let maxCaracteres = 160;
let caracteresRestantesElement = document.getElementById('caracteres_restantes');
function actualizarCaracteresRestantes(textareaMensaje) {
    var cantidadCaracteres = textareaMensaje.value.length;
    var caracteresRestantes = maxCaracteres - cantidadCaracteres;
    caracteresRestantesElement.textContent = caracteresRestantes;
}
