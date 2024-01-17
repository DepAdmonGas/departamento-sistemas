<?php
//Reanudamos la sesión
session_start();

//Requerimos los datos de la conexión a la BBDD
include_once "app/config/configuracion.php";
include_once "app/bd/ConexionBD.php";

//Des-establecemos todas las sesiones
unset($_SESSION);

//Destruimos las sesiones
session_destroy();

//Cerramos la conexión con la base de datos
mysqli_close($con);

//Redireccionamos a el index
header("Location:".PORTAL);
die();
?> 