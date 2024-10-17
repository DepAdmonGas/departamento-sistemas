<?php
//Reanudamos la sesión
session_start();

//Requerimos los datos de la conexión a la BBDD
include_once "app/config/configuracion.php";

//Des-establecemos todas las sesiones
unset($_SESSION);

//Destruimos las sesiones
session_destroy();

setcookie('COOKIEADMONGAS', '', time() - 1, '/');

//Redireccionamos a el index
header("Location:".PORTAL);
die();
