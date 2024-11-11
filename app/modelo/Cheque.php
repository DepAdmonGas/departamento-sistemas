<?php
class Cheque
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function editarCheque($numLista, $columna, $valor): bool
  {
    $resultado = true;
    $tabla = "op_solicitud_cheque";
    if($valor == "razonSocialEstacion"){
        $tabla = "tb_estaciones";
    }
    if($valor == "razonSocialEstacion" || $valor == "razonSocialSolicitud"){
        $valor = "razonsocial";
    }
    $sql = "UPDATE $tabla SET $columna = '$valor' WHERE id = $numLista";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
}