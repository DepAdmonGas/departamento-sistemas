<?php
include_once "config/configuracion.php";
include_once "bd/ConexionBD.php";
include_once "modelo/Sistemas.php";
include_once "modelo/SistemasContenido.php";
include_once 'lib/jwt/vendor/autoload.php';
include_once "config/ConfiguracionSesiones.php";
include_once 'modelo/Home.php';

$ClassConexionBD = new ConexionBD();
$ClassSistemas = new Sistemas();
$ClassContenido = new SistemasContenido();
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// Valida si esta activa la sesion por medio de la cookie
if (isset($_COOKIE['COOKIEADMONGAS']) && !empty($_COOKIE['COOKIEADMONGAS'])) :
    // Instancia la clase configuracion-sesiones
    $configuracionSesiones = new ConfiguracionSesiones();
    // Obtiene keyJWT
    $keyJWT = $configuracionSesiones->obtenerKey();
    $token = $_COOKIE['COOKIEADMONGAS']; 
    try {
        $decoded = JWT::decode($token, new Key($keyJWT, 'HS256'));
        $Session_IDUsuarioBD = $decoded->id_usuario;
        $Session_UsuarioNombre = $decoded->nombre_usuario;
        $Session_IDEstacion = $decoded->id_gas_usuario;
        $Session_IDPuestoBD = $decoded->id_puesto_usuario;
        $Session_NombreEstacion = $decoded->nombre_gas_usuario;
        $Session_TipoPuestoBD = $decoded->tipo_puesto_usuario;
        $con = $ClassConexionBD->conectarBD();
        $Home = new Home($con);
    } catch (Exception $e) {
        echo 'Error: ', $e->getMessage();
    }
else :
    header("Location:" . PORTAL . "");
    die();
endif;




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
         case 0: return "Domingo";
         case 1: return "Lunes";
         case 2: return "Martes";
         case 3: return "Miercoles";
         case 4: return "Jueves";
         case 5: return "Viernes";
         case 6: return "Sabado";
     }
     }
    
function FormatoFecha($fechaFormato){
    $formato_fecha = explode("-",$fechaFormato);
    $resultado = $formato_fecha[2]." de ".nombremes($formato_fecha[1])." del ".$formato_fecha[0];
    return $resultado;
  }

  date_default_timezone_set('America/Mexico_City');
  $fecha_del_dia = date("Y-m-d");
