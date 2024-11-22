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
        echo $curso->editarTema($numLista, $columna, $valor, $numTema);
        break;
    case 'nuevo-tema':
        $modulo = $_POST['modulo'];
        $titulo = $_POST['nombre_tema'];
        echo $curso->nuevoTema($modulo, $titulo);
        break;
    case 'editar-pregunta':
        $id = $_POST['id'];
        $concepto = $_POST['concepto'];
        $columna = $_POST['columna'];
        echo $curso->editarPregunta($id, $concepto, $columna);
        break;
    case 'editar-pregunta-respuesta':
        $id = $_POST['id'];
        $idPregunta = $_POST['id_pregunta'];
        echo $curso->editarPreguntaRespuesta($id, $idPregunta);
        break;
    case 'nueva-respuesta':
        $idTema = $_POST['idTema'];
        $respuesta = $_POST['respuesta'];
        echo $curso->nuevaRespuesta($idTema, $respuesta);
        break;
    case 'agregar-pregunta':
        $idTema = $_POST['id_tema'];
        $titulo = $_POST['titulo'];
        echo $curso->nuevaPregunta($idTema, $titulo);
        break;
}
