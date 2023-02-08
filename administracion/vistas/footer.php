<?php

/**
 * Este fichero lo usamos para llamarlo desde las páginas en las que queremos mostrar el footer
 */

// Si se intenta acceder desde la url no le dejaremos entrar }:)
if ($_SERVER['REQUEST_URI'] == "/vistas/footer.php") {
    header('Location: /');
}

?>
<footer>
    <p>
        "Nuestras elecciones revelan quienes somos" | <a style="color: white;text-decoration: underline;" href="mailto:soporte@alertbully.es">Contacto</a>
    </p>
</footer>
<script type="text/javascript" src="/js/aviso-cookies.js"></script>