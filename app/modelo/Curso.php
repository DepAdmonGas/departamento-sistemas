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
  public function nuevoTema($modulo,$titulo){
    $id = 1;
    $numTema = 0;
    $estado = 1;
    $numTema = $this->ultimoRegistroTema($modulo);
    $sql = "INSERT INTO tb_cursos_temas (id_modulo,num_tema,titulo,estado) VALUES ($modulo,$numTema,'$titulo',$estado)";
    $result = $this->conexion->query($sql);
    if(!$result){
      return $numTema;
    }
    $id = $this->idAgregado();
    return $id;
  }
  private function ultimoRegistroTema($modulo):int {
    $id = 0;
    $sql = "SELECT num_tema FROM tb_cursos_temas WHERE id_modulo = $modulo ORDER BY num_tema DESC LIMIT 1";
    $result = $this->conexion->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
      $id = ($row['num_tema'] + 1);
    }
    return $id;
  }
  private function idAgregado(): int{
    $id = 0;
    $sql = "SELECT id FROM tb_cursos_temas ORDER BY id DESC LIMIT 1";
    $result = $this->conexion->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
      $id = ($row['id']);
    }
    return $id;
  }
}
