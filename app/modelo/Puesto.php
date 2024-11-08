<?php
class Puesto
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function nuevoPuesto(): int
  {
    $resultado = 0;
    $sql = "INSERT INTO tb_puestos (estatus) VALUES (0)";
    $result = $this->conexion->query($sql);
    if($result){
      $resultado = $this->ultimoRegistro();
    }
    return $resultado;
  }
  public function editarPuesto($valor,$id): bool{
    $resultado = true;
    $sql = "UPDATE tb_puestos SET tipo_puesto = '$valor' WHERE id = $id";
    $result = $this->conexion->query($sql);
    if(!$result){
      $resultado = false;
    }
    return $resultado;

  }
  public function eliminarPuesto($id): bool{
    $resultado = true;
    $sql = "UPDATE tb_puestos SET estatus = 1 WHERE id = $id";
    $result = $this->conexion->query($sql);
    if(!$result){
      $resultado = false;
    }
    return $resultado;
  }
  private function ultimoRegistro(): int{
    $id = 0;
    $sql = "SELECT id FROM tb_puestos WHERE estatus <> 1 ORDER BY id DESC LIMIT 1";
    $result = $this->conexion->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
      $id = $row['id'];
    }
    return $id;
  }
}
