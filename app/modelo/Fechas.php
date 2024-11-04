<?php
$mensaje = "hola desde Fechas";

require_once '../help.php';
$mensaje = "hola desde Fechas";

$conexion = $ClassConexionBD->conectarBD();

// Fecha actual
$fechaActual = date('Y-m-d');

// mensaje
$mensaje = "hola desde Fechas";

// Consulta para seleccionar actividades con porcentaje menor al 100% y fechas válidas
$sql = "SELECT id_ticket, descripcion, fecha_inicio, fecha_termino, tiempo_solucion, porcentaje, id_personal_soporte 
        FROM ds_soporte 
        WHERE porcentaje < 100 
        AND estado <> 4 
        AND estado <> 0 
        AND fecha_inicio != '0000-00-00 00:00:00' 
        AND fecha_termino != '0000-00-00 00:00:00'";

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
                echo $mensaje;
            }
        }
    }
}

$conexion->close();
