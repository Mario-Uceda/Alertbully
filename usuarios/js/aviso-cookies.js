/**
 * Este fichero contiene la lógica para mostrar el aviso de uso de cookies
 * El código original de esta lógica es el siguiente:
 * https://github.com/josemmo/aviso-cookies
 */

// Definimos el código que muestra el mensaje de aviso
function showCookiesMsg() {
    var cookie_name = 'cookies_accepted';
    if (document.cookie.indexOf(cookie_name + '=1') < 0) {
        document.head.innerHTML += '<style type="text/css">' +
            '.__cookies_msg a:hover { background:rgba(0,0,0,0.7) !important }' +
            '@media screen and (max-width:900px) {' +
            '.__cookies_msg a { margin-top:10px !important }' +
            '.__cookies_msg hr { display:block !important }' +
            '}' +
            '</style>';
        document.body.innerHTML += '<div class="__cookies_msg" style="' +
            'position:fixed;' +
            'left:0;' +
            'right:0;' +
            'bottom:0;' +
            'display:block;' +
            'margin:0;' +
            'padding:15px;' +
            'background:rgba(233,233,233,0.95);' +
            'color:rgba(0,0,0,0.8);' +
            'font-family:Arial, sans-serif;' +
            'font-size:1.1em;' +
            'font-weight:400;' +
            'font-style:normal;' +
            'text-align:center' +
            '">Alertbully utiliza cookies para mejorar su experiencia en la navegación. Si continua navegando acepta el uso que hacemos de ellas. ' +
            '<button style="width: auto; height: auto; padding: 12px;"onClick="showCookiesPopup()">Más información</button></div>';
    }

    // Guardamos (o actualizamos) una cookie para recordar que ya se ha mostrado el mensaje
    var d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = cookie_name + '=1;expires=' + d.toUTCString() + ';path=/;secure;';
}


// Definimos la ventana emergente que aparecerá cuando un usuario solicite más información
function showCookiesPopup() {
    var popup = window.open('', 'Nuestra política de cookies', 'width=600,height=500');
    popup.document.write('<!doctype html><html>' +
        '<head>' +
        '<meta charset="utf-8">' +
        '<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">' +
        '<title>Nuestra política de cookies</title>' +
        '<style type="text/css">' +
        'body { text-align: justify; margin:0; padding:20px; background:#E9E9E9; color:rgba(0,0,0,0.8); font-size:14px }' +
        'p, h1, h2 { font-family:Arial, sans-serif }' +
        'h1 { margin:0; font-size:36px }' +
        'h2 { font-size:22px }' +
        '</style>' +
        '</head>' +
        '<body>' +
        '<h1>Nuestra política de cookies</h1>' +
        '<h2>¿Qué es una cookie?</h2>' +
        '<p>Este sitio web utiliza cookies y / o tecnologías similares que almacenan y recuperan información cuando navegas.</p> '+ 
        '<p>En general, estas tecnologías pueden servir para finalidades muydiversas, como, por ejemplo, reconocerte como usuario, obtener información sobre tus hábitos de navegación, o personalizar la forma en que se muestra el contenido.</p>'+
        '<p>Puede usted permitir o bloquear las cookies, así como borrar sus datos de navegación (incluidas las cookies) desde el navegador que usted utiliza. Consulte las opciones e instrucciones que ofrece su navegador para ello. ' +
        '<p>Tenga en cuenta que si acepta las cookies de terceros, deberá eliminarlas desde las opciones del navegador.</p>'+
        '<p>Los usos concretos que hacemos de estas tecnologías se describen a continuación.</p> ' +
        '<h2>Nuestra cookies</h2>' +
        '<p>Utilizamos cookies de sesión, para garantizar que los usuarios que interactuen con la web sean humanos y no aplicaciones automatizadas. De esta forma se combate el spam y posibles ataques.</p>' +
        '</body>' +
        '</html>');
}


// Esperamos a que la página web cargue
if (typeof window.onload != 'function') {
    window.onload = showCookiesMsg;
} else {
    var onLoad = window.onload;
    window.onload = function () {
        if (onLoad) oldonload();
        showCookiesMsg();
    };
}