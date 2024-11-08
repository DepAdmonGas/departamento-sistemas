<?php
require_once "../modelo/Puesto.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$puesto = new Puesto($conexion->conectarBD());

switch ($_POST['accion']) {
  case 'nuevo-puesto':
    echo $puesto->nuevoPuesto();
    break;
  case 'editar-puesto':
    $valor = $_POST['concepto'];
    $id = $_POST['idPuesto'];
    echo $puesto->editarPuesto($valor,$id);
    break;
  case 'eliminar-puesto':
    $id = $_POST['idPuesto'];
    echo $puesto->eliminarPuesto($id);
    break;
}
