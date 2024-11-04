<?php
require '../help.php';
require 'tokenTelegram.php';
$conexion = $ClassConexionBD->conectarBD();
$telegram = new Telegram($conexion);
// Fecha actual
$fechaActual = date('Y-m-d');
// mensaje
$mensaje = "";
// Consulta para seleccionar actividades con porcentaje menor al 100% y verificar la fecha de término
$sql = "SELECT id_ticket, descripcion, fecha_inicio, fecha_termino, tiempo_solucion, porcentaje FROM ds_soporte WHERE porcentaje < 100";
$resultado = $conexion->query($sql);

while ($actividad = $resultado->fetch_assoc()) {
    $id = $actividad['id_ticket'];
    $personal = $actividad['id_personal_soporte'];
    $descripcion = $actividad['descripcion'];
    $fechaInicio = new DateTime($actividad['fecha_inicio']);
    $fechaTermino = new DateTime($actividad['fecha_termino']);
    $fechaActualDate = new DateTime($fechaActual);
    $tiempoSolucion = $actividad['tiempo_solucion'];
    $porcentajeActual = $actividad['porcentaje'];

    // Días transcurridos desde el inicio hasta la fecha actual
    $diasTranscurridos = $fechaInicio->diff($fechaActualDate)->days;

    // Cálculo de porcentaje basado en la regla de tres
    if ($tiempoSolucion > 0) { // Evitar división por cero
        $nuevoPorcentaje = ($diasTranscurridos / $tiempoSolucion) * 100;

        // Asegurar que el porcentaje no supere el 100%
        if ($nuevoPorcentaje > 100) {
            $nuevoPorcentaje = 100;
        }

        // Solo actualizar si el nuevo porcentaje es mayor al actual
        if ($nuevoPorcentaje > $porcentajeActual) {
            $actualizarPorcentaje = "UPDATE ds_soporte SET porcentaje = $nuevoPorcentaje WHERE id = $id";
            $conexion->query($actualizarPorcentaje);
        }

        // Si ya pasó la fecha de término, envía una alerta
        if ($fechaActualDate > $fechaTermino) {
            $diasRetraso = $fechaTermino->diff($fechaActualDate)->days;

            if ($diasRetraso == 1) {
                $mensaje = "La actividad con ID $id y descripción '$descripcion' ha pasado su fecha de término";
                $telegram->enviarToken($personal,$mensaje);
            }
        }
    }
}

$conexion->close();
