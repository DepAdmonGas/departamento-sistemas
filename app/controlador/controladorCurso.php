<?php
require_once "../modelo/Curso.php";
require_once "../bd/ConexionBD.php";
$conexion = new ConexionBD();
$curso = new Curso($conexion->conectarBD());

switch ($_POST['accion']) {
    case 'nuevo-curso':
        break;
    case 'agregar-modulo':
        $titulo = $_POST['titulo'];
        echo $curso->nuevoModulo($titulo);
        break;
    case 'editar-modulo':
        $numLista = $_POST['id'];
        $valor = $_POST['concepto'];
        echo $curso->editarModulo($numLista, $valor);
        break;
    case 'editar-tema':
        $numLista = $_POST['id'];
        $valor = $_POST['concepto'];
        $columna = $_POST['columna'];
        $numTema = $_POST['numTema'];
        echo $curso->editarTema($numLista, $columna, $valor,$numTema);
        break;
}
