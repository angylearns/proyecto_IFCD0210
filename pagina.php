<?php
include("funciones.php");
session_start();
chequearSesion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de <?php echo $_SESSION["usuario"];?></title>
    <script src="js/scripts.js"></script>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php
    if (!$_SESSION["usuario"]) {
        header("Location: index.php");
    } else {
        include("header.php");

        $usuario = $_SESSION["usuario"];

        $accesoEntradas = new ConectarDB;
        $consultaAccesoEntradas = "SELECT entradas.texto, entradas.id_entrada FROM entradas, usuarios WHERE usuarios.usuario = '$usuario' AND usuarios.id_usuario = entradas.id_usuario;";
        $resultadoEntradas = $accesoEntradas->consultar($consultaAccesoEntradas)->fetch_all(MYSQLI_ASSOC);
        $accesoEntradas->cerrar();    ?>
        <div id="todasEntradas">
        <!-- VER A PARTIR DE AQUÍ ÑÑÑ -->
        <div class="todasEtiquetasRelacion">  <?php
            $accesoEtiquetasRelacion = new ConectarDB;
            $consultaAccesoEtiquetasRelacion = "SELECT DISTINCT etiquetas.nombre, etiquetas.id_etiqueta FROM etiquetas INNER JOIN etiq_entradas ON etiquetas.id_etiqueta = etiq_entradas.id_etiqueta INNER JOIN entradas ON etiq_entradas.id_entrada = entradas.id_entrada INNER JOIN usuarios ON entradas.id_usuario = (SELECT id_usuario FROM usuarios WHERE usuario = '$usuario');";
            $resultadoEtiquetasRelacion = $accesoEtiquetasRelacion->consultar($consultaAccesoEtiquetasRelacion)->fetch_all(MYSQLI_ASSOC);
            $accesoEtiquetasRelacion->cerrar();       ?>
            <ul>
                <?php
                foreach ($resultadoEtiquetasRelacion as $etiqueta) {
                    ?>
                    <li class="cadaEtiquetaRelacion" onclick="pagAbrirModalEntrada(<?php echo $etiqueta['id_etiqueta'];?>)"><?php echo $etiqueta['nombre'];?></li> 
                    <?php
                } ?>
            </ul>       <?php
            /* MODAL ENTRADAS SEGÚN ETIQUETA */
            foreach ($resultadoEtiquetasRelacion as $etiqueta) {
                $etiquetaIDEtiqueta = $etiqueta['id_etiqueta'];
                $accesoTextoBien = new ConectarDB;
                $consultaTextoBien = "SELECT DISTINCT texto, entradas.id_entrada, etiquetas.id_etiqueta FROM entradas INNER JOIN etiq_entradas ON etiq_entradas.id_entrada = entradas.id_entrada INNER JOIN usuarios ON usuarios.id_usuario = entradas.id_usuario INNER JOIN etiquetas ON etiq_entradas.id_etiqueta = etiquetas.id_etiqueta WHERE usuarios.usuario = '$usuario' AND etiq_entradas.id_etiqueta = '$etiquetaIDEtiqueta';"; 
                $consultaIDEntYEtiq = "SELECT entradas.id_entrada, etiquetas.id_etiqueta FROM entradas INNER JOIN etiq_entradas ON entradas.id_entrada = etiq_entradas.id_entrada INNER JOIN etiquetas ON etiquetas.id_etiqueta = etiq_entradas.id_etiqueta WHERE entradas.id_usuario = (SELECT usuarios.id_usuario FROM usuarios WHERE usuarios.usuario = '$usuario') AND etiquetas.id_etiqueta = '$etiquetaIDEtiqueta';";
                $resultadoTextoBien = $accesoTextoBien->consultar($consultaTextoBien)->fetch_all(MYSQLI_ASSOC);
                $resultadoIDEntYEtiq = $accesoTextoBien->consultar($consultaIDEntYEtiq)->fetch_all(MYSQLI_ASSOC);
/*                 echo "<pre>";
                var_dump($resultadoIDEntYEtiq);
                echo "</pre>"; */
                ?>
                <div id="pagDivModalEntSegunEtiq_<?php echo $etiqueta['id_etiqueta'];?>" class="pagModal">
                    <div class="pagContenidoModal pagContenidoModalEntSegunEtiq">
                        <span id="entradasAsoc">Entradas asociadas:</span>
                        <span class="pagCerrarModal" onclick="pagCerrarModalEntEtiq(<?php echo $etiqueta['id_etiqueta'];?>)">X</span>
                        <p class="pagContenidoModalEntradaEtiq">   <?php 
                            foreach ($resultadoTextoBien as $textoBien) {
                                $textito = $textoBien["texto"];  ?>
                                <span class="pagContenidoModalCadaEntrada" onclick="pagCerrarYAbrirModales(<?php echo $etiqueta['id_etiqueta'] ?>)"><?php echo $textoBien["texto"];?></span>
                                <?php    
                            }   ?>
                        </p>
                    </div>
                </div>
                <?php
            }
            /* FIN MODAL ENTRADAS SEGÚN ETIQUETA  */
            ?>
        </div>
        <!-- VER HASTA AQUÍ ÑÑÑ -->
            <?php
            foreach ($resultadoEntradas as $entrada) { 
                $entradaIDEntrada = $entrada['id_entrada'];   ?>
            <div class="entrada" data-id-entrada="<?php echo $entrada["id_entrada"];?>">
                <?php   echo $entrada["texto"];   ?> 
                <span class="pagSpanModales" id="pagBotonModal" onclick="pagAbrirModalEnt(<?php echo $entrada['id_entrada'];?>)"><mark>Abrir modal</mark></span>
            </div> 

            <!-- COMIENZO MODAL ENTRADAS -->
            <?php 
            $accesoCadaEtiqEntrada = new ConectarDB;
            $consultaCadaEtiqEntrada = "SELECT etiquetas.nombre FROM etiquetas INNER JOIN etiq_entradas ON etiquetas.id_etiqueta = etiq_entradas.id_etiqueta INNER JOIN entradas ON entradas.id_entrada = etiq_entradas.id_entrada WHERE entradas.id_entrada = $entradaIDEntrada";
            $resultadoCadaEtiqEntrada = $accesoCadaEtiqEntrada->consultar($consultaCadaEtiqEntrada)->fetch_all(MYSQLI_ASSOC);   
            $accesoCadaEtiqEntrada->cerrar();     ?>
            <div id="pagDivModalEnt_<?php echo $entrada["id_entrada"]?>" class="pagModal">
                <div class="pagContenidoModal pagContenidoModalEntradas">
                    <span class="pagCerrarModal" onclick="pagCerrarModalEnt(<?php echo $entrada['id_entrada'];?>)">X</span>
                    <div id="pagContenidoModalDefecto_<?php echo $entrada["id_entrada"]?>" >
                        <p class="pagContenidoModalMostrarEntrada"><?php echo $entrada["texto"];?></p>   
                        <p class="pagContenidoModalEtiq"> <span class="pagContenidoModalSpanEtiq">Etiquetas:</span> 
                            <?php 
                            foreach ($resultadoCadaEtiqEntrada as $etiqEntrada) {
                                ?>
                                <span class="pagContenidoModalEtiqCadaEtiq"><?php echo $etiqEntrada["nombre"];?></span> <br>
                                <?php 
                            } ?>
                        </p>
                        <span id="pagSpanEditarEnt" onclick="pagInputEditarEntrada(<?php echo $entrada['id_entrada']?>)">Editar</span>
                    </div>
                    <!-- COMIENZO CONTENIDO ESCONDIDO POR DEFECTO -->
                    <div id="contenidoModalInputEdicion_<?php echo $entrada['id_entrada']?>" style="display:none">                
                        <form method="POST" id="pagFormularioEdicion_<?php echo $entrada['id_entrada'];?>">
                            <textarea name="adicionEditada" class="adicionEditada" id="adicionEditada_<?php echo $entrada['id_entrada'];?>" cols="30" rows="10" maxlength="995" oninput="pagContarCarEdicion(<?php echo $entrada['id_entrada'];?>)"><?php echo $entrada["texto"];?></textarea>
                            <div id="pagContadorCarEdicion_<?php echo $entrada['id_entrada'];?>">0/995</div>
                            <button id="btnEnviarEditar" onclick="pagEditarEntradas(<?php echo $entrada['id_entrada'];?>)">Enviar</button>
                        </form>
                    </div>
                    <!-- FIN CONTENIDO ESCONDIDO POR DEFECTO -->
                </div>
            </div>
            <!-- FIN MODAL ENTRADAS -->
            <?php
            }   ?>
        </div>

        <form action="entradasYEtiquetas.php" method="post"> 
            <textarea name="adicion" id="adicion"  cols="30" rows="5" placeholder="Escribe aquí" maxlength="995" oninput="pagContarCar()"></textarea>
            <div id="pagContadorCar">0/995</div> 
            <br>
            <?php
                $accesoEtiquetasInput = new ConectarDB;
                $consultaAccesoEtiquetasInput = "SELECT DISTINCT etiquetas.nombre, etiquetas.id_etiqueta FROM etiquetas INNER JOIN etiq_entradas ON etiquetas.id_etiqueta = etiq_entradas.id_etiqueta INNER JOIN entradas ON etiq_entradas.id_entrada = entradas.id_entrada INNER JOIN usuarios ON entradas.id_usuario = (SELECT id_usuario FROM usuarios WHERE usuario = '$usuario')";
                $resultadoEtiquetasInput = $accesoEtiquetasInput->consultar($consultaAccesoEtiquetasInput)->fetch_all(MYSQLI_ASSOC);
                $accesoEtiquetasInput->cerrar();
            ?>
            <label for="inputEtiquetas">Etiquetas</label>
            <input type="text" name="inputEtiqueta" id="inputEtiqueta" placeholder="etiqueta"> <span id="pagBorrarEtiq" onclick="pagBorrarEtiq()">Borrar etiquetas</span>
            <br><br>
            <div id="todasEtiquetasInput">
                <?php 
                foreach ($resultadoEtiquetasInput as $etiqueta) {   
                    ?>
                    <span class="cadaEtiquetaInput" onclick="pagPintarEtiquetaInput('<?php echo $etiqueta['nombre'];?>')" id="cadaEtiqueta_<?php echo $etiqueta['id_etiqueta'];?>" data-id-etiqueta="<?php echo $etiqueta['id_etiqueta'];?>">
                        <?php echo $etiqueta["nombre"];?>
                    </span>
                    <?php
                }
                    ?>
            </div>
            <br>
            <input type="submit" value="Insertar" id="btnInsertar" onclick="pagManejarEtiquetas()">
        </form>
        <br>
        
        <?php
    } ?>
</body>
</html>


