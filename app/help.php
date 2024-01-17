<?php
error_reporting(0);
include_once "config/configuracion.php";
include_once "bd/ConexionBD.php";
include_once "modelo/Sistemas.php";
include_once "modelo/SistemasContenido.php";

session_start();
$Session_IDUsuarioBD = $_SESSION["id_usuario"];
$Session_UsuarioNombre = $_SESSION["nombre_usuario"];
$Session_IDEstacion = $_SESSION["id_gas_usuario"];
$Session_IDPuestoBD = $_SESSION["id_puesto_usuario"];
$Session_NombreEstacion = $_SESSION["nombre_gas_usuario"];
$Session_TipoPuestoBD = $_SESSION["tipo_puesto_usuario"];

$ClassConexionBD = new ConexionBD();
$ClassSistemas = new Sistemas();
$ClassContenido = new SistemasContenido();

function nombremes($mes){

    if ($mes=="01") $mes="Enero";
    if ($mes=="02") $mes="Febrero";
    if ($mes=="03") $mes="Marzo";
    if ($mes=="04") $mes="Abril";
    if ($mes=="05") $mes="Mayo";
    if ($mes=="06") $mes="Junio";
    if ($mes=="07") $mes="Julio";
    if ($mes=="08") $mes="Agosto";
    if ($mes=="09") $mes="Septiembre";
    if ($mes=="10") $mes="Octubre";
    if ($mes=="11") $mes="Noviembre";
    if ($mes=="12") $mes="Diciembre";
    
    return $mes;
    }

    function get_nombre_dia($fecha){
        $fechats = strtotime($fecha);
     switch (date('w', $fechats)){
         case 0: return "Domingo"; break;
         case 1: return "Lunes"; break;
         case 2: return "Martes"; break;
         case 3: return "Miercoles"; break;
         case 4: return "Jueves"; break;
         case 5: return "Viernes"; break;
         case 6: return "Sabado"; break;
     }
     }
    
function FormatoFecha($fechaFormato){
    $formato_fecha = explode("-",$fechaFormato);
    $resultado = $formato_fecha[2]." de ".nombremes($formato_fecha[1])." del ".$formato_fecha[0];
    return $resultado;
  }

  date_default_timezone_set('America/Mexico_City');
  $fecha_del_dia = date("Y-m-d");
?>