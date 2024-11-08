<?php
class Personal
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function nuevoPersonal($nombre,$puesto,$usuario,$password,$estacion,$email,$telefono): bool
  {
    $resultado = true;
    $sql = "INSERT INTO tb_usuarios (nombre,id_puesto,usuario,password,id_gas,email,telefono) 
            VALUES ('$nombre','$puesto','$usuario','$password','$estacion','$email','$telefono')";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
  public function eliminarPersonal($id): bool{
    $resultado = true;
    $sql = "UPDATE tb_usuarios SET estatus = 1 WHERE id = $id";
    $result = $this->conexion->query($sql);
    if(!$result){
      $resultado = false;
    }
    return $resultado;
  }
  public function editarPersonal($id, $columna, $valor): bool
  {
    $resultado = true;
    $sql = "UPDATE tb_usuarios SET $columna = '$valor' WHERE id = $id";
    $result = $this->conexion->query($sql);
    if (!$result) {
      $resultado = false;
    }
    return $resultado;
  }
}
