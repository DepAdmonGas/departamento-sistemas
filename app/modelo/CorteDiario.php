<?php
class CorteDiario
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function editarCorte($idDia, $detalle,$usuario):bool
  {
    $resultado = true;
    $sqlH = "INSERT INTO op_corte_dia_hist (
   id_corte,
   id_usuario,
   detalle
    )
    VALUES 
    (
    '".$idDia."',
    '".$usuario."',
    '".$detalle."'
    )";
    $result = $this->conexion->query($sqlH);


    $sql = "UPDATE op_corte_dia SET ventas = 0, tpv = 0, monedero = 0 WHERE id = $idDia";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
  public function finalizarCorte($idDia):bool{
    $resultado = true;
    $sql = "UPDATE op_corte_dia SET ventas = 1, tpv = 1, monedero = 1 WHERE id = $idDia ";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
}