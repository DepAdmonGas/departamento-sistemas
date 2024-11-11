<?php
require_once "../modelo/Vale.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$vale = new Vale($conexion->conectarBD());

switch ($_POST['accion']) {
    case 'editar-vale':
        $numLista = $_POST['id'];
        $valor = $_POST['concepto'];
        $columna = $_POST['columna'];
        echo $vale->editarVale($numLista,$columna,$valor);
        break;
    }