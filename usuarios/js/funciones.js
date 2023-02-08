// Función para desplegar/plegar la barra superior de navegación
function topNav() {
    var menu = document.getElementById("topNav");
    if (menu.className == "header-dere") {
        menu.className += " responsive";
    } else {
        menu.className = "header-dere";
    }
}

// Función para mostrar la pass de un form
function verPassword(campo, ver) {
    var x = document.getElementById(campo);
    if (x.type == "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }

    var y = document.getElementById(ver);
    if (y.className == "fa fa-eye") {
        y.className = "fa fa-eye-slash";
    } else {
        y.className = "fa fa-eye";
    }
}


// Función para filtrar botones dentro de un div con la clase listaBotones
function filtrar() {
    var boton, textoBoton;
    var input = document.getElementById("filtro");
    var filtro = input.value.toUpperCase();
    var div = document.getElementsByClassName("listaBotones")[0];
    var cantidadBotones = div.getElementsByTagName("button").length;

    var i;
    for (i = 0; i < cantidadBotones; i++) {
        boton = div.getElementsByTagName("button")[i];
        textoBoton = boton.textContent || boton.innerText;
        if (textoBoton.toUpperCase().includes(filtro)) {
            boton.style.display = "";
        } else {
            boton.style.display = "none";
        }
    }
}

// Función para buscar dentro de una tabla
function buscarEnTabla(nombreTabla) {
    var input, filtro, table, txtValue;
    input = document.getElementById("buscadorTabla");
    filtro = input.value.toUpperCase();
    table = document.getElementsByClassName(nombreTabla)[0];
    filas = table.getElementsByTagName("tr");

    for (var i = 0; i < filas.length; i++) {
        fila = filas[i].getElementsByTagName("td");
        
        if (fila) {
            for (var j = 0; j < fila.length; j++) {
                columna = fila[j];
                txtValue = columna.textContent || columna.innerText;
                if (txtValue.toUpperCase().indexOf(filtro) > -1) {
                    filas[i].style.display = "";
                    break;
                } else {
                    filas[i].style.display = "none";
                }
            }
        }
    }
}