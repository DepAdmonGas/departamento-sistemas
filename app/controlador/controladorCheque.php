<?php
require_once "../modelo/Cheque.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$cheque = new Cheque($conexion->conectarBD());

switch ($_POST['accion']) {
    case 'editar-cheque':
        $numLista = $_POST['id'];
        $valor = $_POST['concepto'];
        $columna = $_POST['columna'];

        echo $cheque->editarCheque($numLista,$columna,$valor);
        break;
    }