<?php
require_once "../modelo/Personal.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$personal = new Personal($conexion->conectarBD());

switch ($_POST['accion']) {
  case 'nuevo-personal':
    $nombre = $_POST["nombre"] ?? "";
    $puesto = $_POST["puesto"] ?? "";
    $usuario = $_POST["usuario"] ?? "";
    $password = $_POST["password"] ?? "";
    $estacion = $_POST["estacion"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefono = $_POST["telefono"] ?? "";
    echo $personal->nuevoPersonal($nombre, $puesto, $usuario, $password, $estacion, $email, $telefono);
    break;
  case 'eliminar-personal':
    $id = $_POST["id"] ?? 0;
    echo $personal->eliminarPersonal($id);
    break;
  case 'editar-personal':
    $id = $_POST['id'];
    $valor = $_POST['concepto'];
    $columna = $_POST['columna'];

    echo $personal->editarPersonal($id, $columna, $valor);
    break;
}
