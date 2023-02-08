<?php

if ($_SERVER['REQUEST_URI'] == "/errores/error_403.php") { 
    header("Location: /");
}

?>

<!DOCTYPE html>
<html lang="es-Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
    <title>AlertBully</title>
</head>

<body>
    <div class="principal contenedorError">
        <h1 class="granCabecera">¡Oops!</h1>
        <div class="descripcionError">
            <hr>
            <p>Parece que no puedes entrar aquí }:)</p>
            <p>Pulsa <a href="/">aquí</a> para volver a la página de inicio.</p>
            <hr>
        </div>
    </div>
</body>

</html>