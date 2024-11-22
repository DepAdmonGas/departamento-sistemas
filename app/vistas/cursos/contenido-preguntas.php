<?php
include_once "../../help.php";
$idTema = $_GET['idTema'];

echo '<div class="row">';

$sqlPregunta = "SELECT * FROM tb_cursos_temas_preguntas WHERE id_tema = '" . $idTema . "' ";
$resultPregunta = mysqli_query($con, $sqlPregunta);
while ($rowPregunta = mysqli_fetch_array($resultPregunta, MYSQLI_ASSOC)) {
  $pregunta = $rowPregunta['titulo'];
  echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">';
  echo '<div class="pregunta-editable" ondblclick="habilitarEdicion(this)" oninput="editarPregunta(this, ' . $rowPregunta['id'] . ')"><b>' . $pregunta . '</b></div>';
  echo '<div class="p-3">
                  <ol style="list-style-type:lower-alpha">';
  $sqlRespuesta = "SELECT * FROM tb_cursos_temas_preguntas_respuestas WHERE id_pregunta = '" . $rowPregunta['id'] . "' ";
  $resultRespuesta = mysqli_query($con, $sqlRespuesta);
  while ($rowRespuesta = mysqli_fetch_array($resultRespuesta, MYSQLI_ASSOC)) {
    $checked = $rowRespuesta['valor'] == 1 ? 'checked' : '';
    echo '<li> <input type="radio" name="preg' . $rowPregunta['num_pregunta'] . '" onclick="GuardarRespuesta(' . $rowRespuesta['id'] . ',' . $rowRespuesta['id_pregunta'] . ')" ' . $checked . '> ' . $rowRespuesta['titulo'] . '</li>';
  }
  echo '</ol>
                  </div>';
  echo '</div>';
}

echo '</div>';
