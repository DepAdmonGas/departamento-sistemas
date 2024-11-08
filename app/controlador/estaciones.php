<?php
require_once "../modelo/Estaciones.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$estacion = new Estaciones($conexion->conectarBD());

switch ($_POST['accion']) {
    case 'nuevo-estacion':
        echo $puesto->nuevoPuesto();
        break;
    case 'editar-estacion':
        $numLista = $_POST['numLista'];
        $valor = $_POST['concepto'];
        $columna = $_POST['columna'];

        echo $estacion->editarEstacion($numLista,$columna,$valor);
        break;
    case 'eliminar-estacion':
        $numLista = $_POST['numlista'];
        echo $estacion->eliminarEstacion($numLista);
        break;
}
