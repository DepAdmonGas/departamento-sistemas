<?php
class Vale
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function editarVale($numLista, $columna, $valor):bool
  {
    $resultado = true;
    $sql = "UPDATE op_solicitud_vale SET $columna = '$valor' WHERE folio = $numLista";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
}