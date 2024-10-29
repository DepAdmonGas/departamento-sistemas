<?php

class SistemasContenido
{

  private $ClassConexionBD;
  private $con;

  public function __construct()
  {
    $this->ClassConexionBD = new ConexionBD();
    $this->con = $this->ClassConexionBD->conectarBD();
  }

  public function soporteContenido($idRegistro)
  {

    $sql = "SELECT
        ds_soporte.id_ticket, 
        ds_soporte.id_personal,
        ds_soporte.descripcion,
        ds_soporte.prioridad,
        ds_soporte.fecha_creacion,
        ds_soporte.fecha_inicio,        
        ds_soporte.fecha_termino,
        ds_soporte.tiempo_solucion,
        ds_soporte.fecha_termino_real,
        ds_soporte.id_personal_soporte,
        ds_soporte.estado,
        tb_usuarios.nombre,
        tb_estaciones.nombre AS nomestacion,
        tb_puestos.tipo_puesto
        FROM ds_soporte 
        INNER JOIN tb_usuarios 
        ON ds_soporte.id_personal = tb_usuarios.id
        INNER JOIN tb_estaciones
        ON tb_usuarios.id_gas = tb_estaciones.id
        INNER JOIN tb_puestos
        ON tb_usuarios.id_puesto = tb_puestos.id
        WHERE id_ticket = '" . $idRegistro . "' LIMIT 1 ";
    $result = mysqli_query($this->con, $sql);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $descripcion = $row['descripcion'];
      $prioridad = $row['prioridad'];
      $estado = $row['estado'];
      $idSolicitante = $row['id_personal'];
      $idPersonalSoporte = $row['id_personal_soporte'];
      $fechaterminoreal = $row['fecha_termino_real'];
    }

    $Resultado = array(
      "descripcion" => $descripcion,
      "prioridad" => $prioridad,
      "estado" => $estado,
      "idSolicitante" => $idSolicitante,
      "idPersonalSoporte" => $idPersonalSoporte,
      "fechaterminoreal" => $fechaterminoreal
    );

    return $Resultado;
  }

  //-------- TOTAL DE COMENTARIOS -------//

  public function ToComentarios($idticket)
  {
    $sql = "SELECT id FROM ds_soporte_comentarios WHERE id_ticket = '" . $idticket . "' ";
    $result = mysqli_query($this->con, $sql);
    return $numero = mysqli_num_rows($result);
  }

  //-------------------- RESPONSABLE ------------//

  public function Responsable($id)
  {
    $Usuario = "Sistemas";
    $sql_resp = "SELECT nombre FROM tb_usuarios WHERE id = '" . $id . "'  ";
    $result_resp = mysqli_query($this->con, $sql_resp);
    $numero_resp = mysqli_num_rows($result_resp);
    while ($row_resp = mysqli_fetch_array($result_resp, MYSQLI_ASSOC)) {
      $Usuario = $row_resp['nombre'];
    }
    return $Usuario;
  }

  public function TotalConte($idPersonal)
  {
    $sql_rs = "SELECT id_ticket FROM ds_soporte WHERE id_personal = '" . $idPersonal . "' ";
    $result_rs = mysqli_query($this->con, $sql_rs);
    $numero_rs = mysqli_num_rows($result_rs);

    return $numero_rs;
  }

  public function TotalConteSistemas($idPersonal)
  {
    $sql_rs = "SELECT id_ticket FROM ds_soporte WHERE estado <> 4 ";
    $result_rs = mysqli_query($this->con, $sql_rs);
    $numero_rs = mysqli_num_rows($result_rs);

    return $numero_rs;
  }

  //------------------------------------------------------------------------------------

  public function UltimoRegistro($idPersonal)
  {

    $sql = "SELECT
              ds_soporte.id_ticket,
              ds_soporte.fecha_termino,
              tb_estaciones.nombre AS nomestacion,
              tb_puestos.tipo_puesto
          FROM ds_soporte 
          INNER JOIN tb_usuarios 
              ON ds_soporte.id_personal = tb_usuarios.id
          INNER JOIN tb_estaciones
              ON tb_usuarios.id_gas = tb_estaciones.id
          INNER JOIN tb_puestos
              ON tb_usuarios.id_puesto = tb_puestos.id
          WHERE ds_soporte.fecha_termino <> '0000-00-00 00:00:00'
              AND ds_soporte.id_personal_soporte = $idPersonal
              AND ds_soporte.estado <> 4
          ORDER BY ds_soporte.fecha_termino DESC 
          LIMIT 1";
    $result = mysqli_query($this->con, $sql);
    $idticket = 0;
    $fechatermino = '';
    $nomestacion = '';
    $tipopuesto = '';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $idticket = $row['id_ticket'];
      $fechatermino = $row['fecha_termino'];
      $nomestacion = $row['nomestacion'];
      $tipopuesto = $row['tipo_puesto'];
    }

    $Resultado = array(
      "idticket" => $idticket,
      "fechatermino" => $fechatermino,
      "nomestacion" => $nomestacion,
      "tipopuesto" => $tipopuesto
    );

    return $Resultado;
  }
}
