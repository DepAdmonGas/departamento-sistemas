<?php
require('../help.php');

if($_POST['Accion'] == 'nuevo-folio'){
    $Resultado = $ClassSistemas->NuevoTicket($Session_IDUsuarioBD);
    echo $Resultado;
}
else if($_POST['Accion'] == 'editar-prioridad'){
    $Resultado = $ClassSistemas->EditarPrioridad($_POST['idRegistro'],$_POST['Dato']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'editar-descripcion'){
    $Resultado = $ClassSistemas->EditarDescripcion($_POST['idRegistro'],$_POST['Dato']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'agregar-actividad'){

    $Name = $_FILES['Archivo_file']['name'];
    $Temporal = $_FILES['Archivo_file']['tmp_name'];

    $Resultado = $ClassSistemas->AgregarActividad($_POST['idRegistro'],$_POST['ActividadDescripcion'],$Name, $Temporal);
    echo $Resultado;
}
else if($_POST['Accion'] == 'agregar-evidencia'){

    $Name = $_FILES['Archivo_file']['name'];
    $Temporal = $_FILES['Archivo_file']['tmp_name'];

    $Resultado = $ClassSistemas->AgregarEvidencia($_POST['idRegistro'],$_POST['ActividadEvidencia'],$Name, $Temporal);
    echo $Resultado;
}
else if($_POST['Accion'] == 'eliminar-actividad'){
    $Resultado = $ClassSistemas->EliminarActividad($_POST['idActividad']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'eliminar-evidencia'){
    $Resultado = $ClassSistemas->EliminarEvidencia($_POST['idActividad']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'finalizar-registro'){
    $Resultado = $ClassSistemas->FinalizarRegistro($_POST['idRegistro']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'cancelar-ticket'){
    $Resultado = $ClassSistemas->CancelarTicket($_POST['idticket']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'guardar-comentario'){
    $Resultado = $ClassSistemas->GuardarComentario($_POST['idticket'],$_POST['comentario'],$Session_IDUsuarioBD);
    echo $Resultado;
}
else if($_POST['Accion'] == 'editar-registro'){
    $Resultado = $ClassSistemas->EditarRegistro($_POST['idticket'],$_POST['Detalle'],$_POST['opcion']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'editar-actividad'){
    $Resultado = $ClassSistemas->EditarActividad($_POST['idActividad'],$_POST['Detalle'],$_POST['opcion']);
    echo $Resultado;
}
else if($_POST['Accion'] == 'finalizar-soporte'){
    $Resultado = $ClassSistemas->FinalizarSoporte($_POST['idticket'],$Session_IDUsuarioBD);
    echo $Resultado;
}



?>