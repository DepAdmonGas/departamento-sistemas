<?php
class Cheque
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function editarCheque($numLista, $columna, $valor, $idEstacion): bool
  {
    $resultado = true;
    $tabla = "op_solicitud_cheque";
    $id = "id = $numLista";

    $sql = "UPDATE $tabla SET $columna = '$valor' WHERE $id";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
  public function firmarSolicitud($id, $opcion)
  {
    $resultado = true;
    $usuario = 19;
    if ($opcion == 2) {
      $usuario = 2;
    }
    $sql = "INSERT INTO op_solicitud_cheque_token (id_solicitud,id_usuario,token) VALUES ($id,$usuario,$id)";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    $firma = $this->firmar($id, $opcion, $usuario);
    if ($firma == 0) {
      $resultado = false;
    }
    return $resultado;
  }
  private function firmar($id, $estado, $usuario)
  {
    $resultado = true;
    $tipoFirma = 'B';
    if ($estado == 2) {
      $tipoFirma = 'C';
    }
    $Firma = "Firma: " . bin2hex(random_bytes(64)) . "." . uniqid();

    $sql = "SELECT * FROM op_solicitud_cheque_token WHERE id_solicitud = $id and id_usuario = $usuario and token = $id ORDER BY id ASC LIMIT 1 ";
    $result = mysqli_query($this->conexion, $sql);
    $numero = mysqli_num_rows($result);

    if ($numero == 1) {

      $sql = "UPDATE op_solicitud_cheque SET 
        status = '" . $estado . "'
        WHERE id = '" . $id . "' ";

      if (mysqli_query($this->conexion, $sql)) {

        $sql_insert2 = "INSERT INTO op_solicitud_cheque_firma (
          id_solicitud,
          id_usuario,
          tipo_firma,
          firma
              ) 
              VALUES 
              (
              '" . $id . "',
              '" . $usuario . "',
              '" . $tipoFirma . "',
              '" . $Firma . "'
              )";

        if (mysqli_query($this->conexion, $sql_insert2)) {
        } else {
          $resultado = false;
        }
      } else {
        $resultado = false;
      }
    } else {
      $resultado = false;
    }
    return $resultado;
  }
}
