function creUBorrar() {
    document.getElementById("nombreUsuario").value = "";
    document.getElementById("clave").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("email").value = "";
}


function creUVaciarCrearOk() {
    document.getElementById("nombreUsuario").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("email").value = "";
    document.getElementById("mensajeCreacion").innerHTML = "Usuario creado con éxito."
}


function pagContarCar() {
    maxLong = document.getElementById("adicion").getAttribute("maxlength");
    longVerdad = document.getElementById("adicion").value.length;
    document.getElementById("pagContadorCar").innerHTML = longVerdad + "/" + maxLong;
}

function pagContarCarEdicion(id) {
    longVerdadEd = document.getElementById("adicionEditada_" + id).value.length;
    maxLongEd = document.getElementById("adicionEditada_" + id).getAttribute("maxlength");
    document.getElementById("pagContadorCarEdicion_" + id).innerHTML = longVerdadEd + "/" + maxLongEd;
}

function siguienteEntrada(id) {

}

function anteriorEntrada(id) {
    


}

/* COMIENZO MODALES */



function pagAbrirModalEntrada(id) {
    if (document.getElementById("contenidoModalInputEdicion_" + id).style.display = "inline-block") {
        document.getElementById("contenidoModalInputEdicion_" + id).style.display = "none";
    }

    document.getElementById("pagDivModalEnt_" + id).style.display = "block";
    document.getElementById("pagContenidoModalDefecto_" + id).style.display = "inline-block";


    /* A PARTIR DE AQUÍ, BOTONES DE ANTERIOR Y SIGUIENTE- QUE HAY QUE ARREGLAR ↓ */

    ant = document.getElementById("pagDivModalEnt_" + id).previousElementSibling.previousElementSibling.id;
    console.log("Anterior " + ant);
    antID = ant.split("_");
    if (!antID[1]) {
        document.getElementById("btnAnteriorEntrada").style.display = "none"; 
    } 

    actual = document.getElementById("pagDivModalEnt_" + id).id;
    console.log("Actual " + actual);
    nodos = document.getElementById("todasEntradas").childNodes;
    ultNodo = nodos.length-2;
    if (document.getElementById("pagDivModalEnt_" + id).nextElementSibling) {
        sig = document.getElementById("pagDivModalEnt_" + id).nextElementSibling.nextElementSibling.id;} 

    if (nodos[ultNodo].id === actual) {
        console.log("Igual");
        document.getElementById("btnSiguienteEntrada").style.display = "none"; }

    /* ↑ ARREGLAR ESTO */
}




function pagCerrarModalEntrada(id) {
    document.getElementById("pagDivModalEnt_" + id).style.display = "none";
}

function pagAbrirModalEtiqueta(id) {
    document.getElementById("pagDivModalEntSegunEtiq_" + id).style.display = "block";
}

function pagCerrarModalEntSegunEtiq(id) {
    document.getElementById("pagDivModalEntSegunEtiq_" + id).style.display = "none";
}

function pagCerrarsito(id) {
    document.getElementById("pagDivModalEntSegunEtiq_" + id).style.display = "none";
}

function pagAbrirsito(id) {
    document.getElementById("pagDivModalEnt_" + id).style.display = "block";
}

/* FIN MODALES */

var arrayInput = new Array (); // no sirve de nada y está en la siguiente funciónm, que tampoco sirve para nada
function cambiarlenombre() {
    /* CON ESTO VOY AGARRANDO LO QUE MANDO CON "NUEVA ETIQUETA" EN EL INPUT Y LO VOY AGREGANDO A UN ARRAY
    var loDelInput = document.getElementById("inputEtiqueta").value;
    document.getElementById("etiquetasElegidas").innerHTML += "<span class='cadaEtiquetaInput'>" + loDelInput + "</span>";
    arrayInput.push(loDelInput);
    console.log(arrayInput); */

    /* 
    document.getElementById("etiquetasElegidas").innerHTML += "<span class='cadaEtiquetaInput'>" + loDelInput + "</span>";
    var etqs = document.getElementById("etiquetasElegidas").innerHTML.split('<span class="cadaEtiquetaInput">');
    var etq = "";
    for (i=1; i<etqs.length; i++) {
        etq += etqs[i];
        var etqSplit = etq.split("</span>");
        var etqCasiListo = etqSplit.join(","); 
        var JsonEtiquetas = JSON.stringify(etqCasiListo);
    } */
    document.getElementById("inputEtiqueta").value = "";
}

function pagSpanEliminarEnt(id) {
    var urlAJAX = "http://localhost:8080/pen_arb/eliminaciones.php";
    var llamadaAJAX = new XMLHttpRequest();

    llamadaAJAX.onreadystatechange = function() {
        if (llamadaAJAX.readyState == 4 ) {
            /* var respuesta = llamadaAJAX.responseText;
            document.getElementById("todasEntradas").innerHTML = respuesta; */
            window.location="pagina.php";
        }
     }

    llamadaAJAX.open( "GET", urlAJAX + "?idEntrada=" + id);
    llamadaAJAX.send();
} 

function pagPintarEtiquetaInput(etq) {
    document.getElementById("inputEtiqueta").value += etq + ", ";
}

function pagBorrarEtiq() {
    document.getElementById("inputEtiqueta").value = "";
}

function pagInputEditarEntrada(id) {
    document.getElementById("pagContenidoModalDefecto_" + id).style.display = "none";
    document.getElementById("contenidoModalInputEdicion_" + id).style.display = "inline-block";
}

function cambiarOrdenFecha(id) {
    fechaCreacion = document.getElementById("fechaCreacion_" + id).innerHTML.split("-");
    fechaCreacion = fechaCreacion[2] + "-" + fechaCreacion[1] + "-" + fechaCreacion[0];
    document.getElementById("fechaCreacion_" + id).innerHTML = fechaCreacion;
}

function cerrarModalEdicion(id) {    
    document.getElementById("contenidoModalInputEdicion_" + id).style.display = "none";
    document.getElementById("pagContenidoModalDefecto_" + id).style.display = "inline-block";
}

/* ↓ AQUÍ - La función aplica al primer elemento donde aparece dicha entrada. Ej.: la etiqueta "musical", si quiero que salga el input para editarla, saldrá en la primera entrada */

function mostrarInputEdicionEtiq(id) {
    document.getElementById("etiquetaEdicion_" + id).style.display = "inline-block";
    document.getElementById("signoCheck_" + id).style.display = "inline-block";
}

/* ↑ PREGUNTAR */

function agregarEtiqEditInputEscond(id) {
    var etq = document.getElementById("etiquetaEdicion_" + id).value;
    if (document.getElementById("etiquetaEdicion_" + id).style.display = "inline-block") {
        document.getElementById("etiquetaEdicion_" + id).style.display = "none";
    }
    if (document.getElementById("signoCheck_" + id).style.display = "inline-block") {
        document.getElementById("signoCheck_" + id).style.display = "none";
    }
    document.getElementById("etiquetasModalInputEdicion_" + id).innerHTML = etq;
}