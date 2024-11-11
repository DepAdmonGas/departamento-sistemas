<?php
class Curso
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }
  public function nuevoModulo($titulo):bool
  {
    $resultado = true;
    $numModulo = $this->ultimoRegistro();
    $sql = "INSERT INTO tb_cursos_modulos (titulo,num_modulo) VALUES ('$titulo',$numModulo)";
    $result = $this->conexion->query($sql);
    if(!$result){
      $resultado = false;
    }
    return $resultado;
  }
  public function editarModulo($id,$valor): bool{
    $resultado = true;
    $sql = "UPDATE tb_cursos_modulos SET titulo = '$valor' WHERE num_modulo = $id";
    $result = $this->conexion->query($sql);
    if(!$result){
      $resultado = false;
    }
    return $resultado;
  }
  public function editarTema($id,$valor,$columna,$numTema){
    $resultado = true;
    $sql = "UPDATE tb_cursos_temas SET $valor = '$columna' WHERE id_modulo = $id AND num_tema = $numTema";
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
    $sql = "SELECT num_modulo FROM tb_cursos_modulos ORDER BY num_modulo DESC LIMIT 1";
    $result = $this->conexion->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
      $id = ($row['num_modulo'] + 1);
    }
    return $id;
  }
}
