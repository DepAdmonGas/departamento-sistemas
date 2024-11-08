<?php
class Estaciones
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function editarEstacion($numLista, $columna, $valor): bool
  {
    $resultado = true;
    $sql = "UPDATE tb_estaciones SET $columna = '$valor' WHERE numlista = $numLista";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
  public function eliminarEstacion($estacion): bool
  {
    $resultado = true;
    $sql = "UPDATE tb_estaciones SET estatus = 1 WHERE numlista = $estacion";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
}
