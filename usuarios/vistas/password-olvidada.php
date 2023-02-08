<?php
/**
 * A través de este fichero mostraremos el formulario para pedir un restablecimiento de contraseña.
 * También tenemos toda la lógica del programa.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con los correos electrónicos
require $_SERVER['DOCUMENT_ROOT'] . '/includes/correos.inc.php';

// Importamos la clase consultas y creamos un objeto Consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

// Si en la sesión hay usuario volvemos al index o se intenta acceder desde la url
if (isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/password-olvidada.php") {
    header("Location: /");
}

// Si recibimos un email a través del formulario...
if (isset($_POST['email'])) {
    // Guardamos el email introducido desde el formulario en $email
    $email = htmlspecialchars($_POST['email']);

    // Comprobamos que el correo no esté vacio
    if (!empty($email)) {

        // Guardamos los datos del usuario que tenga ese $email en $usuario
        // a través de la función getDatosUsuarioConEmail()
        $usuario = $consulta->getDatosUsuarioConEmail($email);

        // Si hay una fila...
        if ($usuario != null) {

            /*
                Creamos 2 tokens. $selector que nos servirá para buscar en la base de datos el token $token
                y comprobar que el token que nos pasa el usuario es el mismo que el de la base de datos.
                De esta forma evitamos Timming Attacks
            */
            // Creamos el token $selector y lo pasamos a hexadecimal
            $selector = bin2hex(random_bytes(8));
            // Creamos el token $token pero no lo pasamos a hexadecimal
            $token = random_bytes(32);
            // Guardamos en la variable $server el dominio del servidor
            $server = $_SERVER['SERVER_NAME'];

            // Creamos la URL que le pasaremos al usuario con $selector y $token pasado a hexadecimal
            $url = $server . "/restablecimiento?selector=" . $selector . "&token=" . bin2hex($token);

            // Guardamos en $expira el día de hoy en segundos desde 1970 + 30 min
            // Este será el tiempo que tiene el usuario para restablecer la contraseña
            $expira = date("U") + 1800;

            // Borramos de la tabla pwdreset el registro que contenga el email del usuario
            $consulta->borrarRegistroPwdresetPorEmail($email);

            // Guardamos los datos de restablecimiento en la tabla pwdreset
            $tokenHash = hash("sha512", $token);
            $ejecucion = $consulta->nuevoRegistroPwdreset($email, $selector, $tokenHash, $expira);

            // Si la inserción se ha realizado correctamente...
            if ($ejecucion) {
                // Si el correo se manda de forma satisfactoria...
                if (correoRestablecimiento($url, $email)) {
                    // Mostramos un mensaje de confirmación en la página de login
                    header('Location: /?email=enviado&dir=' . $email . '');
                // Si no...
                } else {
                    // Mostramos un error
                    $error = $ERROR_GENERAL;
                }
            } else {
                $error = $ERROR_GENERAL;
            }
        } else {
            // Le hacemos pensar al usuario que existe una cuenta con ese email }:)
            // a través de la página de login
            header('Location: /?email=enviado&dir=' . $email . '');
        }
    } else {
        $error = $CAMPOS_VACIOS;
    }
}

?>

<!DOCTYPE html>
<html lang="es-Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/styles/style.css?<?php echo date('Y-m-d H:i:s'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>AlertBully</title>
</head>

<body>
    <header>
        <a class="logo" href="/">AlertBully</a>
    </header>
    <div class="principal">
        <h1>Restablecer contraseña</h1>
        <?php

        // Si ha ocurrido un error lo mostramos
        if (isset($error)) {
            ?>

            <p style="margin-top: 3%;" class="error"><?php echo $error ?></p>
            <form action="#" method="POST">
                <p style="margin-top: 4% !important; margin-bottom: 0; margin: auto; width: 77%;">
                    Introduce tu dirección de correo electrónico y te mandarémos un email para que puedas restablecer tu contraseña.
                </p>
                
                <div class="group">
                    <input type="text" name="email" required>
                    <label>Introduce tu email</label>
                </div>

                <button type="submit">Mandar Email</button>
            </form>

        <?php

        // Si no, mostramos el título con el formulario
        } else {

        ?>

            <form action="#" method="POST">
                <p style="margin-top: 4% !important; margin-bottom: 0; margin: auto; width: 77%;">
                    Introduce tu dirección de correo electrónico y te mandarémos un email para que puedas restablecer tu contraseña.
                </p>

                <div class="group">
                    <input type="text" name="email" required>
                    <label>Introduce tu email</label>
                </div>

                <button type="submit">Mandar Email</button>
            </form>
            
        <?php

        }

        ?>

    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
</body>

</html>