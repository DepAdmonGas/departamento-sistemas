<?php

class Home
{
  private $con;
  public function __construct($con)
  {
    $this->con = $con;
  }

  public function contarActividadesIncompletas($opcion, $usuario): int
  {
    // Define la condición en base a la opción recibida
    $condicion = "AND tb_puestos.tipo_puesto = 'Departamento Sistemas'";
    if ($opcion == 1) {
      $condicion = "AND tb_puestos.tipo_puesto <> 'Departamento Sistemas'";
    }

    // Consulta SQL para contar las actividades con porcentaje <> 100
    $sql = "SELECT COUNT(*) AS cantidad_incompletas
          FROM ds_soporte ds
          INNER JOIN tb_usuarios ON ds.id_personal = tb_usuarios.id
          INNER JOIN tb_estaciones ON tb_usuarios.id_gas = tb_estaciones.id
          INNER JOIN tb_puestos ON tb_usuarios.id_puesto = tb_puestos.id
          WHERE ds.porcentaje <> 100 AND ds.id_personal_soporte = $usuario $condicion AND ds.estado <> 4 AND ds.estado <> 0";

    $result = $this->con->query($sql);

    // Valor inicial de cantidad
    $cantidad = 0;
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $cantidad = (int)$row["cantidad_incompletas"];
    }

    return $cantidad;
  }
  public function actualizarPorcentajeActividades($fechaActual)
  {
    // Consulta SQL para obtener actividades incompletas del ticket especificado
    $sql = "SELECT id_ticket, descripcion, fecha_inicio, fecha_termino, tiempo_solucion, porcentaje, id_personal_soporte 
          FROM ds_soporte
          WHERE porcentaje < 100 
          AND estado <> 4 
          AND estado <> 0 
          AND fecha_inicio != '0000-00-00 00:00:00'
          AND fecha_termino != '0000-00-00 00:00:00' ";

    $resultado = $this->con->query($sql);

    while ($actividad = $resultado->fetch_assoc()) {
      $id = $actividad['id_ticket'];
      $descripcion = $actividad['descripcion'];
      $fechaInicio = new DateTime($actividad['fecha_inicio']);
      $fechaTermino = new DateTime($actividad['fecha_termino']);
      $fechaActualDate = new DateTime($fechaActual);
      $tiempoSolucion = $actividad['tiempo_solucion'];
      $porcentajeActual = $actividad['porcentaje'];

      // Días transcurridos desde el inicio hasta la fecha actual
      $diasTranscurridos = $fechaInicio->diff($fechaActualDate)->days;
      // Si ya pasó la fecha de término, envía una alerta
      if ($fechaActualDate > $fechaTermino) {
        $diasRetraso = $fechaTermino->diff($fechaActualDate)->days;

        if ($diasRetraso > 1) {
          $actualizarPorcentaje = "UPDATE ds_soporte SET porcentaje = 60 ,estado = 5 WHERE id_ticket = $id";
          $this->con->query($actualizarPorcentaje);
        }
      }
      // Cálculo de porcentaje basado en la regla de tres
      if ($tiempoSolucion > 0 && $fechaActualDate <= $fechaTermino) { // Evitar división por cero
        $nuevoPorcentaje = ($diasTranscurridos / $tiempoSolucion) * 100;

        // Asegurar que el porcentaje no supere el 100%
        if ($nuevoPorcentaje > 100) {
          $nuevoPorcentaje = 100;
        }

        // Solo actualizar si el nuevo porcentaje es mayor al actual
        if ($nuevoPorcentaje > $porcentajeActual) {
          $actualizarPorcentaje = "UPDATE ds_soporte SET porcentaje = $nuevoPorcentaje WHERE id_ticket = $id";
          $this->con->query($actualizarPorcentaje);
        }
      }
    }
  }
  public function asignar(): int{
    $sql = "SELECT  COUNT(*) AS actividades_asignar 
          FROM ds_soporte
          WHERE porcentaje < 100 
          AND estado <> 4 
          AND estado <> 0
          AND id_personal_soporte = 0";

    $result = $this->con->query($sql);
    // Valor inicial de cantidad
    $cantidad = 0;
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $cantidad = (int)$row["actividades_asignar"];
    }

    return $cantidad;

  }
}
