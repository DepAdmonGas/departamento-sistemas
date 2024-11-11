<?php
class Cheque
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function editarCheque($numLista, $columna, $valor,$idEstacion):bool
  {
    $resultado = true;
    $tabla = "op_solicitud_cheque";
    $id = "id = $numLista";
    if($columna == "razonSocialEstacion"){
        $tabla = "tb_estaciones";
        $id = "id = $idEstacion";
    }
    if($columna == "razonSocialEstacion" || $columna == "razonSocialSolicitud"){
        $columna = "razonsocial";
    }
    
    $sql = "UPDATE $tabla SET $columna = '$valor' WHERE $id";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
}