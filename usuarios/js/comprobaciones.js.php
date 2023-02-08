<?php
/**
 * Este fichero contiene las comprobaciones de los formularios 
 */

// Le decimos a php que esto es un archivo que contiene javascript
header("Content-type: application/javascript");

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT']. '/includes/diccionario.inc.php';

?>
let formulario = document.getElementsByClassName('formularioAcomprobar')[0];

// Función para volver arriba cuando se muestre un error
function irArriba() {
    var principalDiv = document.getElementsByClassName("principal")[0];
    principalDiv.scrollTop = 0;
}

/**
 * Funcion que valida la contraseña
 */
function validar_pass(contrasenna){	
    var mayuscula = false;
    var minuscula = false;
    var numero = false;
    var caracter_raro = false;
    
    //Recorremos todas las letras de la contraseña y hacemos comprobaciones
    for(var i = 0; i < contrasenna.length; i++){
        //si la contraseña tiene mayuscula se le asigna un true
        if(contrasenna.charCodeAt(i) >= 65 && contrasenna.charCodeAt(i) <= 90){
            mayuscula = true;

        //Si la contraseña tiene minusculas se le asigna un true
        }else if(contrasenna.charCodeAt(i) >= 97 && contrasenna.charCodeAt(i) <= 122){
            minuscula = true;

        //Si la contraseña tiene un numero se le asigna un true
        }else if(contrasenna.charCodeAt(i) >= 48 && contrasenna.charCodeAt(i) <= 57){
            numero = true;
        //Si la contraseña contiene un caracter especial
        }else{
            caracter_raro = true;
        }
    }
    //Si todas las variables conbienen un true se devuelve un true 
    if(mayuscula == true && minuscula == true && caracter_raro == true && numero == true){
        return true;
    }

    return false;
}


/**
 * Funcion que valida el numero de telefono
 */
function validar_tlf(telefono){
    //Recorremos todos los caracteres del numero de telefono
    for(var i = 0; i < telefono.length; i++){
        //Si contiene algun caracter que no sea numero se devuelve un false
        if(!(telefono.charCodeAt(i) >= 48 && telefono.charCodeAt(i) <= 57)){
            return false;
        }
    }
    return true;
}


/**
 * Funcion que valida el email
 */
function validar_email( email ){

    re=/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/
    //comprobamos que el email comple con los requisitos
    if(!re.exec(email)){
		return true;
	}else{
	    return false;
	}
}


/**
 * Capturamos el envio del formulario y vemos si se puede enviar o no
 */
formulario.addEventListener('submit', (e) => {
    let password = document.getElementsByClassName('passwordComprobar')[0];
    let rePassword = document.getElementsByClassName('rePasswordComprobar')[0];

    //comprobamos que las contraseñas son iguales
    if(password && rePassword){
        //Comprobamos si las contraseñas son iguales
        if (password.value != rePassword.value) {
            let mensaje = document.getElementsByClassName('error')[0];
            mensaje.innerHTML = "<?php echo $PASSWORDS_DIFERENTES?>";
            mensaje.style.display = "block";
            irArriba();
            e.preventDefault();
        }

        //Comprobamos si la contraseña es valida
        if(!validar_pass(password.value)){
            let mensaje = document.getElementsByClassName('error')[0];
            mensaje.innerHTML = "<?php echo $PASSWORD_NO_CUMPLE_REQUISITOS?>";
            mensaje.style.display = "block";
            irArriba();
            e.preventDefault();
        }
    }
    

    let telefono = document.getElementsByClassName('telefonoComprobar')[0];

    //Comprobamos si el telefono es valido
    if(telefono){
        //Si el telefono contiene menois de nueve caracteres...
        if(telefono.value.length < 9){
            let mensaje = document.getElementsByClassName('error')[0];
            mensaje.innerHTML = "<?php echo $TLFN_NO_ADMITIDO?>";
            mensaje.style.display = "block";
            irArriba();
            e.preventDefault();
        }else{
            //Comprobamos que los caracteres del telefono son correctos
            if(!validar_tlf(telefono.value)){
                let mensaje = document.getElementsByClassName('error')[0];
                mensaje.innerHTML = "<?php echo $TLFN_DEBER_SER_NUMERO?>";
                mensaje.style.display = "block";
                irArriba();
                e.preventDefault();
            }
        }
    }

    let email = document.getElementsByClassName('emailComprobar')[0];
    //comprobamos el email
    if(email){
        //validamos el email
        if(validar_email(email.value)){
            let mensaje = document.getElementsByClassName('error')[0];
            mensaje.innerHTML = "<?php echo $EMAIL_INCORRECTO?>";
            mensaje.style.display = "block";
            irArriba();
            e.preventDefault();
        }
    }

});
