<?php
require_once "../modelo/CorteDiario.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$corte = new CorteDiario($conexion->conectarBD());

switch ($_POST['accion']) {
    case 'activar-corte':
        $detalle = $_POST['Detalle'];
        $idDia = $_POST['idDias'];
        $usuario = $_POST['usuario'];
        echo $corte->editarCorte($idDia,$detalle,$usuario);
        break;
    case 'finalizar-corte':
        $idDia = $_POST['idDias'];
        echo $corte->finalizarCorte($idDia);
        break;
    }