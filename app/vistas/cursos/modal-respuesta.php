<?php
require('../../help.php');
$idTema = $_GET['idTema'];
$idPregunta = $_GET['idPregunta'];
?>

<div class="modal-header">
  <h5 class="modal-title">Respuesta</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

  <div class="row">

    <div class="col-12 mb-2">
      <div class="mb-1 mt-2 text-secondary fw-bold">Selecciona Pregunta:</div>
      <select class="form-control" id="pregunta" onchange="cargaRespuestas(<?= $idTema ?>)">
        <option value=""></option>
        <?php
        $sql_area = "SELECT id, titulo FROM tb_cursos_temas_preguntas WHERE id_tema = $idTema";
        $result_area = mysqli_query($con, $sql_area);
        while ($row_area = mysqli_fetch_array($result_area, MYSQLI_ASSOC)) {
          $id = $row_area['id'];
          $titulo = $row_area['titulo'];
          echo "<option value='$id'>" . $titulo . "</option>";
        }
        ?>
      </select>
    </div>
    <div class="row">
      <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 mb-2">
        <div class="mb-1 mt-2 text-secondary fw-bold">* Nueva Respuesta:</div>
        <input type="text" class="form-control rounded-0" id="respuesta">
      </div>
      <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 text-end mt-5">
        <button type="button" class="btn btn-labeled2 btn-primary" onclick="agregarRespuesta(<?=$idTema?>,<?=$idPregunta?>)">
          <span class="btn-label2"><i class="fa fa-check"></i></span>Agregar</button>
      </div>
    </div>
    <hr>
    <div class="mb-1 text-secondary fw-bold">Respuestas:</div>
    <?php if ($idPregunta != 0) { ?>
      <div class="table-responsive col-12 mt-2">
        <table class="custom-table" style="font-size: .8em;" width="100%">
          <thead class="tables-bg text-white">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Titulo</th>
              <th class="text-center" width="20"><img src="<?= RUTA_IMG_ICONOS ?>eliminar.png"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT id, titulo FROM tb_cursos_temas_preguntas_respuestas WHERE id_pregunta = $idPregunta";
            $result = mysqli_query($con, $sql);
            $numero = mysqli_num_rows($result);
            if ($numero > 0) {
              while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $numModulo = $row['id'];
                $titulo = $row['titulo'];
            ?>
                <tr>
                  <th class="text-center align-middle"><?= $numModulo ?></th>
                  <td class="align-middle" ondblclick="habilitarEdicion(this)">
                    <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarModulo(this,<?= $numModulo ?>)"><?= $titulo ?></div>
                  </td>
                  <td class="align-middle" ondblclick="eliminarRespuesta()" width="20">
                    <img src="<?= RUTA_IMG_ICONOS ?>eliminar.png">
                  </td>
                </tr>
            <?php }
            } else {
              echo "<th class='colspan='2' text-center align-middle'>No se encontraron registros</th>";
            } ?>
          </tbody>
        </table>

      </div>
    <?php } ?>
  </div>

</div>