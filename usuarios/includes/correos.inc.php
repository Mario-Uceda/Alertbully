<?php
/**
 * En este fichero tenemos todos los correos que se mandan automáticamente desde la web
 */

/**
 * Función para mandar el correo con la URL de restablecimiento de contraseña
 */
function correoRestablecimiento($url, $email) {
    $from = "soporte@alertbully.es";
    $to = $email;
    $subject = "Solicitud de restablecimiento de contraseña";
    $message = '
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="color: black; font-family: \'Source Sans Pro\', sans-serif; font-size: 1.5em;">
        <div style="
                background-color: #F5F5F6;
                border: 1px solid #801313;
                border-radius: 64px;
                bottom: 0;
                height: fit-content;
                left: 0;
                margin: auto 3%;
                padding: 3% 1%;
                position: absolute;
                right: 0;
                text-align: center;
                top: 3.5%;
                width: fit-content;
                max-height: fit-content;
            ">
            <h1 style="
                color: #a73f2d;
                font-weight: bold;
                font-size: 2.5em;
                margin: 0;
                text-decoration: underline;
            ">AlertBully</h1>
            <p>Hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta de AlertBully.</p>
            <p>En caso de que no hayas realizado la solicitud, por favor, ignora este correo electrónico.</p>
            <p>
                Para realizar el restablecimiento puedes hacerlo desde el siguiente <a style="color: #a73f2d;
                    text-decoration: none;" href="' . $url . '">link</a>.
            </p>
            <p>El equipo de AlertBully.</p>
        </div>
    </body>

    </html>
    ';

    $headers = "From: Alertbully <" . $from . ">\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-type: text/html\r\n";

    // Si el correo se manda correctamente devolvemos true
    if (mail($to, $subject, $message, $headers)) {
        return true;
        // Si no, devolvemos false...
    } else {
        return false;
    }
}

 /**
 * Función para mandar el correo con la URL de restablecimiento de contraseña
 */
function correoPasswordCambiada($email) {
    // Guardamos en la variable $server el dominio del servidor
    $server = $_SERVER['SERVER_NAME'];
    // Creamos la URL con la ubicación del formulario para restaurar la contraseña
    $urlRestauracion = $server . "/recuperacion";
    $from = "soporte@alertbully.es";
    $to = $email;
    $subject = "Cambio de contraseña realizado";
    $message = '
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="color: black; font-family: \'Source Sans Pro\', sans-serif; font-size: 1.5em;">
        <div style="
                background-color: #F5F5F6;
                border: 1px solid #801313;
                border-radius: 64px;
                bottom: 0;
                height: fit-content;
                left: 0;
                margin: auto 3%;
                padding: 3% 1%;
                position: absolute;
                right: 0;
                text-align: center;
                top: 3.5%;
                width: fit-content;
                max-height: fit-content;
            ">
            <h1 style="
                color: #a73f2d;
                font-weight: bold;
                font-size: 2.5em;
                margin: 0;
                text-decoration: underline;
            ">AlertBully</h1>
            <p>Te informamos de que tu contraseña de AlertBully ha sido cambiada.</p>
            <p>
                En caso de que no hayas realizado la modificación, por favor, intenta 
                <a style="color: #a73f2d; text-decoration: none;" href="' . $urlRestauracion . '">restaurarla</a>.
            </p>
            <p>Si no puedas restaurarla ponte en contacto con nuestro soporte o con el administrador.</p>
            <p>El equipo de AlertBully.</p>
        </div>
    </body>

    </html>
    ';

    $headers = "From: Alertbully <" . $from . ">\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-type: text/html\r\n";

    // Si el correo se manda correctamente devolvemos true
    if (mail($to, $subject, $message, $headers)) {
        return true;
        // Si no, devolvemos false...
    } else {
        return false;
    }
}


/**
 * Función para mandar el correo con la URL de restablecimiento de contraseña
 */
function correoRegistro($email, $password) {
    $from = "soporte@alertbully.es";
    $to = $email;
    $subject = "Has sido dado de alta";
    $message = '
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="color: black; font-family: \'Source Sans Pro\', sans-serif; font-size: 1.5em;">
        <div style="
                background-color: #F5F5F6;
                border: 1px solid #801313;
                border-radius: 64px;
                bottom: 0;
                height: fit-content;
                left: 0;
                margin: auto 3%;
                padding: 3% 1%;
                position: absolute;
                right: 0;
                text-align: center;
                top: 3.5%;
                width: fit-content;
                max-height: fit-content;
            ">
            <h1 style="
                color: #a73f2d;
                font-weight: bold;
                font-size: 2.5em;
                margin: 0;
                text-decoration: underline;
            ">AlertBully</h1>
            <p>Has sido dado de alta en el servicio de AlertBully.</p>
            <p>Los credenciales para entrar son los siguientes:</p>
            <p>·Email: ' . $email . '</p>
            <p>·Contraseña: ' . $password . '</p>
            <p>
                Por favor, recuerda cambiar la contraseña al iniciar sesión por primera vez.
            </p>
            <p>El equipo de AlertBully.</p>
        </div>
    </body>

    </html>
    ';

    $headers = "From: Alertbully <" . $from . ">\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-type: text/html\r\n";

    // Si el correo se manda correctamente devolvemos true
    if (mail($to, $subject, $message, $headers)) {
        return true;
        // Si no, devolvemos false...
    } else {
        return false;
    }
}

/**
 * Función para mandarle la nueva contraseña a un usuario
 */
function correoNuevaPass($email, $password) {
    $from = "soporte@alertbully.es";
    $to = $email;
    $subject = "Tu contraseña ha sido restablecida";
    $message = '
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="color: black; font-family: \'Source Sans Pro\', sans-serif; font-size: 1.5em;">
        <div style="
                background-color: #F5F5F6;
                border: 1px solid #801313;
                border-radius: 64px;
                bottom: 0;
                height: fit-content;
                left: 0;
                margin: auto 3%;
                padding: 3% 1%;
                position: absolute;
                right: 0;
                text-align: center;
                top: 3.5%;
                width: fit-content;
                max-height: fit-content;
            ">
            <h1 style="
                color: #a73f2d;
                font-weight: bold;
                font-size: 2.5em;
                margin: 0;
                text-decoration: underline;
            ">AlertBully</h1>
            <p>Tu contraseña de AlertBully ha sido restablecida por parte del soporte.</p>
            <p>Los credenciales para entrar son los siguientes:</p>
            <p>·Email: ' . $email . '</p>
            <p>·Contraseña: ' . $password . '</p>
            <p>
                Por favor, recuerda cambiar la contraseña al iniciar sesión por primera vez.
            </p>
            <p>El equipo de AlertBully.</p>
        </div>
    </body>

    </html>
    ';

    $headers = "From: Alertbully <" . $from . ">\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-type: text/html\r\n";

    // Si el correo se manda correctamente devolvemos true
    if (mail($to, $subject, $message, $headers)) {
        return true;
        // Si no, devolvemos false...
    } else {
        return false;
    }
}